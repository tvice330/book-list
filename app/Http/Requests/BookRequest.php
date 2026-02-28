<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function baseRules(): array
    {
        return [
            'title' => ['string', 'max:255'],
            'publisher' => ['string', 'max:255'],
            'author' => ['string', 'max:255'],
            'genres' => ['array', 'min:1'],
            'genres.*' => ['required', 'string', 'max:100'],
            'published_at' => ['date'],
            'word_count' => ['integer', 'min:0'],
            'price_usd' => ['numeric', 'min:0'],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function rulesWithPresence(string $presenceRule): array
    {
        $rules = [];

        foreach ($this->baseRules() as $field => $fieldRules) {
            if (str_contains($field, '.*')) {
                $rules[$field] = $fieldRules;
                continue;
            }

            $rules[$field] = [$presenceRule, ...$fieldRules];
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $normalized = [];

        foreach (['title', 'publisher', 'author', 'price_usd', 'published_at'] as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $normalized[$field] = trim($this->input($field));
            }
        }

        if ($this->has('genres') && is_array($this->input('genres'))) {
            $genres = [];

            foreach ($this->input('genres') as $genre) {
                $value = $genre;

                if (is_string($genre)) {
                    $value = trim($genre);

                    if ($value === '') {
                        continue;
                    }
                } elseif ($genre === null) {
                    continue;
                }

                if (!in_array($value, $genres, true)) {
                    $genres[] = $value;
                }
            }

            $normalized['genres'] = array_values($genres);
        }

        if ($normalized !== []) {
            $this->replace([
                ...$this->all(),
                ...$normalized,
            ]);
        }
    }
}
