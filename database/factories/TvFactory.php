<?php

namespace Database\Factories;

use App\Models\Tv;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tv>
 */
class TvFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'TV-'.fake()->unique()->numberBetween(1, 99),
            'type' => fake()->randomElement(['ps3', 'ps4', 'ps5', 'sim_racing', 'nintendo_switch']),
            'price_per_hour' => 15000,
            'status' => 'available',
            'is_buzzer_on' => false,
        ];
    }
}
