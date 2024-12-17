<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
            'work_unit_id' => 'required|exists:work_units,id',
            'nip' => 'required|unique:employees,nip',
            'name' => 'required',
            'position' => 'required',
            'duty_place' => 'required',
            'religion' => 'required',
            'phone_number' => 'required',
            'npwp' => 'required',
            'photo' => 'required|file|mimes:jpg,png,jpeg',
            'email' => 'required',
            'birth_place' => 'required',
            'birth_date' => 'required',
            'gender' => 'required',
            'address' => 'required'
        ];
    }

}
