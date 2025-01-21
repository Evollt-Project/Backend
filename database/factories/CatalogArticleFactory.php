<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Catalog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CatalogArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'catalog_id' => $this->faker->numberBetween(1, count(Catalog::all())),
            'article_id' => $this->faker->numberBetween(1, count(Article::all())),
        ];
    }
}
