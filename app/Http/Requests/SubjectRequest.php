<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $subject = $this->route('subject');
        
        if ($this->isMethod('POST')) {
            // For create
            return $this->user()->can('create', \App\Models\Subject::class);
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // For update
            return $subject && $this->user()->can('update', $subject);
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
        $subjectId = $this->route('subject') ? $this->route('subject')->id : null;
        
        return [
            'code' => [
                'required', 
                'string', 
                'max:10',
                'regex:/^[A-Z]{3}[0-9]{3}$/',
                Rule::unique('subjects')->ignore($subjectId)
            ],
            'name' => ['required', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'code.unique' => 'Kode mata pelajaran sudah digunakan.',
            'code.regex' => 'Format kode mata pelajaran tidak valid (contoh: MTH101).',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
        ];
    }
}
