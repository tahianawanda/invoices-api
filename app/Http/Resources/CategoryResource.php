<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'category',
            'id' => (string) $this->resource->id,
            'attributes' => [
                'name' => $this->resource->name,
                'slug' => $this->resource->slug
            ],
            'link' => [
                'self' => route('category.show', $this->resource->id)
            ]
        ];
    }
}
