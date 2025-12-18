<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenewalDealDetail extends Model
{
    protected $fillable = [
        'deal_id',
        'previous_expiry_date',
        'renewal_period_months',
        'churn_risk',
    ];
}
