<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = ['user_id', 'exercise_id', 'reps', 'weight', 'workout_date'];

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
