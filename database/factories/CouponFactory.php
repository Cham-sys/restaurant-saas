<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Coupon> */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'restaurant_id' => Restaurant::factory(),
            'code' => fake()->unique()->bothify('SAVE##??'),
            'name' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'is_active' => true,
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 50,
            'max_uses' => 500,
            'used_count' => 0,
            'max_uses_per_user' => 1,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
            'apply_to_all' => true,
            'product_ids' => null,
        ];
    }
}
