<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Invoice> */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $total = fake()->randomFloat(2, 30, 200);

        return [
            'order_id' => Order::factory(),
            'restaurant_id' => Restaurant::factory(),
            'invoice_number' => fake()->unique()->bothify('INV-########'),
            'subtotal' => $total,
            'tax_amount' => 0,
            'total_amount' => $total,
            'status' => 'pending',
            'paid_at' => null,
        ];
    }
}
