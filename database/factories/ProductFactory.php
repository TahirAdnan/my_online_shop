<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->name();
        $slug = str::slug($title);
        
        $subCategories = [20,21];
        $subCategoriesRandKey = array_rand($subCategories);

        $brands = [1,2];
        $brandsRandKey = array_rand($brands);
        return [
            'title' => $title,
            'slug' => $slug,
            "category_id" => 11,
            'sub_category_id' => $subCategories[$subCategoriesRandKey],
            'brand_id' => $brands[$brandsRandKey],
            'price' => rand(0,1000),
            'sku' => rand(1000,1000000),
            'track_qty' => 'Yes',
            'qty' => 10,
            'is_featured' => 'Yes',
            'status' => 1
        ];
    }
}
