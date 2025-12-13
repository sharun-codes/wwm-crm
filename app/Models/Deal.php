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
}
