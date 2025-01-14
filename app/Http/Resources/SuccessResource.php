<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class SuccessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dataResource = $this->resource['data_resource'] ?? null;

        if ($dataResource && class_exists($dataResource)) {
            return [
                'success' => $this->resource['success'],
                'message' => $this->resource['message'],
                'data' => $dataResource::make($this->resource['data']),
            ];
        }

        return [
            'success' => $this->resource['success'],
            'data' => $this->resource['data'],
        ];
    }
}
