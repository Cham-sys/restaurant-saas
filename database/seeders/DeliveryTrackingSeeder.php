<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DeliveryTrackingSeeder extends Seeder
{
    public function run(): void
    {
        $theme = Theme::query()->updateOrCreate(
            ['slug' => 'burger-theme'],
            [
                'name' => 'قالب المطعم',
                'folder_name' => 'burger-theme',
                'version' => '1.0.0',
                'is_active' => true,
                'is_default' => true,
            ]
        );

        $restaurant = Restaurant::query()->updateOrCreate(
            ['slug' => 'sham-kitchen'],
            [
                'name' => 'مطبخ الشام',
                'subdomain' => 'sham-kitchen',
                'description' => 'مطعم سوري يقدم أطباق دمشقية منزلية.',
                'phone' => '0113334455',
                'email' => 'hello@sham-kitchen.test',
                'address' => 'المزة، دمشق، سوريا',
                'city' => 'دمشق',
                'delivery_fee' => 15000,
                'estimated_delivery_time' => 35,
                'theme_id' => $theme->id,
                'primary_color' => '#D97706',
                'secondary_color' => '#FFFFFF',
                'background_color' => '#FFF7ED',
                'is_active' => true,
                'trial_ends_at' => now()->addDays(30),
            ]
        );

        $driver = User::query()->updateOrCreate(
            ['email' => 'driver@syrian.test'],
            [
                'name' => 'أحمد الخطيب',
                'phone' => '0933123456',
                'role' => 'driver',
                'restaurant_id' => $restaurant->id,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $customer = User::query()->updateOrCreate(
            ['email' => 'customer@syrian.test'],
            [
                'name' => 'محمد الدمشقي',
                'phone' => '0944123456',
                'role' => 'customer',
                'restaurant_id' => null,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $category = Category::query()->updateOrCreate(
            ['restaurant_id' => $restaurant->id, 'slug' => 'shami-meals'],
            [
                'name' => 'وجبات شامية',
                'description' => 'أطباق سورية شهية.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $products = collect([
            ['slug' => 'chicken-shawarma', 'name' => 'شاورما دجاج', 'price' => 30000],
            ['slug' => 'kibbeh', 'name' => 'كبة مقلية', 'price' => 25000],
            ['slug' => 'tabbouleh', 'name' => 'تبولة شامية', 'price' => 18000],
        ])->map(function (array $data) use ($restaurant, $category): Product {
            return Product::query()->updateOrCreate(
                ['restaurant_id' => $restaurant->id, 'slug' => $data['slug']],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'description' => 'طبق طازج محضر يوميًا.',
                    'price' => $data['price'],
                    'is_available' => true,
                    'is_featured' => true,
                    'sort_order' => 1,
                ]
            );
        });

        $order = Order::query()->updateOrCreate(
            ['tracking_code' => 'ORD-SHAM-1001'],
            [
                'restaurant_id' => $restaurant->id,
                'user_id' => $customer->id,
                'driver_id' => $driver->id,
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'customer_email' => $customer->email,
                'delivery_type' => 'delivery',
                'delivery_address' => 'شارع أبو رمانة، بناء 12، الطابق الثاني، دمشق',
                'delivery_city' => 'دمشق',
                'delivery_latitude' => 33.5138,
                'delivery_longitude' => 36.2765,
                'driver_latitude' => 33.5089,
                'driver_longitude' => 36.2852,
                'driver_location_updated_at' => now()->subSeconds(20),
                'subtotal' => 103000,
                'delivery_fee' => 15000,
                'total_amount' => 103000,
                'final_amount' => 118000,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'notes' => 'الاتصال قبل الوصول، المدخل بجانب الصيدلية.',
                'status' => 'on_way',
            ]
        );

        foreach ($products as $index => $product) {
            $quantity = $index === 0 ? 2 : 1;
            Order_item::query()->updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $product->id],
                [
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total' => $product->price * $quantity,
                ]
            );
        }

        $this->command?->newLine();
        $this->command?->info('بيانات اختبار التوصيل السوري');
        $this->command?->line('المطعم: مطبخ الشام');
        $this->command?->line('المدينة: دمشق - سوريا');
        $this->command?->line('العنوان: المزة، دمشق، سوريا');
        $this->command?->line('الهاتف: 0113334455');
        $this->command?->line('Slug المطعم: sham-kitchen');
        $this->command?->newLine();
        $this->command?->line('حساب المندوب');
        $this->command?->line('  Email: driver@syrian.test');
        $this->command?->line('  Password: password');
        $this->command?->line('  Phone: 0933123456');
        $this->command?->line('  Role: driver');
        $this->command?->newLine();
        $this->command?->line('حساب العميل');
        $this->command?->line('  Email: customer@syrian.test');
        $this->command?->line('  Password: password');
        $this->command?->line('  Phone: 0944123456');
        $this->command?->line('  Role: customer');
        $this->command?->newLine();
        $this->command?->line('بيانات الطلب');
        $this->command?->line('  Tracking code: ORD-SHAM-1001');
        $this->command?->line('  Status: on_way');
        $this->command?->line('  Payment: cash / pending');
        $this->command?->line('  Items: '.$products->pluck('name')->implode('، '));
        $this->command?->line('  Customer location: 33.5138, 36.2765');
        $this->command?->line('  Driver location: 33.5089, 36.2852');
        $this->command?->newLine();
        $this->command?->line('صفحة المندوب: /driver/orders');
        $this->command?->line('صفحة التتبع: /driver/orders/'.$order->id.'/tracking');
        $this->command?->line('API البيانات: GET /driver/orders/'.$order->id);
        $this->command?->line('API الموقع: POST /driver/orders/'.$order->id.'/location');
        $this->command?->line('API الحالة: POST /driver/orders/'.$order->id.'/status');
    }
}
