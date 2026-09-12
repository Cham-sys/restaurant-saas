<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Image> */
class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'imageable_type' => Product::class,
            'imageable_id' => Product::factory(),
            'path' => 'products/'.fake()->uuid().'.jpg',
            'alt' => fake()->sentence(3),
            'sort_order' => 0,
            'is_primary' => true,
        ];
    }
}
