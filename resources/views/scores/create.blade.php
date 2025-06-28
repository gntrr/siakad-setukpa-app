@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Input Nilai Baru</h1>
        <a href="{{ route('scores.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Input Nilai</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('scores.store') }}" method="POST">
                        @csrf

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
                                                    {{ old('student_id') == $student->id ? 'selected' : '' }}>
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
                                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
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
                                           value="{{ old('score') }}" 
                                           min="0" 
                                           max="100" 
                                           step="0.1"
                                           placeholder="Masukkan nilai (0-100)"
                                           required>
                                    @error('score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Masukkan nilai antara 0-100</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Preview Grade & Status</label>
                                    <div class="border rounded p-3 bg-light">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="font-weight-bold text-primary h4" id="gradeDisplay">-</div>
                                                <small class="text-muted">Grade</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="font-weight-bold h5" id="statusDisplay">-</div>
                                                <small class="text-muted">Status</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="font-weight-bold text-info h4" id="scoreDisplay">-</div>
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
                                      placeholder="Catatan tambahan untuk nilai ini (opsional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maksimal 500 karakter</small>
                        </div>

                        <!-- Duplicate Check Warning -->
                        <div id="duplicateWarning" class="alert alert-warning d-none">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Peringatan:</strong> Mahasiswa ini sudah memiliki nilai untuk mata pelajaran yang dipilih. 
                            Melanjutkan akan menggantikan nilai yang sudah ada.
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Nilai
                                    </button>
                                    <a href="{{ route('scores.index') }}" class="btn btn-secondary mr-2">
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
                        <i class="fas fa-info-circle"></i> Panduan Penilaian
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Skala Grade:</h6>
                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-success">A</span>
                                    <span>85 - 100 (Sangat Baik)</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-success">A-</span>
                                    <span>80 - 84 (Baik Sekali)</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-warning">B+</span>
                                    <span>75 - 79 (Baik)</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-warning">B</span>
                                    <span>70 - 74 (Cukup Baik)</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-warning">B-</span>
                                    <span>65 - 69 (Cukup)</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="badge badge-info">C+</span>
                                    <span>60 - 64 (Kurang Baik)</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Lanjutan:</h6>
                            <div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-info">C</span>
                                    <span>55 - 59 (Kurang)</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-info">C-</span>
                                    <span>50 - 54 (Kurang Sekali)</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-danger">D+</span>
                                    <span>45 - 49 (Buruk)</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge badge-danger">D</span>
                                    <span>40 - 44 (Buruk Sekali)</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="badge badge-danger">E</span>
                                    <span>0 - 39 (Sangat Buruk)</span>
                                </div>
                                <div class="mt-2 p-2 bg-light rounded">
                                    <small class="font-weight-bold">
                                        <i class="fas fa-check-circle text-success"></i> 
                                        Nilai ≥60 = LULUS
                                    </small><br>
                                    <small class="font-weight-bold">
                                        <i class="fas fa-times-circle text-danger"></i> 
                                        Nilai <60 = TIDAK LULUS
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Input Option -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-upload"></i> Input Massal
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Perlu input nilai untuk banyak mahasiswa sekaligus? Gunakan fitur import Excel.</p>
                    <a href="" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Import dari Excel
                    </a>
                    <a href="" class="btn btn-outline-success mr-2">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Use vanilla JavaScript as fallback in case jQuery is not loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing grade preview...'); // Debug log
    
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
            statusDisplay.className = 'font-weight-bold h5';
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
            statusClass = 'font-weight-bold h5 text-success';
        } else {
            status = 'TIDAK LULUS';
            statusClass = 'font-weight-bold h5 text-danger';
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
            
            // Validate range and update display
            let score = parseFloat(value);
            if (score > 100) {
                $(this).val('100');
            } else if (score < 0) {
                $(this).val('0');
            }
            
            // Update grade status after validation
            updateGradeStatus();
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
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon periksa kembali input Anda');
        } else {
            // If form is valid, refresh pending count after form submission
            setTimeout(function() {
                if (typeof window.refreshPendingCount === 'function') {
                    window.refreshPendingCount();
                }
            }, 1000);
        }
    });
});
</script>
@endpush

@endsection