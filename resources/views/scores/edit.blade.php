@extends('layouts.app')

@section('title', 'Edit Nilai')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Nilai</h1>
        <a href="{{ route('scores.show', $score) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Nilai</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('scores.update', $score) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id" class="form-label">Mahasiswa <span class="text-danger">*</span></label>
                                    <select class="form-control @error('student_id') is-invalid @enderror" 
                                            id="student_id" 
                                            name="student_id" 
                                            required>
                                        <option value="">Pilih Mahasiswa</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" 
                                                    {{ old('student_id', $score->student_id) == $student->id ? 'selected' : '' }}>
                                                {{ $student->nim }} - {{ $student->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-control @error('subject_id') is-invalid @enderror" 
                                            id="subject_id" 
                                            name="subject_id" 
                                            required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                    {{ old('subject_id', $score->subject_id) == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->code }} - {{ $subject->name }} ({{ $subject->credits }} SKS)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="score" class="form-label">Nilai <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('score') is-invalid @enderror" 
                                           id="score" 
                                           name="score" 
                                           value="{{ old('score', $score->score) }}" 
                                           min="0" 
                                           max="100" 
                                           step="0.1"
                                           placeholder="0-100"
                                           required>
                                    @error('score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Masukkan nilai antara 0-100</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Grade & Status</label>
                                    <div class="border rounded p-3 bg-light">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="font-weight-bold text-primary" id="gradeDisplay">-</div>
                                                <small class="text-muted">Grade</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="font-weight-bold" id="statusDisplay">-</div>
                                                <small class="text-muted">Status</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="font-weight-bold text-info" id="scoreDisplay">-</div>
                                                <small class="text-muted">Nilai</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Catatan tambahan untuk nilai ini (opsional)">{{ old('notes', $score->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maksimal 500 karakter</small>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Nilai
                                    </button>
                                    <a href="{{ route('scores.show', $score) }}" class="btn btn-secondary mr-2">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle"></i> Field bertanda <span class="text-danger">*</span> wajib diisi
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Grade Scale Reference -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i> Skala Penilaian
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Skala Grade:</h6>
                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-success">A</span>
                                    <span>85 - 100</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-success">A-</span>
                                    <span>80 - 84</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-warning">B+</span>
                                    <span>75 - 79</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-warning">B</span>
                                    <span>70 - 74</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-warning">B-</span>
                                    <span>65 - 69</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="badge badge-info">C+</span>
                                    <span>60 - 64</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Lanjutan:</h6>
                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-info">C</span>
                                    <span>55 - 59</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-info">C-</span>
                                    <span>50 - 54</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-danger">D+</span>
                                    <span>45 - 49</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-danger">D</span>
                                    <span>40 - 44</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="badge badge-danger">E</span>
                                    <span>0 - 39</span>
                                </div>
                                <div class="mt-2 text-muted">
                                    <small><strong>Catatan:</strong> Nilai ≥60 = Lulus</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Use vanilla JavaScript as fallback in case jQuery is not loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing grade preview for edit...'); // Debug log
    
    // Function to calculate grade and status
    function updateGradeStatus() {
        console.log('updateGradeStatus called'); // Debug log
        
        let scoreInput = document.getElementById('score');
        let scoreValue = scoreInput.value;
        let score = parseFloat(scoreValue);
        
        console.log('Score input value:', scoreValue, 'Parsed score:', score); // Debug log
        
        let grade = '';
        let status = '';
        let statusClass = '';
        
        // Get display elements
        let gradeDisplay = document.getElementById('gradeDisplay');
        let statusDisplay = document.getElementById('statusDisplay');
        let scoreDisplay = document.getElementById('scoreDisplay');
        
        // Handle empty or invalid input
        if (!scoreValue || scoreValue.trim() === '' || isNaN(score)) {
            gradeDisplay.textContent = '-';
            statusDisplay.className = 'font-weight-bold';
            statusDisplay.textContent = '-';
            scoreDisplay.textContent = '-';
            console.log('Empty or invalid input, showing dashes'); // Debug log
            return;
        }
        
        // Ensure score is within valid range
        if (score < 0) score = 0;
        if (score > 100) score = 100;
        
        // Calculate grade
        if (score >= 85) {
            grade = 'A';
        } else if (score >= 80) {
            grade = 'A-';
        } else if (score >= 75) {
            grade = 'B+';
        } else if (score >= 70) {
            grade = 'B';
        } else if (score >= 65) {
            grade = 'B-';
        } else if (score >= 60) {
            grade = 'C+';
        } else if (score >= 55) {
            grade = 'C';
        } else if (score >= 50) {
            grade = 'C-';
        } else if (score >= 45) {
            grade = 'D+';
        } else if (score >= 40) {
            grade = 'D';
        } else {
            grade = 'E';
        }
        
        // Calculate status
        if (score >= 60) {
            status = 'LULUS';
            statusClass = 'font-weight-bold text-success';
        } else {
            status = 'TIDAK LULUS';
            statusClass = 'font-weight-bold text-danger';
        }
        
        // Update display
        gradeDisplay.textContent = grade;
        statusDisplay.className = statusClass;
        statusDisplay.textContent = status;
        scoreDisplay.textContent = score;
        
        console.log('Updated display - Grade:', grade, 'Status:', status, 'Score:', score); // Debug log
    }
    
    // Add event listeners to score input
    let scoreInput = document.getElementById('score');
    if (scoreInput) {
        scoreInput.addEventListener('input', updateGradeStatus);
        scoreInput.addEventListener('keyup', updateGradeStatus);
        scoreInput.addEventListener('change', updateGradeStatus);
        scoreInput.addEventListener('paste', function() {
            setTimeout(updateGradeStatus, 10); // Small delay for paste event
        });
        
        console.log('Event listeners added to score input'); // Debug log
    } else {
        console.error('Score input element not found!'); // Debug log
    }
    
    // Initialize grade status on page load
    updateGradeStatus();
    
    // Also add jQuery version if available (for compatibility)
    if (typeof $ !== 'undefined') {
        console.log('jQuery detected, adding jQuery event handlers'); // Debug log
        
        $('#score').on('input keyup change paste', function() {
            updateGradeStatus();
        });
        
        // Form validation
        $('form').on('submit', function(e) {
            let isValid = true;
            
            // Check required fields
            $('input[required], select[required]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            // Validate score range
            let score = parseFloat($('#score').val());
            if (isNaN(score) || score < 0 || score > 100) {
                isValid = false;
                $('#score').addClass('is-invalid');
                $('#score').siblings('.invalid-feedback').text('Nilai harus antara 0-100');
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Mohon periksa kembali input Anda');
            }
        });
        
        // Remove invalid class on input
        $('input, select, textarea').on('input change', function() {
            $(this).removeClass('is-invalid');
        });
        
        // Character counter for notes
        $('#notes').on('input', function() {
            let length = $(this).val().length;
            let maxLength = 500;
            
            if (length > maxLength) {
                $(this).val($(this).val().substring(0, maxLength));
                length = maxLength;
            }
            
            let counter = $(this).siblings('.form-text');
            counter.text(`${length}/${maxLength} karakter`);
            
            if (length > maxLength * 0.9) {
                counter.addClass('text-warning').removeClass('text-muted');
            } else {
                counter.addClass('text-muted').removeClass('text-warning');
            }
        });
        
        // Score input validation
        $('#score').on('input', function() {
            let value = $(this).val();
            
            // Remove invalid characters
            value = value.replace(/[^0-9.]/g, '');
            
            // Ensure only one decimal point
            let parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            
            $(this).val(value);
            
            // Validate range
            let score = parseFloat(value);
            if (score > 100) {
                $(this).val('100');
            } else if (score < 0) {
                $(this).val('0');
            }
        });
        
        // Enhanced select2 for better UX (if available)
        if (typeof $.fn.select2 !== 'undefined') {
            $('#student_id, #subject_id').select2({
                theme: 'bootstrap4',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });
        }
    }
});
</script>
@endpush

@endsection
