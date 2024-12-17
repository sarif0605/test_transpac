<?php

namespace App\Http\Resources\WorkUnit;

use App\Http\Resources\Employee\EmployeeCollectionResource;
use App\Http\Resources\Employee\EmployeeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkUnitResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
