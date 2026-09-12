<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'restaurant_id' => Restaurant::factory(),
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(3),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 10, 100),
            'old_price' => null,
            'image' => null,
            'is_available' => true,
            'is_featured' => fake()->boolean(25),
            'sort_order' => fake()->numberBetween(1, 20),
        ];
    }
}
