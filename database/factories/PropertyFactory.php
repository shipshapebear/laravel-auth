<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
     
            //
            return [
                'tdId' => $this->faker->randomNumber(5),
                'ownerId' => $this->faker->randomNumber(5),
                'name' => $this->faker->name(),
                'address' => $this->faker->address(),
                'classification' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
                'assessedValue' => $this->faker->randomFloat(2, 100, 10000),
            ];
        
    }
}
