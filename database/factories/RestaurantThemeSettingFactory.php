<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\RestaurantThemeSetting;
use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<RestaurantThemeSetting> */
class RestaurantThemeSettingFactory extends Factory
{
    protected $model = RestaurantThemeSetting::class;

    public function definition(): array
    {
        return [
            'restaurant_id' => Restaurant::factory(),
            'theme_id' => Theme::factory(),
            'settings' => ['primary_color' => '#E85D04'],
        ];
    }
}
