<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $availableGenres = [
            'fiction',
            'non-fiction',
            'mystery',
            'fantasy',
            'science',
            'history',
            'biography',
            'romance',
        ];

        return [
            'title' => fake()->sentence(3),
            'publisher' => fake()->company(),
            'author' => fake()->name(),
            'genres' => fake()->randomElements($availableGenres, fake()->numberBetween(1, 3)),
            'published_at' => fake()->date(),
            'word_count' => fake()->numberBetween(10_000, 300_000),
            'price_usd' => fake()->randomFloat(2, 0, 200),
        ];
    }
}
