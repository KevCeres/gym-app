<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    // Una categoría tiene muchos ejercicios
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}