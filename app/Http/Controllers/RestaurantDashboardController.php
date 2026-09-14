<?php

namespace App\Http\Controllers;

use App\Services\RestaurantDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RestaurantDashboardController extends Controller
{
    public function summary(RestaurantDashboardService $dashboardService): JsonResponse
    {
        return response()->json($dashboardService->forRestaurant(Auth::user()?->restaurant));
    }
}
