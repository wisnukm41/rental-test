<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = \App\Models\User::pluck('id');

        return [
            'user_id' => fake()->randomElement($userIds),
            'image' => 'images/car.jpeg',
            'brand' => fake()->word(),
            'model' => fake()->word(),
            'police_number' => fake()->regexify('D [0-9]{4} [A-Z]{2,3}'),
            'rent_fee' => fake()->numberBetween(1000, 100000),
        ];
    }
}
