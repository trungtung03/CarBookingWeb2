<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Car;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Car::create([
            'depature_location' => 'Hanoi',
            'destination' => 'Ho Chi Minh City',
            'name' => 'Car 1',
            'license_plates' => '30A-12345',
            'image' => 'image1.jpg',
            'price' => 100.00,
            'type_name' => 'SUV',
            'id_user' => 1,
        ]);
    }
}
