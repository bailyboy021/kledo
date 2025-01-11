<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Approval extends Model
{
    use SoftDeletes;

    protected $table = "approvals";
    protected $primarykey = "id";

    protected $guarded = [
        'id'
    ];

    // Relasi ke Approver
    public function approver()
    {
        return $this->belongsTo(Approver::class, 'approver_id', 'id');
    }

    // Relasi ke Status
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
}
