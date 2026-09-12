<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\RestaurantTable;

it('opens a table QR link and keeps the table in the customer session', function () {
    $restaurant = Restaurant::factory()->create();
    $table = RestaurantTable::factory()->create(['restaurant_id' => $restaurant->id]);

    $this->get(route('restaurant.table.menu', [$restaurant->slug, $table->qr_token]))
        ->assertRedirect(route('restaurant.menu', $restaurant->slug));

    expect(session('restaurant_table_id'))->toBe($table->id);
});

it('creates a dine-in order linked to the scanned table', function () {
    $restaurant = Restaurant::factory()->create();
    $category = Category::factory()->create(['restaurant_id' => $restaurant->id]);
    $product = Product::factory()->create([
        'restaurant_id' => $restaurant->id,
        'category_id' => $category->id,
        'price' => 50,
    ]);
    $table = RestaurantTable::factory()->create(['restaurant_id' => $restaurant->id]);

    $this->withSession([
        'cart' => [$product->id => ['qty' => 2]],
        'restaurant_table_id' => $table->id,
        'restaurant_table_restaurant_id' => $restaurant->id,
    ])->post(route('checkout.store', $restaurant->slug), [
        'name' => 'عميل الطاولة',
        'phone' => '0999999999',
        'payment_method' => 'cash',
    ])->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'restaurant_id' => $restaurant->id,
        'restaurant_table_id' => $table->id,
        'delivery_type' => 'dine_in',
        'delivery_fee' => 0,
    ]);
});
