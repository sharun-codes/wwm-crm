<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
    ];

    protected static function booted()
    {
        static::updated(function ($client) {
            if ($client->company_id) {
                $client->deals()
                    ->whereNull('company_id')
                    ->update(['company_id' => $client->company_id]);
            }
        });
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
