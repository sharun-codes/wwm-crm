<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'industry',
        'website',
        'gst',
        'billing_address',
        'is_active',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

}
