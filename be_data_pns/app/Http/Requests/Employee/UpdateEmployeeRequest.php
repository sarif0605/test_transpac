<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Pastikan hanya pengguna yang berwenang dapat memperbarui data karyawan.
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
            'nip' => 'nullable',
            'name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'duty_place' => 'nullable|string|max:255',
            'religion' => 'nullable|string|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'phone_number' => 'nullable|numeric|digits_between:10,15',
            'npwp' => 'nullable|numeric|digits:15',
            'photo' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'email' => 'nullable|email',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|string',
            'address' => 'nullable|string|max:500',
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
            'work_unit_id.exists' => 'Unit kerja tidak ditemukan.',
            'nip.max' => 'NIP tidak boleh lebih dari 20 karakter.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'position.string' => 'Jabatan harus berupa teks.',
            'position.max' => 'Jabatan tidak boleh lebih dari 100 karakter.',
            'duty_place.string' => 'Tempat tugas harus berupa teks.',
            'duty_place.max' => 'Tempat tugas tidak boleh lebih dari 255 karakter.',
            'religion.in' => 'Agama harus salah satu dari: Islam, Kristen, Katolik, Hindu, Buddha, Konghucu.',
            'phone_number.numeric' => 'Nomor telepon harus berupa angka.',
            'phone_number.digits_between' => 'Nomor telepon harus terdiri dari 10 hingga 15 digit.',
            'npwp.numeric' => 'NPWP harus berupa angka.',
            'npwp.digits' => 'NPWP harus terdiri dari 15 digit.',
            'photo.file' => 'Foto harus berupa file.',
            'photo.mimes' => 'Foto harus memiliki format jpg, png, atau jpeg.',
            'photo.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
            'email.email' => 'Email harus dalam format yang valid.',
            'email.unique' => 'Email sudah digunakan.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'birth_place.string' => 'Tempat lahir harus berupa teks.',
            'birth_place.max' => 'Tempat lahir tidak boleh lebih dari 255 karakter.',
            'birth_date.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'address.string' => 'Alamat harus berupa teks.',
            'address.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
        ];
    }
}
