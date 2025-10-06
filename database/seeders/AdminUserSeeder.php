<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       {
    \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('admin123'), // Change this password!
        'role' => \App\Models\User::ROLE_ADMIN,
    ]);

    \App\Models\User::create([
        'name' => 'Regular User', 
        'email' => 'user@example.com',
        'password' => bcrypt('user123'), // Change this password!
        'role' => \App\Models\User::ROLE_USER,
    ]);
}
    }
}
