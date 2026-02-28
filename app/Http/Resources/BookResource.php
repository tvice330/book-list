<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'publisher' => $this->publisher,
            'author' => $this->author,
            'genres' => $this->genres,
            'published_at' => $this->published_at?->format('Y-m-d'),
            'word_count' => $this->word_count,
            'price_usd' => $this->price_usd,
        ];
    }
}
