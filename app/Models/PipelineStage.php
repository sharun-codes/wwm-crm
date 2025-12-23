<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 

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

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'pipeline_stage_id'); 
    }
}
