<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadContact extends Model
{
    protected $fillable = [
        'name',
        'designation',
        'email',
        'mobile',
        'landline',
        'whatsapp',
        'notes',
        'is_primary',
    ];
}
