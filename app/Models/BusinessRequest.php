<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessRequest extends Model
{
    use HasFactory;

    protected $table = 'requests';

    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */
 
    // const PENDING = 'PENDING';
    // const APPROVED = 'APPROVED';
    // const WORKING = 'WORKING';
    // const COMPLETED = 'COMPLETED';
    // const REJECTED = 'REJECTED';


    protected $fillable = [
        'request_number',
        'title',
        'user_id',
        'department_id',
        'target_department_id',
        'worker_id',
        'reject_reason',
        'status',
        'due_date'
    ];



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    

    // Request creator
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    

    // Requester's department
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Target department
    public function targetDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'target_department_id');
    }

    // Assigned worker
    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    // Request categories
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_request',
            'request_id',
            'category_id'
        );
    }

    // Request content
    public function requestContent(): HasOne
    {
        return $this->hasOne(RequestContent::class, 'request_id');
    }

    // Attachments
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class, 'request_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    // Requests created by employee
    public function scopeForEmployee($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Requests assigned to worker
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('worker_id', $userId);
    }

    public function approvals()
{
    return $this->hasMany(Approval::class, 'request_id');
}

// Helper to get the latest rejection reason easily
public function latestRejection()
{
    return $this->hasOne(Approval::class, 'request_id')
                ->where('approval_status', 'REJECTED')
                ->latestOfMany();
}
public function getStatusConfigAttribute()
{
    return [
        'PENDING'   => [
            'label' => '承認待ち', 
            'color' => 'bg-amber-50 text-amber-700 border-amber-200'
        ],
        'APPROVED'  => [
            'label' => '承認済み', 
            'color' => 'bg-indigo-50 text-indigo-700 border-indigo-200'
        ],
        'REJECTED'  => [
            'label' => '却下', 
            'color' => 'bg-rose-50 text-rose-700 border-rose-200'
        ],
        // Blue indicates "In Progress"
        'WORKING'   => [
            'label' => '作業中', 
            'color' => 'bg-blue-50 text-blue-700 border-blue-200'
        ],
        // Green indicates "Mission Accomplished"
        'COMPLETED' => [
            'label' => '完了', 
            'color' => 'bg-emerald-50 text-emerald-700 border-emerald-200'
        ], 
    ][$this->status] ?? [
        'label' => $this->status,
        'color' => 'bg-slate-50 text-slate-600 border-slate-200'
    ];
}
}