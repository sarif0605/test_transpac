<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Pastikan hanya pengguna yang berwenang dapat membuat karyawan.
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
            'nip' => 'required|unique:employees,nip|max:20',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:100',
            'duty_place' => 'required|string|max:255',
            'religion' => 'required|string|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'phone_number' => 'required|numeric|digits_between:10,15',
            'npwp' => 'required|numeric|digits:15',
            'photo' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'email' => 'required|email|unique:employees,email|max:255',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|string',
            'address' => 'required|string|max:500',
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
            'work_unit_id.required' => 'Unit kerja harus diisi.',
            'work_unit_id.exists' => 'Unit kerja tidak ditemukan.',
            'nip.required' => 'NIP harus diisi.',
            'nip.unique' => 'NIP sudah digunakan.',
            'nip.max' => 'NIP tidak boleh lebih dari 20 karakter.',
            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'position.required' => 'Jabatan harus diisi.',
            'position.string' => 'Jabatan harus berupa teks.',
            'position.max' => 'Jabatan tidak boleh lebih dari 100 karakter.',
            'duty_place.required' => 'Tempat tugas harus diisi.',
            'duty_place.string' => 'Tempat tugas harus berupa teks.',
            'duty_place.max' => 'Tempat tugas tidak boleh lebih dari 255 karakter.',
            'religion.required' => 'Agama harus diisi.',
            'religion.in' => 'Agama harus salah satu dari: Islam, Kristen, Katolik, Hindu, Buddha, Konghucu.',
            'phone_number.required' => 'Nomor telepon harus diisi.',
            'phone_number.numeric' => 'Nomor telepon harus berupa angka.',
            'phone_number.digits_between' => 'Nomor telepon harus terdiri dari 10 hingga 15 digit.',
            'npwp.required' => 'NPWP harus diisi.',
            'npwp.numeric' => 'NPWP harus berupa angka.',
            'npwp.digits' => 'NPWP harus terdiri dari 15 digit.',
            'photo.required' => 'Foto harus diunggah.',
            'photo.file' => 'Foto harus berupa file.',
            'photo.mimes' => 'Foto harus memiliki format jpg, png, atau jpeg.',
            'photo.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus dalam format yang valid.',
            'email.unique' => 'Email sudah digunakan.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'birth_place.required' => 'Tempat lahir harus diisi.',
            'birth_place.string' => 'Tempat lahir harus berupa teks.',
            'birth_place.max' => 'Tempat lahir tidak boleh lebih dari 255 karakter.',
            'birth_date.required' => 'Tanggal lahir harus diisi.',
            'birth_date.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'gender.required' => 'Jenis kelamin harus diisi.',
            'address.required' => 'Alamat harus diisi.',
            'address.string' => 'Alamat harus berupa teks.',
            'address.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
        ];
    }
}
