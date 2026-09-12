<?php

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;

it('allows an assigned driver to update a delivery location', function () {
    $restaurant = Restaurant::factory()->create();
    $driver = User::factory()->create([
        'role' => 'driver',
        'restaurant_id' => $restaurant->id,
    ]);
    $order = Order::factory()->create([
        'restaurant_id' => $restaurant->id,
        'driver_id' => $driver->id,
        'status' => 'ready',
        'delivery_latitude' => 33.5,
        'delivery_longitude' => 36.2,
    ]);

    $this->actingAs($driver)
        ->postJson(route('driver.location.update', $order), [
            'latitude' => 33.51,
            'longitude' => 36.21,
        ])
        ->assertSuccessful()
        ->assertJsonPath('success', true);

    expect($order->fresh())
        ->driver_latitude->toBe('33.5100000')
        ->driver_longitude->toBe('36.2100000')
        ->status->toBe('on_way');
});

it('does not allow a driver to update another restaurant order', function () {
    $restaurant = Restaurant::factory()->create();
    $otherRestaurant = Restaurant::factory()->create();
    $driver = User::factory()->create([
        'role' => 'driver',
        'restaurant_id' => $restaurant->id,
    ]);
    $order = Order::factory()->create([
        'restaurant_id' => $otherRestaurant->id,
        'driver_id' => $driver->id,
        'status' => 'ready',
    ]);

    $this->actingAs($driver)
        ->postJson(route('driver.location.update', $order), [
            'latitude' => 33.51,
            'longitude' => 36.21,
        ])
        ->assertForbidden();
});
