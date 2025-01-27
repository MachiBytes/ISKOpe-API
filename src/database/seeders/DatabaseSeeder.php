<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test Student 1',
            'studentId' => 'student1',
            'password' => 'student1',
            'isAdmin' => 0
        ]);

        User::factory()->create([
            'name' => 'Test Student 2',
            'studentId' => 'student2',
            'password' => 'student2',
            'isAdmin' => 0
        ]);

        User::factory()->create([
            'name' => 'Test Admin',
            'studentId' => 'admin1',
            'password' => 'admin1',
            'isAdmin' => 1
        ]);
    }
}
