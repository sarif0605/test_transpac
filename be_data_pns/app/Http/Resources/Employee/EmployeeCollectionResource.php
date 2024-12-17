<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployeeCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'message' => 'Berhasil Menampilkan Employee',
            'data' => EmployeeResource::collection($this->collection), // Gunakan $this->collection
            'pagination' => [
                'total' => $this->resource->total(),
                'per_page' => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'next_page_url' => $this->resource->nextPageUrl(),
                'prev_page_url' => $this->resource->previousPageUrl(),
            ],
        ];
    }
}
