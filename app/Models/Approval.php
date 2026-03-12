<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Approval extends Model
{
    
    use HasFactory;
    protected $table = 'approvals';

    protected $fillable = [
        'request_id',
        'approver_id',
        'approval_status',
        'rejection_reason', // This is the fix for your missing data
        'approved_at',
    ];

    /**
     * Relationship: The request being approved/rejected
     */
    public function businessRequest()
    {
        return $this->belongsTo(BusinessRequest::class, 'request_id');
    }

    /**
     * Relationship: The user who performed the approval/rejection
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}

