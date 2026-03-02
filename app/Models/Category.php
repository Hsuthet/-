<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function requests()
{
    return $this->belongsToMany(Request::class);
}

public function businessRequests()
{
    return $this->belongsToMany(
        \App\Models\BusinessRequest::class,
        'category_request',
        'category_id',
        'request_id'
    );
}
}
