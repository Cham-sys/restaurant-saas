<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function index(Request $request): View
    {
        $driver = $request->user();

        abort_unless($driver->role === 'driver', 403);

        $orders = Order::query()
            ->where('driver_id', $driver->id)
            ->whereIn('status', ['ready', 'on_way'])
            ->with('restaurant')
            ->latest()
            ->get();

        return view('driver.orders', compact('orders'));
    }

    public function updateLocation(Request $request, Order $order): JsonResponse
    {
        $driver = $request->user();

        abort_unless(
            $driver->role === 'driver'
                && $order->driver_id === $driver->id
                && $order->restaurant_id === $driver->restaurant_id,
            403
        );

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
        ]);
    }
}
