<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $student = $this->route('student');
        
        if ($this->isMethod('POST')) {
            // For create
            return $this->user()->can('create', \App\Models\Student::class);
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // For update
            return $student && $this->user()->can('update', $student);
        }
        
        // For other methods, allow if user has general access
        return $this->user()->isAdmin() || $this->user()->isManajemen();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $studentId = $this->route('student') ? $this->route('student')->id : null;
        
        return [
            'student_number' => [
                'required', 
                'string', 
                'max:20',
                Rule::unique('students')->ignore($studentId)
            ],
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('students')->ignore($studentId)
            ],
            'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['nullable', 'string', 'max:15'],
            'status' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'student_number.required' => 'Nomor induk siswa wajib diisi.',
            'student_number.unique' => 'Nomor induk siswa sudah digunakan.',
            'name.required' => 'Nama siswa wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin tidak valid.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal tidak valid.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'phone.max' => 'Nomor telepon maksimal 15 karakter.',
            'status.max' => 'Status maksimal 50 karakter.',
            'address.max' => 'Alamat maksimal 255 karakter.',
        ];
    }
}
