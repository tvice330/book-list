<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="Book List API",
 *         version="1.0.0"
 *     ),
 *     @OA\Server(
 *         url="http://localhost:8000",
 *         description="Local"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Books",
 *     description="Book CRUD"
 * )
 *
 * @OA\Schema(
 *     schema="Book",
 *     type="object",
 *     required={"id", "title", "publisher", "author", "genres", "published_at", "word_count", "price_usd"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", maxLength=255, example="Clean Code"),
 *     @OA\Property(property="publisher", type="string", maxLength=255, example="Prentice Hall"),
 *     @OA\Property(property="author", type="string", maxLength=255, example="Robert C. Martin"),
 *     @OA\Property(
 *         property="genres",
 *         type="array",
 *         minItems=1,
 *         @OA\Items(type="string", maxLength=100, example="software")
 *     ),
 *     @OA\Property(property="published_at", type="string", format="date", example="2008-08-01"),
 *     @OA\Property(property="word_count", type="integer", minimum=0, example=145000),
 *     @OA\Property(property="price_usd", type="string", example="39.99")
 * )
 *
 * @OA\Schema(
 *     schema="BookCreate",
 *     type="object",
 *     required={"title", "publisher", "author", "genres", "published_at", "word_count", "price_usd"},
 *     @OA\Property(property="title", type="string", maxLength=255),
 *     @OA\Property(property="publisher", type="string", maxLength=255),
 *     @OA\Property(property="author", type="string", maxLength=255),
 *     @OA\Property(
 *         property="genres",
 *         type="array",
 *         minItems=1,
 *         @OA\Items(type="string", maxLength=100)
 *     ),
 *     @OA\Property(property="published_at", type="string", format="date"),
 *     @OA\Property(property="word_count", type="integer", minimum=0),
 *     @OA\Property(property="price_usd", type="number", format="float", minimum=0)
 * )
 *
 * @OA\Schema(
 *     schema="BookUpdate",
 *     type="object",
 *     @OA\Property(property="title", type="string", maxLength=255),
 *     @OA\Property(property="publisher", type="string", maxLength=255),
 *     @OA\Property(property="author", type="string", maxLength=255),
 *     @OA\Property(
 *         property="genres",
 *         type="array",
 *         minItems=1,
 *         @OA\Items(type="string", maxLength=100)
 *     ),
 *     @OA\Property(property="published_at", type="string", format="date"),
 *     @OA\Property(property="word_count", type="integer", minimum=0),
 *     @OA\Property(property="price_usd", type="number", format="float", minimum=0)
 * )
 *
 * @OA\Schema(
 *     schema="BookResponse",
 *     type="object",
 *     @OA\Property(property="data", ref="#/components/schemas/Book")
 * )
 *
 * @OA\Schema(
 *     schema="BooksIndexResponse",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Book")
 *     ),
 *     @OA\Property(property="links", type="object", additionalProperties=true),
 *     @OA\Property(property="meta", type="object", additionalProperties=true)
 * )
 */
class OpenApi
{
}
