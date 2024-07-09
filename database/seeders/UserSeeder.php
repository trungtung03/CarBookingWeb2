<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'full_name' => 'John Doe',
            'password' => bcrypt('password'),
            'email' => 'john@example.com',
            'phone_number' => '123456789',
            'avatar' => 'avatar.jpg',
            'role' => 'admin',
            'address' => '123 Main St',
            'date_of_birth' => '1990-01-01'
        ]);
    }
}
