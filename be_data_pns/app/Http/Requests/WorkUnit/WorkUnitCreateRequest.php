<?php

namespace App\Http\Requests\WorkUnit;

use Illuminate\Foundation\Http\FormRequest;

class WorkUnitCreateRequest extends FormRequest
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
            'unit_name' => 'required',
            'grade' => 'required',
            'echelon' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'grade.required' => 'grade harus di isi',
            'echelon.required' => 'echelon harus diisi',
            'unit_name.required' => 'unit name harus diisi'
        ];
    }
}
