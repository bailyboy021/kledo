<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ApprovalStage extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = "approval_stages";
    protected $primarykey = "id";

    protected $guarded = [
        'id'
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'approver_id' => $this->approver_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public static function addApprovalStage(array $params = [])
    {
        try {
            return DB::transaction(function () use ($params) {
                $data = array(
                    'approver_id' => $params['approver_id']
                );

                $approvalStage = self::create($data);

                if (!$approvalStage) {
                    abort(400, 'Failed to add approval stage');
                }
            
                return [
                    'status' => true,
                    'data' => $approvalStage
                ];
            });
        } catch (\Exception $e) {
            Log::error('Failed to add approval stage: ' . $e->getMessage());    
            return false;
        }
    }

    public static function updateApprovalStage(array $params = [])
    {
        try {
            return DB::transaction(function () use ($params) {
                $approvalStage = ApprovalStage::find($params['id']);
            
                if (!$approvalStage) {
                    abort(400, 'Approval stage not found');
                }

                // Perbarui approver_id
                $approvalStage->approver_id = $params['approver_id'];
                $approvalStage->save();

                return [
                    'message' => 'Approval stage updated successfully',
                    'approval_stage' => $approvalStage
                ];
            });
        } catch (\Exception $e) {
            Log::error('Failed to update approval stage: ' . $e->getMessage());    
            return false;
        }
    }
}
