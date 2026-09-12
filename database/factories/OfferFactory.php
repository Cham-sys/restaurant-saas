<?php

namespace Database\Factories;

use App\Models\Offer;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Offer> */
class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition(): array
    {
        return [
            'restaurant_id' => Restaurant::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'image' => null,
            'is_active' => true,
            'type' => 'percentage',
            'value' => 20,
            'min_order_amount' => 40,
            'max_uses' => 100,
            'used_count' => 0,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
            'apply_to_all' => true,
            'product_ids' => null,
        ];
    }
}
