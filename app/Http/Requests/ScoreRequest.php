<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Score;

class ScoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $score = $this->route('score');
        
        if ($this->isMethod('POST')) {
            // For create
            return $this->user()->can('create', \App\Models\Score::class);
        } elseif ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // For update
            return $score && $this->user()->can('update', $score);
        }
        
        // For other methods, allow if user is dosen
        return $this->user()->isDosen();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $scoreId = $this->route('score') ? $this->route('score')->id : null;
        
        return [
            'student_id' => [
                'required', 
                'exists:students,id',
                Rule::unique('scores')->ignore($scoreId)->where(function ($query) {
                    return $query->where('subject_id', $this->subject_id);
                })
            ],
            'subject_id' => ['required', 'exists:subjects,id'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'validated' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'Siswa wajib dipilih.',
            'student_id.exists' => 'Siswa tidak ditemukan.',
            'student_id.unique' => 'Nilai untuk siswa dan mata pelajaran ini sudah ada.',
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'subject_id.exists' => 'Mata pelajaran tidak ditemukan.',
            'score.required' => 'Nilai wajib diisi.',
            'score.numeric' => 'Nilai harus berupa angka.',
            'score.min' => 'Nilai minimal 0.',
            'score.max' => 'Nilai maksimal 100.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Additional validation: ensure teacher can only input scores for their subjects
            if ($this->subject_id && auth()->user()->isDosen()) {
                // You can add logic here to check if dosen is assigned to this subject
                // For now, we allow all dosen to input scores for any subject
            }
        });
    }
}
