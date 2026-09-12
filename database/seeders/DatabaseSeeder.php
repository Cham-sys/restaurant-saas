<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\RestaurantThemeSetting;
use App\Models\Review;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $theme = Theme::factory()->create([
            'name' => 'برجر كلاسيك',
            'slug' => 'burger-theme',
            'folder_name' => 'burger-theme',
            'is_default' => true,
        ]);

        $restaurant = Restaurant::factory()->create([
            'name' => 'برجر هاوس',
            'slug' => 'burger-house',
            'subdomain' => 'burger-house',
            'theme_id' => $theme->id,
        ]);

        User::factory()->create([
            'name' => 'مدير المطعم',
            'email' => 'admin@example.com',
            'role' => 'restaurant_admin',
            'restaurant_id' => $restaurant->id,
        ]);
        $customer = User::factory()->create([
            'name' => 'عميل تجريبي',
            'email' => 'customer@example.com',
            'role' => 'customer',
        ]);

        RestaurantThemeSetting::factory()->create([
            'restaurant_id' => $restaurant->id,
            'theme_id' => $theme->id,
        ]);

        $categories = Category::factory(2)->create(['restaurant_id' => $restaurant->id]);
        $products = Product::factory(3)->create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $categories->first()->id,
        ]);

        $offer = Offer::factory()->create(['restaurant_id' => $restaurant->id]);
        $offer->products()->attach($products->take(2)->modelKeys());
        $coupon = Coupon::factory()->create([
            'restaurant_id' => $restaurant->id,
            'code' => 'WELCOME20',
        ]);

        $order = Order::factory()->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_phone' => '0500000002',
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
            'status' => 'delivered',
            'payment_status' => 'verified',
        ]);

        foreach ($products->take(2) as $product) {
            Order_item::factory()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'total' => $product->price,
            ]);
        }

        Invoice::factory()->create([
            'order_id' => $order->id,
            'restaurant_id' => $restaurant->id,
            'status' => 'paid',
            'paid_at' => now(),
        ]);
        Review::factory()->create([
            'order_id' => $order->id,
            'restaurant_id' => $restaurant->id,
            'customer_name' => $customer->name,
            'rating' => 5,
        ]);

        User::factory()->create([
            'name' => 'مدير النظام',
            'email' => 'super@example.com',
            'role' => 'super_admin',
        ]);
    }
}
