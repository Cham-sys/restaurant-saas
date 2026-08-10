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
        $restaurant = Auth::user()->restaurant; // افتراض أن المستخدم الحالي هو صاحب المطعم
        
        if (!$restaurant || !$restaurant->theme) {
            return response()->json(['error' => 'لم يتم العثور على مطعم أو ثيم'], 404);
        }

        // جلب الإعدادات المخصصة، أو العودة للإعدادات الافتراضية للثيم
        $settings = $restaurant->themeSettings 
            ? $restaurant->themeSettings->settings 
            : $restaurant->theme->default_settings;

        return response()->json([
            'success' => true,
            'settings' => $settings,
            'allowed_variables' => $restaurant->theme->allowed_variables
        ]);
    }

    /**
     * حفظ إعدادات الثيم الجديدة
     */
    public function updateSettings(Request $request)
    {
        $restaurant = Auth::user()->restaurant;

        $request->validate([
            'settings' => 'required|array',
        ]);

        // تحديث أو إنشاء سجل الإعدادات
        $themeSetting = RestaurantThemeSetting::updateOrCreate(
            ['restaurant_id' => $restaurant->id],
            [
                'theme_id' => $restaurant->theme_id,
                'settings' => $request->settings,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ إعدادات المظهر بنجاح ✓',
            'settings' => $themeSetting->settings
        ]);
    }
}