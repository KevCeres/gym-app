<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relacion con la tabla de usuarios
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');  //Relacion con la tabla de ejercicios 
            $table->integer('reps'); // Repeticiones
            $table->decimal('weight', 8, 2); // Peso levantado
            $table->date('workout_date'); // Fecha del entrenamiento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
