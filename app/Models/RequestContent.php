<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestContent extends Model
{
    protected $table = 'request_contents';

    protected $fillable = [
        'request_id',
        'special_note',
        'description'
    ];

    public function businessRequest()
    {
        return $this->belongsTo(BusinessRequest::class, 'request_id');
    }
}