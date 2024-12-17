<?php

namespace App\Http\Requests\WorkUnit;

use Illuminate\Foundation\Http\FormRequest;

class WorkUnitUpdateRequest extends FormRequest
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
            'grade' => 'nullable',
            'echelon' => 'nullable',
            'unit_name' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'grade.nullable' => 'grade tidak boleh null',
            'echelon.nullable' => 'echelon tidak boleh null',
            'unit_name.nullable' => 'unit name tidak boleh null'
        ];
    }
}
