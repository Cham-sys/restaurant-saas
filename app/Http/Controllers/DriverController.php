<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function dashboard(Request $request): View
    {
        $driver = $request->user();

        if ($driver?->role !== 'driver') {
            return view('dashboard');
        }

        $activeOrders = Order::query()
            ->where('driver_id', $driver->id)
            ->whereIn('status', ['ready', 'on_way'])
            ->with('restaurant')
            ->latest()
            ->get();

        $todayOrders = Order::query()
            ->where('driver_id', $driver->id)
            ->whereDate('created_at', today());

        $stats = [
            'active' => $activeOrders->count(),
            'deliveredToday' => (clone $todayOrders)->where('status', 'delivered')->count(),
            'totalToday' => $todayOrders->count(),
        ];

        $recentOrders = Order::query()
            ->where('driver_id', $driver->id)
            ->where('status', 'delivered')
            ->with('restaurant')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('driver.dashboard', compact('driver', 'activeOrders', 'recentOrders', 'stats'));
    }

    public function index(Request $request): View
    {
        $driver = $request->user();

        abort_unless($driver->role === 'driver', 403);

        $orders = Order::query()
            ->where('driver_id', $driver->id)
            ->whereIn('status', ['ready', 'on_way'])
            ->with(['restaurant', 'items.product'])
            ->latest()
            ->get();

        return view('driver.orders', compact('orders'));
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        $this->authorizeDriverOrder($request, $order);

        return response()->json($this->orderPayload($order->loadMissing(['restaurant', 'items.product'])));
    }

    public function tracking(Request $request, Order $order): View
    {
        $this->authorizeDriverOrder($request, $order);

        $order->load(['restaurant', 'driver', 'items.product']);

        return view('driver.tracking', compact('order'));
    }

    public function updateLocation(Request $request, Order $order): JsonResponse
    {
        $this->authorizeDriverOrder($request, $order);

        abort_unless(in_array($order->status, ['ready', 'on_way'], true), 422, 'تم إغلاق تتبع هذا الطلب.');

        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $order->forceFill([
            'driver_latitude' => $validated['latitude'],
            'driver_longitude' => $validated['longitude'],
            'driver_location_updated_at' => now(),
            'status' => $order->status === 'ready' ? 'on_way' : $order->status,
        ])->save();

        return response()->json([
            'success' => true,
            'updated_at' => $order->driver_location_updated_at?->toIso8601String(),
            'status' => $order->status,
        ]);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $this->authorizeDriverOrder($request, $order);

        $validated = $request->validate([
            'status' => ['required', 'in:on_way,delivered'],
        ]);

        abort_unless(
            ($validated['status'] === 'on_way' && $order->status === 'ready')
                || ($validated['status'] === 'delivered' && $order->status === 'on_way'),
            422,
            'لا يمكن تغيير حالة الطلب من الحالة الحالية.'
        );

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'status' => $order->status,
        ]);
    }

    private function authorizeDriverOrder(Request $request, Order $order): void
    {
        $driver = $request->user();

        abort_unless(
            $driver?->role === 'driver'
                && $order->driver_id === $driver->id
                && $order->restaurant_id === $driver->restaurant_id,
            403
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function orderPayload(Order $order): array
    {
        return [
            'id' => $order->id,
            'tracking_code' => $order->tracking_code,
            'status' => $order->status,
            'customer' => [
                'name' => $order->customer_name,
                'phone' => $order->customer_phone,
                'address' => $order->delivery_address,
                'latitude' => $order->delivery_latitude,
                'longitude' => $order->delivery_longitude,
            ],
            'driver' => [
                'latitude' => $order->driver_latitude,
                'longitude' => $order->driver_longitude,
                'updated_at' => $order->driver_location_updated_at?->toIso8601String(),
            ],
            'restaurant' => [
                'name' => $order->restaurant?->name,
            ],
            'items' => $order->items->map(fn ($item): array => [
                'name' => $item->product?->name ?? 'منتج محذوف',
                'quantity' => $item->quantity,
            ])->values()->all(),
        ];
    }
}
