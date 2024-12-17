<?php

namespace App\Http\Resources\WorkUnit;

use App\Http\Resources\Employee\EmployeeCollectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkUnitResourceById extends JsonResource
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
            'unit_name' => $this->unit_name,
            'grade' => $this->grade,
            'echelon' => $this->echelon,
            'list_employee' => EmployeeCollectionResource::collection($this->whenLoaded('employees')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
