<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Category> */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'restaurant_id' => Restaurant::factory(),
            'name' => $name,
            'slug' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'image' => null,
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
