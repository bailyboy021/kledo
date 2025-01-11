<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Approval;
use App\Models\ApprovalStage;
use App\Models\Status;

class Expense extends Model
{
    use SoftDeletes;

    protected $table = "expenses";
    protected $primarykey = "id";

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public static function getExpense(array $params = [])
    {
        try {
            return DB::transaction(function () use ($params) {
                $expense = self::with([
                    'status', 
                    'approvals' => function ($query) {
                        $query->with(['approver', 'status']);
                    }
                ])->find($params['id']);

                if (!$expense) {
                    abort(404, 'Expense not found');
                }

                return [
                    'id' => $expense->id,
                    'amount' => $expense->amount,
                    'status' => [
                        'id' => $expense->status->id,
                        'name' => $expense->status->name,
                    ],
                    'approvals' => $expense->approvals->map(function ($approval) {
                        return [
                            'id' => $approval->id,
                            'approver' => [
                                'id' => $approval->approver->id,
                                'name' => $approval->approver->name,
                            ],
                            'status' => [
                                'id' => $approval->status->id,
                                'name' => $approval->status->name,
                            ],
                        ];
                    })->toArray(),
                ];
            });
        } catch (\Exception $e) {
            Log::error('Failed to get expense: ' . $e->getMessage());    
            return false;
        }
    }

    public static function addExpense(array $params = [])
    {
        try {
            return DB::transaction(function () use ($params) {
                $data = array(
                    'amount' => $params['amount'],
                    'status_id' => 1
                );

                $expense = self::create($data);

                if (!$expense) {
                    abort(400, 'Failed to add expense');
                }

                // Ambil semua approval stages untuk membuat entri approval
                $approvalStages = ApprovalStage::orderBy('id', 'asc')->get();

                if ($approvalStages->isEmpty()) {
                    abort(400, 'No approval stages defined');
                }

                foreach ($approvalStages as $stage) {
                    $approvalData = [
                        'expense_id' => $expense->id,
                        'approver_id' => $stage->approver_id,
                        'status_id' => 1 //"menunggu persetujuan"
                    ];

                    $approval = Approval::create($approvalData);

                    if (!$approval) {
                        abort(400, 'Failed to create approval entry');
                    }
                }
            
                return $expense;
            });
        } catch (\Exception $e) {
            Log::error('Failed to add expense: ' . $e->getMessage());    
            return false;
        }
    }

    public static function approveExpense(array $params = [])
    {
        try {
            return DB::transaction(function () use ($params) {
                $currentApproval = Approval::where('expense_id', $params['id'])
                    ->where('approver_id', $params['approver_id']) 
                    ->where('status_id', 1)
                    ->orderBy('id', 'asc')
                    ->first();

                // dd($currentApproval);

                if (!$currentApproval) {
                    abort(400, 'No expense to approve or invalid approver for this stage.');
                }

                // Cek apa ada tahap sebelumnya yang belum disetujui
                $previousPendingApprovals = Approval::where('expense_id', $currentApproval->expense_id)
                    ->where('id', '<', $currentApproval->id)
                    ->where('status_id', 1)
                    ->exists();

                if ($previousPendingApprovals) {
                    abort(400, 'You cannot approve this stage before previous stages are approved.');
                }

                $currentApproval->status_id = 2;
                $currentApproval->update();

                // Cek apakah semua tahap approval sudah selesai
                $expense = self::find($currentApproval->expense_id);
                $allApprovalsApproved = $expense->approvals()->where('status_id', '!=', 2)->doesntExist();

                if ($allApprovalsApproved) {
                    $expense->status_id = 2; // Status pengeluaran menjadi disetujui
                    $expense->update();
                }

                return [
                    'message' => 'Approval successful',
                    'current_approval' => $currentApproval,
                    'expense' => $expense,
                ];                        
            });
        } catch (\Exception $e) {
            Log::error('Failed to approve expense: ' . $e->getMessage());
            abort(400, $e->getMessage());         
        }
    }
}
