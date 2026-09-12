<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Order_item> */
class Order_itemFactory extends Factory
{
    protected $model = Order_item::class;

    public function definition(): array
    {
        $product = Product::factory();
        $quantity = fake()->numberBetween(1, 3);
        $price = fake()->randomFloat(2, 10, 100);

        return [
            'order_id' => Order::factory(),
            'product_id' => $product,
            'product_name' => fake()->words(3, true),
            'quantity' => $quantity,
            'price' => $price,
            'total' => $price * $quantity,
            'notes' => null,
        ];
    }
}
