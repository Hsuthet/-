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

    // Connect to the 'requests' table
    protected $table = 'requests';

    protected $fillable = [
        'request_number',
        'title',
        'user_id',
        'department_id',
        'category_id',
        'status',
        'due_date',
        'status'
    ];

    public function targetDepartment(): BelongsTo
    {
        // requests table ထဲက department_id နဲ့ departments table ကို ချိတ်တာပါ
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * RELATIONSHIP: Link to the request_contents table
     */
    public function requestContent(): HasOne
    {
        // 'request_id' is the column in your request_contents table
        return $this->hasOne(RequestContent::class, 'request_id');
    }

    /**
     * RELATIONSHIP: Link to the User who created it
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function department()
{
    return $this->belongsTo(Department::class);
}

// public function category()
// {
//     return $this->belongsTo(Category::class);
// }


public function attachments()
{
    return $this->hasMany(Attachment::class);
}

public function categories()
{
    return $this->belongsToMany(
        \App\Models\Category::class,
        'category_request',          // pivot table name
        'request_id',       //  foreign key in pivot
        'category_id'                //  related key in pivot
    );
}
}