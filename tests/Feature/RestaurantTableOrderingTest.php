<?php

use App\Events\OrderCreated;
use App\Http\Resources\KdsOrderResource;
use App\Models\Category;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\Event;

it('opens a table QR link and keeps the table in the customer session', function () {
    $restaurant = Restaurant::factory()->create();
    $table = RestaurantTable::factory()->create(['restaurant_id' => $restaurant->id]);

    $response = $this->get(route('restaurant.table.menu', [$restaurant->slug, $table->qr_token]));

    $response->assertRedirect(route('restaurant.menu', $restaurant->slug));

    $this->get(route('restaurant.menu', $restaurant->slug))
        ->assertOk()
        ->assertSee('الطاولة '.$table->number);

    expect(session('restaurant_table_id'))->toBe($table->id);
});

it('builds table QR links from the configured public application URL', function () {
    config(['app.url' => 'https://menu.example.test']);

    $restaurant = Restaurant::factory()->create();
    $table = RestaurantTable::factory()->create(['restaurant_id' => $restaurant->id]);

    expect($table->qrUrl())->toStartWith('https://menu.example.test/')
        ->and($table->qrUrl())->toContain('/'.$restaurant->slug.'/table/'.$table->qr_token);
});

it('shows the current cart items and prices in the table menu summary', function () {
    $restaurant = Restaurant::factory()->create();
    $category = Category::factory()->create(['restaurant_id' => $restaurant->id]);
    $product = Product::factory()->create([
        'restaurant_id' => $restaurant->id,
        'category_id' => $category->id,
        'name' => 'وجبة الاختبار',
        'price' => 50,
    ]);
    $table = RestaurantTable::factory()->create(['restaurant_id' => $restaurant->id]);

    $response = $this->withSession([
        'cart_'.$restaurant->id => [$product->id => ['qty' => 2]],
        'restaurant_table_id' => $table->id,
        'restaurant_table_restaurant_id' => $restaurant->id,
    ])->get(route('restaurant.menu', $restaurant->slug))
        ->assertOk()
        ->assertSee('وجبة الاختبار')
        ->assertSee('2 × 50.00')
        ->assertSee('115.00')
        ->assertSee('mini-cart-items')
        ->assertSee('2 عناصر');
});

it('returns updated cart items and totals when a menu item is added asynchronously', function () {
    $restaurant = Restaurant::factory()->create();
    $category = Category::factory()->create(['restaurant_id' => $restaurant->id]);
    $product = Product::factory()->create([
        'restaurant_id' => $restaurant->id,
        'category_id' => $category->id,
        'name' => 'وجبة AJAX',
        'price' => 40,
    ]);

    $this->postJson(route('cart.add', $restaurant->slug), [
        'product_id' => $product->id,
        'quantity' => 2,
    ])->assertOk()
        ->assertJsonPath('count', 2)
        ->assertJsonPath('total', 80)
        ->assertJsonPath('grand_total', 92)
        ->assertJsonPath('items.0.name', 'وجبة AJAX')
        ->assertJsonPath('items.0.quantity', 2)
        ->assertJsonPath('items.0.subtotal', 80);
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

    Event::fake();

    $response = $this->withSession([
        'cart_'.$restaurant->id => [$product->id => ['qty' => 2]],
        'restaurant_table_id' => $table->id,
        'restaurant_table_restaurant_id' => $restaurant->id,
    ])->post(route('checkout.store', $restaurant->slug), [
        'name' => 'عميل الطاولة',
        'phone' => '0999999999',
        'payment_method' => 'cash',
    ]);

    $order = Order::query()->latest('id')->firstOrFail();

    $response->assertRedirect(route('order.success', [$restaurant->slug, $order->tracking_code]));

    $this->get(route('order.success', [$restaurant->slug, $order->tracking_code]))
        ->assertOk()
        ->assertSee('عرض الفاتورة')
        ->assertSee('تتبع الطلب')
        ->assertSee('العودة إلى منيو الطاولة')
        ->assertSee('طلب الطاولة')
        ->assertSee($table->number);

    $this->assertDatabaseHas('orders', [
        'restaurant_id' => $restaurant->id,
        'restaurant_table_id' => $table->id,
        'delivery_type' => 'dine_in',
        'delivery_fee' => 0,
    ]);

    Event::assertDispatched(OrderCreated::class);
});

it('does not require a phone number for a table order', function () {
    $restaurant = Restaurant::factory()->create();
    $category = Category::factory()->create(['restaurant_id' => $restaurant->id]);
    $product = Product::factory()->create(['restaurant_id' => $restaurant->id, 'category_id' => $category->id]);
    $table = RestaurantTable::factory()->create(['restaurant_id' => $restaurant->id]);

    Event::fake();

    $this->withSession([
        'cart_'.$restaurant->id => [$product->id => ['qty' => 1]],
        'restaurant_table_id' => $table->id,
        'restaurant_table_restaurant_id' => $restaurant->id,
    ])->post(route('checkout.store', $restaurant->slug), [
        'name' => 'عميل الطاولة',
        'payment_method' => 'cash',
    ])->assertRedirect();

    $this->assertDatabaseHas('orders', ['restaurant_table_id' => $table->id, 'customer_phone' => null]);
});

it('serializes the table number and product name for the kitchen display', function () {
    $restaurant = Restaurant::factory()->create();
    $table = RestaurantTable::factory()->create([
        'restaurant_id' => $restaurant->id,
        'number' => 'A12',
    ]);
    $order = Order::factory()->create([
        'restaurant_id' => $restaurant->id,
        'restaurant_table_id' => $table->id,
        'delivery_type' => 'dine_in',
    ]);
    Order_item::factory()->create([
        'order_id' => $order->id,
        'product_name' => 'برجر خاص',
    ]);

    $payload = (new KdsOrderResource($order->load(['items', 'restaurantTable'])))->resolve();

    expect($payload['table_number'])->toBe('A12')
        ->and($payload['type'])->toBe('dine-in')
        ->and($payload['items']->first()['name'])->toBe('برجر خاص');
});

it('broadcasts an order only on its restaurant KDS channel', function () {
    $restaurant = Restaurant::factory()->create();
    $otherRestaurant = Restaurant::factory()->create();
    $order = Order::factory()->create(['restaurant_id' => $restaurant->id]);

    $channel = (new OrderCreated($order))->broadcastOn()[0];

    expect($channel->name)->toBe('private-restaurant.'.$restaurant->id.'.kds')
        ->and($channel->name)->not->toContain((string) $otherRestaurant->id);
});
