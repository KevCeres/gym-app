<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\User::create([
        'name' => 'Kevin Cereceres',
        'email' => 'KevCereceres@gym.com',
        'password' => bcrypt('kev123'),
        'role' => 'admin',
    ]);
}
}
