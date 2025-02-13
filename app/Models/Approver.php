<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Approver extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = "approvers";
    protected $primarykey = "id";

    protected $guarded = [
        'id'
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public static function addApprover(array $params = [])
    {
        try {
            return DB::transaction(function () use ($params) {
                $data = array(
                    'name' 	    => $params['name']
                );

                $approver = self::create($data);

                if (!$approver) {
                    abort(400, 'Failed to add approver');
                }
            
                return [
                    'status' => true,
                    'data' => $approver
                ];
            });
        } catch (\Exception $e) {
            Log::error('Failed to add approver: ' . $e->getMessage());    
            return false;
        }
    }
}
