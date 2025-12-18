<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesDealDetail extends Model
{
    protected $fillable = [
        'deal_id',
        'campaign_type',
        'platform',
        'duration_days',
        'expected_reach',
    ];
}
