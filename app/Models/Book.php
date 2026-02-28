<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'publisher',
        'author',
        'genres',
        'published_at',
        'word_count',
        'price_usd',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'genres' => 'array',
            'published_at' => 'date:Y-m-d',
            'price_usd' => 'decimal:2',
        ];
    }
}
