<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="List books",
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of books (data + meta + links)",
     *         @OA\JsonContent(ref="#/components/schemas/BooksIndexResponse")
     *     )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return BookResource::collection(Book::query()->paginate());
    }

    /**
     * @OA\Post(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="Create book",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BookCreate")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/BookResponse")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = DB::transaction(fn () => Book::query()->create($request->validated()));

        return $this->bookResponse($book, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Show book",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/BookResponse")
     *     ),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(Book $book): BookResource
    {
        return new BookResource($book);
    }

    /**
     * @OA\Patch(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Update book",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BookUpdate")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/BookResponse")
     *     ),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        DB::transaction(fn () => $book->update($request->validated()));

        return $this->bookResponse($book);
    }

    /**
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Delete book",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="No content"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy(Book $book): Response
    {
        DB::transaction(fn () => $book->delete());

        return response()->noContent();
    }

    private function bookResponse(Book $book, int $status = 200): JsonResponse
    {
        return (new BookResource($book))
            ->response()
            ->setStatusCode($status);
    }
}
