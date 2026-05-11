<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Sistema',
            'email' => 'admin@gym-app.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'María Atleta',
            'email' => 'maria@gym-app.test',
            'password' => 'password123',
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Carlos Atleta',
            'email' => 'carlos@gym-app.test',
            'password' => 'password123',
            'role' => 'user',
        ]);
    }
}
