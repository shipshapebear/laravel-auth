<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //create fake data for testing

        $faker = Faker\Factory::create();
        for ($i = 0; $i < 200; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => $faker->password,
                'status' => $faker->randomElement(['active', 'inactive']),
                'role' => $faker->randomElement(['admin', 'user']),

            ]);
            
            DB::table('properties')->insert([
                'name' => $faker->name,
                'address' => $faker->address,
                'classification' => $faker->randomElement(['A', 'B', 'C', 'D', 'E']),
                'assessedValue' => $faker->randomFloat(2, 100, 10000),
            ]);
        }

    }
}
