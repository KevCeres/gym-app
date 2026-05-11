<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Pecho',
            'Espalda',
            'Pierna',
            'Hombro',
            'Bíceps',
            'Tríceps',
            'Abdomen',
            'Glúteo',
            'Antebrazo',
            'Trapecio',
            'Gemelos',
            'Cardio',
        ];

        foreach ($names as $name) {
            Category::create(['name' => $name]);
        }
    }
}
