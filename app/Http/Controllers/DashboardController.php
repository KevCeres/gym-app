<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Exercise;
use App\Models\RoutineTemplate;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $adminStats = null;
        if ($user->role === 'admin') {
            $adminStats = [
                'users' => User::count(),
                'categories' => Category::count(),
                'exercises' => Exercise::count(),
                'workouts' => Workout::count(),
            ];
        }

        $athleteStats = null;
        if ($user->role === 'user') {
            $athleteStats = [
                'workout_rows' => Workout::where('user_id', $user->id)->count(),
                'templates' => RoutineTemplate::where('user_id', $user->id)->count(),
            ];
        }

        return view('dashboard', [
            'adminStats' => $adminStats,
            'athleteStats' => $athleteStats,
        ]);
    }
}
