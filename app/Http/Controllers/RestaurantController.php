<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusChanged;
use App\Helpers\ThemeHelper;
use App\Models\Order;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للمطعم
     */
    public function index()
    {
        $restaurant = auth()->user()->restaurant;

        // جلب الإعدادات من قاعدة البيانات، أو استخدام القيم الافتراضية
        $settings = $restaurant->themeSettings?->settings ?? $restaurant->theme?->default_settings ?? [];

        return view('restaurant.dashboard', compact('settings'));
    }

    public function kds(string $slug)
    {
        $restaurant = Restaurant::with(['theme', 'categories', 'activeOffers'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $orders = Order::with('items')
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->where('restaurant_id', $restaurant->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $themePath = ThemeHelper::getThemePath($restaurant);
        $initialOrders = $orders->map(function ($order) {

            $jsStatus = 'new';
            if ($order->status === 'preparing') {
                $jsStatus = 'preparing';
            } elseif ($order->status === 'ready') {
                $jsStatus = 'ready';
            }

            return [
                'id' => $order->tracking_code ?? $order->id,
                'type' => $order->delivery_type, 
                'table_number' => null, // يمكن إضافته لاحقاً إذا أضفت عمود table_id للجدول
                'customer_name' => $order->customer_name,
                'driver_name' => null, // يمكن ربطه لاحقاً إذا كان هناك علاقة مع driver
                'status' => $jsStatus, // الحالة المحوّلة
                'created_at' => $order->created_at->toIso8601String(), // تنسيق متوافق مع JS Date
                'items' => $order->items->map(function ($item) {
                    return [
                        // غيّر 'name' و 'quantity' حسب أسماء الأعمدة الفعلية في جدول order_items
                        'name' => $item->name ?? 'صنف غير معروف',
                        'qty' => $item->quantity ?? $item->qty ?? 1,
                        'notes' => $item->notes ?? ($order->notes ?? '') // ملاحظات الصنف أو ملاحظات الطلب العامة
                    ];
                })->toArray()
            ];
        });
        return view("themes.{$themePath}.css.kds", compact('restaurant', 'initialOrders'));
    }
    public function updateStatusKds(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:new,preparing,ready,completed,pending'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // إذا اكتمل الطلب، نحدث وقت الإنهاء
        if ($request->status === 'completed') {
            $order->update(['completed_at' => now()]);
        }

        // إرسال حدث Real-time لتحديث الشاشات الأخرى فوراً
        broadcast(new OrderStatusChanged($order, $oldStatus))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الحالة بنجاح',
            'order' => $order->load('items')
        ]);
    }
    public function home(string $slug)
    {
        // تحميل المطعم مع الثيم
        $restaurant = Restaurant::with(['theme', 'categories', 'activeOffers'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $categories = $restaurant->categories()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        // تحديد مسار الثيم (سنستخدم burger-theme كافتراضي حالياً)
        $themePath = ThemeHelper::getThemePath($restaurant);

        return view("themes.{$themePath}.pages.home", compact('restaurant', 'categories'));
    }

    public function showProduct($slug, $productId)
    {
        $restaurant = Restaurant::with('theme')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $product = Product::where('id', $productId)
            ->where('restaurant_id', $restaurant->id)
            ->where('is_available', true)
            ->firstOrFail();

        // جلب التقييمات المعتمدة للمنتج
        $reviews = Review::whereHas('order', function ($query) use ($productId) {
            $query->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            });
        })
            ->with('images')
            ->latest()
            ->paginate(10);

        $themePath = ThemeHelper::getThemePath($restaurant);

        return view("themes.{$themePath}.pages.product", compact('restaurant', 'product', 'reviews'));
    }

    /**
     * عرض قائمة الطعام (سنضيفها في المرحلة 4.2)
     */
    public function menu($slug)
    {
        $restaurant = Restaurant::with('theme')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // تحميل التصنيفات النشطة مع منتجاتها المتاحة
        $categories = $restaurant->categories()
            ->where('is_active', true)
            ->with(['products' => function ($query) {
                $query->where('is_available', true)
                    ->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $themePath = ThemeHelper::getThemePath($restaurant);

        $table = session('restaurant_table_restaurant_id') === $restaurant->id
            ? $restaurant->tables()->find(session('restaurant_table_id'))
            : null;

        return view("themes.{$themePath}.pages.menu", compact('restaurant', 'categories', 'table'));
    }
}
