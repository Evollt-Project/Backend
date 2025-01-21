<?php

namespace Database\Factories;

use App\Models\Catalog;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'catalog_id' => $this->faker->numberBetween(1, count(Catalog::all())),
            'description' => $this->faker->text(),
        ];
    }
}
