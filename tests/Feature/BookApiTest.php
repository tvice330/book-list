<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_books(): void
    {
        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/books');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_book(): void
    {
        $payload = [
            'title' => 'The Pragmatic Programmer',
            'publisher' => 'Addison-Wesley',
            'author' => 'Andrew Hunt',
            'genres' => ['software', 'engineering'],
            'published_at' => '1999-10-20',
            'word_count' => 100000,
            'price_usd' => 42.50,
        ];

        $response = $this->postJson('/api/books', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.title', 'The Pragmatic Programmer')
            ->assertJsonPath('data.price_usd', '42.50')
            ->assertJsonPath('data.published_at', '1999-10-20')
            ->assertJsonPath('data.genres.0', 'software');

        $this->assertDatabaseHas('books', [
            'title' => 'The Pragmatic Programmer',
            'publisher' => 'Addison-Wesley',
        ]);
    }

    public function test_validation_errors_on_create(): void
    {
        $response = $this->postJson('/api/books', []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'title',
                'publisher',
                'author',
                'genres',
                'published_at',
                'word_count',
                'price_usd',
            ]);
    }

    public function test_can_show_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson('/api/books/'.$book->id);

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $book->id);
    }

    public function test_can_patch_book(): void
    {
        $book = Book::factory()->create([
            'title' => 'Initial Title',
            'publisher' => 'Initial Publisher',
            'author' => 'Initial Author',
            'genres' => ['history', 'science'],
            'published_at' => '2001-02-03',
            'word_count' => 12345,
            'price_usd' => 10.00,
        ]);

        $response = $this->patchJson('/api/books/'.$book->id, ['title' => '  New Title  ']);

        $response
            ->assertOk()
            ->assertJsonPath('data.title', 'New Title')
            ->assertJsonPath('data.publisher', 'Initial Publisher')
            ->assertJsonPath('data.author', 'Initial Author')
            ->assertJsonPath('data.word_count', 12345)
            ->assertJsonPath('data.price_usd', '10.00');

        $book->refresh();

        $this->assertSame('New Title', $book->title);
        $this->assertSame('Initial Publisher', $book->publisher);
        $this->assertSame('Initial Author', $book->author);
        $this->assertSame(['history', 'science'], $book->genres);
        $this->assertSame('2001-02-03', $book->published_at?->format('Y-m-d'));
        $this->assertSame(12345, $book->word_count);
        $this->assertSame('10.00', $book->price_usd);
    }

    public function test_can_delete_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson('/api/books/'.$book->id);

        $response->assertNoContent();

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    public function test_show_returns_404_for_missing_book(): void
    {
        $this->getJson('/api/books/999999')
            ->assertNotFound();
    }

    public function test_patch_returns_404_for_missing_book(): void
    {
        $this->patchJson('/api/books/999999', ['title' => 'Any'])
            ->assertNotFound();
    }

    public function test_delete_returns_404_for_missing_book(): void
    {
        $this->deleteJson('/api/books/999999')
            ->assertNotFound();
    }

    public function test_genres_must_not_be_empty_after_validation(): void
    {
        $payload = [
            'title' => 'Book',
            'publisher' => 'Pub',
            'author' => 'Author',
            'genres' => [],
            'published_at' => '2024-01-01',
            'word_count' => 1000,
            'price_usd' => 10,
        ];

        $this->postJson('/api/books', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['genres']);
    }

    public function test_genres_are_normalized_trimmed_and_deduplicated(): void
    {
        $payload = [
            'title' => '  Normalized Title  ',
            'publisher' => '  Normalized Publisher ',
            'author' => '  Normalized Author ',
            'genres' => [' ', 'a', 'a'],
            'published_at' => ' 2024-01-01 ',
            'word_count' => 1000,
            'price_usd' => ' 19.90 ',
        ];

        $response = $this->postJson('/api/books', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.title', 'Normalized Title')
            ->assertJsonPath('data.publisher', 'Normalized Publisher')
            ->assertJsonPath('data.author', 'Normalized Author')
            ->assertJsonPath('data.published_at', '2024-01-01')
            ->assertJsonPath('data.price_usd', '19.90')
            ->assertJsonPath('data.genres', ['a']);
    }
}
