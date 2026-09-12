<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Order> */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 30, 200);
        $deliveryFee = 10;

        return [
            'restaurant_id' => Restaurant::factory(),
            'user_id' => User::factory(),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'customer_email' => fake()->safeEmail(),
            'delivery_type' => 'delivery',
            'delivery_address' => fake()->address(),
            'delivery_city' => fake()->city(),
            'delivery_fee' => $deliveryFee,
            'subtotal' => $subtotal,
            'discount' => 0,
            'total_amount' => $subtotal + $deliveryFee,
            'final_amount' => $subtotal + $deliveryFee,
            'payment_status' => 'pending',
            'payment_method' => 'cash',
            'status' => 'pending',
            'tracking_code' => fake()->unique()->bothify('ORD-########'),
        ];
    }
}
