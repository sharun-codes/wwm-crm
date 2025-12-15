<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'subject_type',
        'subject_id',
        'user_id',
        'type',
        'message',
    ];
}
