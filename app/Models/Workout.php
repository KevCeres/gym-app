<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = ['user_id', 'exercise_id', 'reps', 'weight', 'workout_date', 'set_number', 'series_count', 'completed'];

    protected function casts(): array
    {
        return [
            'workout_date' => 'date',
            'set_number' => 'integer',
            'series_count' => 'integer',
            'completed' => 'boolean',
        ];
    }

    /**
     * Número de series (mínimo 1), para usar en vistas sin @php complejo.
     */
    protected function effectiveSeriesCount(): Attribute
    {
        return Attribute::get(function (): int {
            return max(1, (int) ($this->series_count ?? 1));
        });
    }

    /**
     * Volumen de la línea: peso × repeticiones × número de series (cada una con el mismo peso/reps).
     */
    public function lineVolume(): float
    {
        return (float) $this->weight * (int) $this->reps * $this->effective_series_count;
    }

    // El progreso pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // El progreso pertenece a un ejercicio
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
