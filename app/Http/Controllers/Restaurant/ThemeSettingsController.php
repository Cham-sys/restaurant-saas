<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\RestaurantThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeSettingsController extends Controller
{
    /**
     * جلب إعدادات الثيم الحالية للمطعم
     */
    public function getSettings()
    {
        $restaurant = Auth::user()?->restaurant()->with('theme')->first();

        if (! $restaurant || ! $restaurant->theme) {
            return response()->json(['error' => 'لم يتم العثور على مطعم أو ثيم'], 404);
        }

        $defaults = $restaurant->theme->default_settings ?? [];
        $customSettings = $restaurant->themeSettings?->settings ?? [];

        return response()->json([
            'success' => true,
            'settings' => array_replace($defaults, $customSettings),
            'allowed_variables' => $restaurant->theme->allowed_variables ?? [],
        ]);
    }

    /**
     * حفظ إعدادات الثيم الجديدة
     */
    public function updateSettings(Request $request)
    {
        $restaurant = Auth::user()?->restaurant()->with('theme')->first();

        if (! $restaurant || ! $restaurant->theme) {
            return response()->json(['error' => 'لم يتم العثور على مطعم أو ثيم'], 404);
        }

        $request->validate([
            'settings' => 'required|array',
        ]);

        $allowedVariables = $restaurant->theme->allowed_variables ?? [];
        $allowedKeys = array_keys($allowedVariables);
        $submittedSettings = $request->input('settings', []);
        $filteredSettings = [];

        foreach ($allowedKeys as $key) {
            if (array_key_exists($key, $submittedSettings)) {
                $filteredSettings[$key] = $submittedSettings[$key];
            }
        }

        $mergedSettings = array_replace(
            $restaurant->theme->default_settings ?? [],
            $restaurant->themeSettings?->settings ?? [],
            $filteredSettings
        );

        $themeSetting = RestaurantThemeSetting::updateOrCreate(
            ['restaurant_id' => $restaurant->id],
            [
                'theme_id' => $restaurant->theme_id,
                'settings' => $mergedSettings,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ إعدادات المظهر بنجاح ✓',
            'settings' => $themeSetting->settings,
        ]);
    }
}
