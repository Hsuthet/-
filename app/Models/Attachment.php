<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
    'request_id',
    'file_name',
    'file_path',
    'file_type',
    'file_size'
];
}
