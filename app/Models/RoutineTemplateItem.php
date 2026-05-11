<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoutineTemplateItem extends Model
{
    protected $fillable = [
        'routine_template_id',
        'exercise_id',
        'sort_order',
        'default_reps',
        'default_series_count',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'default_reps' => 'integer',
            'default_series_count' => 'integer',
        ];
    }

    public function routineTemplate(): BelongsTo
    {
        return $this->belongsTo(RoutineTemplate::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
