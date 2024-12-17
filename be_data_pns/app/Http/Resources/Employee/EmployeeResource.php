<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id ?? null,
            'nip' => $this->nip ?? null,
            'name' => $this->name ?? null,
            'position' => $this->position ?? null,
            'duty_place' => $this->duty_place ?? null,
            'religion' => $this->religion ?? null,
            'phone_number' => $this->phone_number ?? null,
            'npwp' => $this->npwp ?? null,
            'photo' => $this->photo ?? null,

            // Gunakan whenLoaded untuk work_unit
            'work_unit' => $this->whenLoaded('work_unit', function () {
                return [
                    'id' => $this->work_unit->id ?? null,
                    'unit_name' => $this->work_unit->unit_name ?? null,
                    'grade' => $this->work_unit->grade ?? null,
                    'echelon' => $this->work_unit->echelon ?? null,
                ];
            }),

            // Gunakan whenLoaded untuk profile
            'profile' => $this->whenLoaded('profile', function () {
                return [
                    'gender' => $this->profile->gender ?? null,
                    'birth_place' => $this->profile->birth_place ?? null,
                    'birth_date' => $this->profile->birth_date ?? null,
                    'address' => $this->profile->address ?? null,
                ];
            }),
        ];
    }
}
