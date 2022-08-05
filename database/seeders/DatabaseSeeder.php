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
                'tdId' => $faker->unique()->randomFloat(0, 100, 10000),
                'name' => $faker->name,
                'address' => $faker->address,
                'classification' => $faker->randomElement(['A', 'B', 'C', 'D', 'E']),
                'assessedValue' => $faker->randomFloat(2, 100, 10000),
            ]);

            DB::table('payments')->insert([
                'transaction_id' => $faker->unique()->randomFloat(0, 200, 20000),
                'amount' => $faker->randomFloat(0, 100, 200),
                'payment_for' => 'RPT',
                'quarters' => '4',
                'payment_method' => 'Gcash',
                'payment_type' => $faker->randomElement(['installment', 'full-payment']),
                'name' => $faker->name,
                'payment_status' => $faker->randomElement(['pending', 'success', 'canceled']),
                'date_of_payment' => $faker->dateTime(),
                'tdId' => '2901',
                'ownerId' => '203',
            ]);


        }

    }
}
