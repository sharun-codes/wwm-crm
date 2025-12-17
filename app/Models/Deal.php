<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'lead_id',
        'pipeline_id',
        'pipeline_stage_id',
        'value',
        'owner_id',
        'expected_close_date',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }

    public function stage()
    {
        return $this->belongsTo(PipelineStage::class, 'pipeline_stage_id');
    }

    public function salesDetails()
    {
        return $this->hasOne(SalesDealDetail::class);
    }

    public function renewalDetails()
    {
        return $this->hasOne(RenewalDealDetail::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
