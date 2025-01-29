<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
/**
 * @OA\Schema(
 *     schema="CategoryCollection",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/CategoryResource")
 * )
 */
class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => CategoryResource::collection($this->collection),
            'links' => [
                'self' => route('category.index'),
            ],
            'meta' => [
                'category_count' => $this->collection->count()
            ]
        ];
    }
}
