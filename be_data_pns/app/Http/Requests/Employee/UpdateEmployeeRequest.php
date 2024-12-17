<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'work_unit_id' => 'nullable|exists:work_units,id',
            'nip' => 'nullable|unique:employees,nip',
            'name' => 'nullable',
            'position' => 'nullable',
            'duty_place' => 'nullable',
            'religion' => 'nullable',
            'phone_number' => 'nullable',
            'npwp' => 'nullable',
            'photo' => 'nullable|file|mimes:jpg,png,jpeg',
            'email' => 'nullable',
            'birth_place' => 'nullable',
            'birth_date' => 'nullable',
            'gender' => 'nullable',
            'address' => 'nullable'
        ];
    }
}
