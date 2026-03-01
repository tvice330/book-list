<?php

namespace Tests\Unit;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class BookResourceTest extends TestCase
{
    public function test_book_resource_formats_output_fields(): void
    {
        $book = new Book([
            'id' => 7,
            'title' => 'Clean Code',
            'publisher' => 'Prentice Hall',
            'author' => 'Robert C. Martin',
            'genres' => ['software', 'craftsmanship'],
            'published_at' => '2008-08-01',
            'word_count' => 1200,
            'price_usd' => '32',
        ]);

        $resource = new BookResource($book);
        $payload = $resource->toArray(Request::create('/api/books/7', 'GET'));

        $this->assertSame('2008-08-01', $payload['published_at']);
        $this->assertIsArray($payload['genres']);
        $this->assertSame(['software', 'craftsmanship'], $payload['genres']);
        $this->assertIsString($payload['price_usd']);
        $this->assertSame('32.00', $payload['price_usd']);
    }
}
