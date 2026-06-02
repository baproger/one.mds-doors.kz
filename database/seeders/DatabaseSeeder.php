<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'admin@mds-doors.kz')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@mds-doors.kz',
                'password' => bcrypt('password123'),
            ]);
        }
    }
}
