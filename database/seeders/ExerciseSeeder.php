<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Exercise;
use Illuminate\Database\Seeder;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Press banca', 'description' => 'Press plano con barra.', 'category' => 'Pecho'],
            ['name' => 'Press inclinado mancuernas', 'description' => 'Banco 30–45°.', 'category' => 'Pecho'],
            ['name' => 'Dominadas', 'description' => 'Jalón con peso corporal.', 'category' => 'Espalda'],
            ['name' => 'Remo con barra', 'description' => 'Torso inclinado.', 'category' => 'Espalda'],
            ['name' => 'Sentadilla', 'description' => 'Sentadilla trasera con barra.', 'category' => 'Pierna'],
            ['name' => 'Prensa de piernas', 'description' => 'Máquina prensa.', 'category' => 'Pierna'],
            ['name' => 'Press militar', 'description' => 'Press de hombro de pie.', 'category' => 'Hombro'],
            ['name' => 'Elevaciones laterales', 'description' => 'Mancuernas.', 'category' => 'Hombro'],
            ['name' => 'Curl bíceps', 'description' => 'Mancuernas alternas.', 'category' => 'Bíceps'],
            ['name' => 'Press francés', 'description' => 'Tríceps acostado.', 'category' => 'Tríceps'],
            ['name' => 'Plancha abdominal', 'description' => 'Isométrico.', 'category' => 'Abdomen'],
            ['name' => 'Cinta o bicicleta', 'description' => 'Calentamiento o cardio.', 'category' => 'Cardio'],
        ];

        $byName = Category::all()->keyBy('name');

        foreach ($rows as $row) {
            $category = $byName->get($row['category']);
            if (! $category) {
                continue;
            }
            Exercise::create([
                'name' => $row['name'],
                'description' => $row['description'],
                'category_id' => $category->id,
                'image' => null,
            ]);
        }
    }
}
