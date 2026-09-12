<?php

namespace Database\Factories;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Theme> */
class ThemeFactory extends Factory
{
    protected $model = Theme::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);
        $slug = fake()->unique()->slug(2);

        return [
            'name' => $name,
            'slug' => $slug,
            'author' => fake()->name(),
            'version' => '1.0.0',
            'description' => fake()->sentence(),
            'folder_name' => $slug,
            'preview_image' => null,
            'default_settings' => ['primary_color' => '#FF6B35', 'secondary_color' => '#FFFFFF'],
            'allowed_variables' => ['primary_color', 'secondary_color'],
            'is_active' => true,
            'is_default' => false,
        ];
    }
}
