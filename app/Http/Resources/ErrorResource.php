<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ErrorResource",
 *     type="object",
 *     title="Error Response",
 *     @OA\Property(property="message", type="string", example="Error"),
 *     @OA\Property(property="errors", type="object", example={"details": "Error message"})
 * )
 */

class ErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->resource['message'],
            'errors' => [
                'details' => $this->resource['message'] ?? 'Dont have more error description'
            ],
        ];
    }
}
