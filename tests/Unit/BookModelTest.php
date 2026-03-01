<?php

namespace Tests\Unit;

use App\Models\Book;
use Carbon\CarbonInterface;
use PHPUnit\Framework\TestCase;

class BookModelTest extends TestCase
{
    public function test_book_casts_genres_published_at_and_price_usd(): void
    {
        $book = new Book([
            'title' => 'Domain-Driven Design',
            'publisher' => 'Addison-Wesley',
            'author' => 'Eric Evans',
            'genres' => ['software', 'architecture'],
            'published_at' => '2003-08-30',
            'word_count' => 1000,
            'price_usd' => '19.9',
        ]);

        $this->assertIsArray($book->genres);
        $this->assertSame(['software', 'architecture'], $book->genres);

        $this->assertInstanceOf(CarbonInterface::class, $book->published_at);
        $this->assertSame('2003-08-30', $book->published_at->format('Y-m-d'));

        $this->assertIsString($book->price_usd);
        $this->assertSame('19.90', $book->price_usd);
    }
}
