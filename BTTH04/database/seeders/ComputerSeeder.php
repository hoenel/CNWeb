<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Computer;
use Faker\Factory as Faker;

class ComputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 51; $i++){
            Computer::create([
                'computer_name' =>  $faker->randomElement(['Lenovo', 'Dell', 'HP', 'Asus', 'Acer']) . ' ' . $faker->bothify('??###'),
                'model' => $faker->bothify('Model-??###'),
                'operating_system' => $faker->randomElement(['Windows 10', 'Windows 11', 'Ubuntu 20.04', 'macOS Monterey', 'Fedora 35']),
                'processor' => $faker->randomElement(['Intel i5', 'Intel i7', 'AMD Ryzen 5', 'AMD Ryzen 7', 'Apple M1']),
                'memory' => $faker->randomElement([2, 4, 8, 16, 32, 64]),
                'available' => $faker->boolean(80)
            ]);
        }
    }
}
