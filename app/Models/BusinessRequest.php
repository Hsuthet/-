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
        'status',
        'description',
        'special_note'
    ];

    public function targetDepartment(): BelongsTo
    {
      
        return $this->belongsTo(Department::class, 'department_id');
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
        public function categories()
    {
        return $this->belongsToMany(
            \App\Models\Category::class,
            'category_request',          // pivot table name
            'request_id',       //  foreign key in pivot
            'category_id'                //  related key in pivot
        );
    }

 /**
     * RELATIONSHIP: Link to the request_contents table
     */
    public function requestContent(): HasOne
    {
      
        return $this->hasOne(RequestContent::class, 'request_id');
    }

    public function attachments()
    {
    return $this->hasMany(Attachment::class, 'request_id');
    }


}