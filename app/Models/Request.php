<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    
public function categories() {
    return $this->belongsToMany(Category::class, 'category_request');
}


}
