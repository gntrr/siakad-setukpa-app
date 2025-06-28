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
            'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'birth_date' => ['required', 'date', 'before:today'],
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
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin tidak valid.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Format tanggal tidak valid.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
        ];
    }
}
