<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run(): void
    {
        $faker = Faker::create();
        for($i = 0; $i < 50; $i++) {
            DB::table('medicine')->insert([
                'name' => $faker->word(),
                'brand' => $faker->company(),
                'dosage' => $faker->randomElement(['250mg', '500mg', '750mg', '1000mg']),
                'form' => $faker->randomElement(['tablet', 'capsule', 'syrup', 'injection']),
                'price' => $faker->randomFloat(2, 5, 100),
                'stock' => $faker->numberBetween(10, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
