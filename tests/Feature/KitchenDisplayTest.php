<?php

use App\Models\Order;
use App\Models\Order_item;
use App\Models\Restaurant;
use App\Models\User;

it('renders kitchen orders with their item details', function () {
    $restaurant = Restaurant::factory()->create(['slug' => 'burger-house']);
    $user = User::factory()->create(['restaurant_id' => $restaurant->id]);
    $order = Order::factory()->create([
        'restaurant_id' => $restaurant->id,
        'status' => 'pending',
        'notes' => 'بدون بصل',
    ]);
    Order_item::factory()->create([
        'order_id' => $order->id,
        'product_name' => 'برجر دجاج',
        'quantity' => 2,
    ]);

    $this->actingAs($user)
        ->get(route('kitchen.display', $restaurant->slug))
        ->assertSuccessful()
        ->assertSee('برجر دجاج')
        ->assertSee('بدون بصل')
        ->assertSee($order->tracking_code);

    $this->actingAs($user)
        ->getJson(route('kitchen.orders.index', $restaurant->slug))
        ->assertSuccessful()
        ->assertJsonPath('orders.0.id', $order->id)
        ->assertJsonPath('orders.0.items.0.name', 'برجر دجاج')
        ->assertJsonPath('orders.0.items.0.qty', 2);
});

it('updates a kitchen order only through its restaurant route', function () {
    $restaurant = Restaurant::factory()->create(['slug' => 'burger-house']);
    $otherRestaurant = Restaurant::factory()->create(['slug' => 'other-house']);
    $user = User::factory()->create(['restaurant_id' => $restaurant->id]);
    $order = Order::factory()->create(['restaurant_id' => $restaurant->id, 'status' => 'pending']);

    $this->actingAs($user)
        ->patchJson(route('kitchen.orders.update', ['slug' => $restaurant->slug, 'order' => $order]), ['status' => 'preparing'])
        ->assertSuccessful()
        ->assertJsonPath('success', true);

    expect($order->fresh()->status)->toBe('preparing');

    $this->actingAs($user)
        ->patchJson(route('kitchen.orders.update', ['slug' => $otherRestaurant->slug, 'order' => $order]), ['status' => 'ready'])
        ->assertNotFound();
});