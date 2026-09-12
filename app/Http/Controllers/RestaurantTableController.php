<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestaurantTableController extends Controller
{
    public function enter(string $slug, string $token): RedirectResponse
    {
        $restaurant = Restaurant::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $table = $restaurant->tables()->where('qr_token', $token)->where('is_active', true)->firstOrFail();

        session([
            'restaurant_table_id' => $table->id,
            'restaurant_table_restaurant_id' => $restaurant->id,
        ]);

        return redirect()->route('restaurant.menu', $restaurant->slug);
    }

    public function qr(Request $request, RestaurantTable $table): View
    {
        abort_unless($request->user()?->restaurant_id === $table->restaurant_id, 403);

        return view('restaurant.table-qr', [
            'table' => $table->load('restaurant'),
            'url' => $table->qrUrl(),
        ]);
    }
}
