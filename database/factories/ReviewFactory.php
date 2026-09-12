<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Review> */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'restaurant_id' => Restaurant::factory(),
            'customer_name' => fake()->name(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->sentence(),
        ];
    }
}
