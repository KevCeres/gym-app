<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = ['name', 'description', 'image', 'category_id'];

    // Un ejercicio pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Un ejercicio puede estar en muchos registros de entrenamiento
    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }
}
