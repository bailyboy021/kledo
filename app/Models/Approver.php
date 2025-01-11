<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Approver extends Model
{
    use SoftDeletes;

    protected $table = "approvers";
    protected $primarykey = "id";

    protected $guarded = [
        'id'
    ];

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
            
                return $approver;
            });
        } catch (\Exception $e) {
            Log::error('Failed to add approver: ' . $e->getMessage());    
            return false;
        }
    }
}
