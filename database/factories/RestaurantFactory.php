<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Restaurant> */
class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'slug' => fake()->unique()->slug(),
            'subdomain' => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'delivery_fee' => 10,
            'estimated_delivery_time' => 35,
            'theme_id' => Theme::factory(),
            'primary_color' => '#FF6B35',
            'secondary_color' => '#FFFFFF',
            'background_color' => '#1A1A1A',
            'pricing_type' => 'commission',
            'commission_rate' => 5,
            'subscription_fee' => 0,
            'is_active' => true,
            'trial_ends_at' => now()->addDays(14),
        ];
    }
}
