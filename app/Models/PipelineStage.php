<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PipelineStage extends Model
{
    protected $fillable = [
        'pipeline_id',
        'name',
        'slug',
        'sort_order',
        'probability',
        'is_won',
        'is_lost',
    ];

    public function pipeline()
    {
        return $this->belongsTo(Pipeline::class);
    }
}
