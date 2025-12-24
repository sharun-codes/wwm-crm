<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'source',
        'status',
        'assigned_to',
        'notes',
    ];

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function contacts()
    {
        return $this->hasMany(LeadContact::class);
    }
}
