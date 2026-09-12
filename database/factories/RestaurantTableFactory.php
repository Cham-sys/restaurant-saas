<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RestaurantTable>
 */
class RestaurantTableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'restaurant_id' => Restaurant::factory(),
            'number' => 'T-'.fake()->unique()->numerify('##'),
            'name' => null,
            'seats' => 2,
            'qr_token' => fake()->unique()->regexify('[A-Za-z0-9]{24}'),
            'is_active' => true,
        ];
    }
}
