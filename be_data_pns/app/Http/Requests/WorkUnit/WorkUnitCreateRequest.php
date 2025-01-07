<?php

namespace App\Http\Requests\WorkUnit;

use Illuminate\Foundation\Http\FormRequest;

class WorkUnitCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Pastikan hanya pengguna yang berwenang yang dapat membuat permintaan ini.
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
            'unit_name' => 'required|string|max:255',
            'grade' => 'required',
            'echelon' => 'required|string',
        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'unit_name.required' => 'Nama unit harus diisi.',
            'unit_name.string' => 'Nama unit harus berupa teks.',
            'unit_name.max' => 'Nama unit tidak boleh lebih dari 255 karakter.',
            'grade.required' => 'Grade harus diisi.',
            'echelon.required' => 'Eselon harus diisi.',
            'echelon.string' => 'Eselon harus berupa teks.',
        ];
    }
}
