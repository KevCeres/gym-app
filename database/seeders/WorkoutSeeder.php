<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Database\Seeder;

class WorkoutSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->orderBy('id')->get();
        $exercises = Exercise::query()->orderBy('id')->get();

        if ($users->isEmpty() || $exercises->isEmpty()) {
            return;
        }

        $samples = [
            ['reps' => 10, 'weight' => 40.00, 'days_ago' => 0],
            ['reps' => 8, 'weight' => 45.00, 'days_ago' => 1],
            ['reps' => 12, 'weight' => 35.00, 'days_ago' => 2],
            ['reps' => 15, 'weight' => 20.00, 'days_ago' => 3],
            ['reps' => 6, 'weight' => 50.00, 'days_ago' => 4],
            ['reps' => 10, 'weight' => 60.00, 'days_ago' => 5],
            ['reps' => 12, 'weight' => 25.50, 'days_ago' => 6],
            ['reps' => 8, 'weight' => 30.00, 'days_ago' => 7],
            ['reps' => 20, 'weight' => 10.00, 'days_ago' => 8],
            ['reps' => 10, 'weight' => 55.00, 'days_ago' => 9],
            ['reps' => 5, 'weight' => 70.00, 'days_ago' => 10],
            ['reps' => 12, 'weight' => 42.50, 'days_ago' => 11],
        ];

        foreach ($samples as $i => $row) {
            $user = $users[$i % $users->count()];
            $exercise = $exercises[$i % $exercises->count()];

            Workout::create([
                'user_id' => $user->id,
                'exercise_id' => $exercise->id,
                'reps' => $row['reps'],
                'weight' => $row['weight'],
                'workout_date' => now()->subDays($row['days_ago'])->toDateString(),
            ]);
        }
    }
}
