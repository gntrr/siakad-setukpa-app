@extends('layouts.app')

@section('content')
<!-- Debug Information -->
<!-- @if(config('app.debug'))
<div class="container-fluid">
    <div class="alert alert-info">
        <strong>Debug Info:</strong><br>
        Student ID: {{ $student->id ?? 'N/A' }}<br>
        Student Number: {{ $student->student_number ?? 'N/A' }}<br>
        Student Name: {{ $student->name ?? 'N/A' }}<br>
        Student Gender: {{ $student->gender ?? 'N/A' }}<br>
        Student Birth Date: {{ $student->birth_date ?? 'N/A' }}<br>
        Birth Date Formatted: {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('Y-m-d') : 'N/A' }}
    </div>
</div>
@endif -->

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Siswa</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Data Siswa</a></li>
                <li class="breadcrumb-item active">Edit Siswa</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Data Siswa</h6>
                </div>
                <div class="card-body">
                    <form id="studentForm" method="POST" action="{{ route('students.update', $student->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Nomor Induk Siswa -->
                            <div class="col-md-6 mb-3">
                                <label for="student_number" class="form-label">Nomor Induk Siswa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('student_number') is-invalid @enderror" 
                                       id="student_number" name="student_number" value="{{ old('student_number', $student->student_number) }}" 
                                       placeholder="Contoh: 2024001" required>
                                @error('student_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Nomor induk siswa harus unik dan tidak boleh kosong</div>
                            </div>

                            <!-- Nama Lengkap -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $student->name) }}" 
                                       placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Jenis Kelamin -->
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" {{ old('gender', $student->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender', $student->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" 
                                       value="{{ old('birth_date', $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('Y-m-d') : '') }}" required>
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('students.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Update Siswa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Form Guidelines -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Panduan Pengisian</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">Field Wajib:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success mr-2"></i>Nomor Induk Siswa</li>
                        <li><i class="fas fa-check text-success mr-2"></i>Nama Lengkap</li>
                        <li><i class="fas fa-check text-success mr-2"></i>Jenis Kelamin</li>
                        <li><i class="fas fa-check text-success mr-2"></i>Tanggal Lahir</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary">Catatan:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-info-circle text-info mr-2"></i>Nomor induk siswa harus unik</li>
                        <li><i class="fas fa-info-circle text-info mr-2"></i>Tanggal lahir harus sebelum hari ini</li>
                        <li><i class="fas fa-info-circle text-info mr-2"></i>Jenis kelamin harus dipilih</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary">Shortcut Keyboard:</h6>
                    <ul class="list-unstyled small">
                        <li><kbd>Tab</kbd> - Pindah ke field berikutnya</li>
                        <li><kbd>Shift + Tab</kbd> - Pindah ke field sebelumnya</li>
                        <li><kbd>Ctrl + S</kbd> - Simpan form</li>
                        <li><kbd>Esc</kbd> - Kembali ke daftar siswa</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentStudentNumber = '{{ $student->student_number }}';
    
    // Auto-format student number input (only numbers)
    const studentNumberInput = document.getElementById('student_number');
    studentNumberInput.addEventListener('input', function(e) {
        // Allow alphanumeric characters for student number
        let value = e.target.value.replace(/[^a-zA-Z0-9]/g, '');
        e.target.value = value;
        
        // Check if student number already exists (only if different from current)
        if (value.length >= 3 && value !== currentStudentNumber) {
            checkStudentNumberExists(value);
        }
    });

    // Handle form submission
    document.getElementById('studentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const button = this.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        
        // Add debugging
        console.log('Form data:', Object.fromEntries(formData));
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                showAlert('success', 'Data siswa berhasil diupdate!');
                setTimeout(() => {
                    window.location.href = '{{ route("students.index") }}';
                }, 1500);
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan');
                if (data.errors) {
                    displayValidationErrors(data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showAlert('error', 'Terjadi kesalahan sistem: ' + error.message);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            document.getElementById('studentForm').dispatchEvent(new Event('submit'));
        }
        
        if (e.key === 'Escape') {
            window.location.href = '{{ route("students.index") }}';
        }
    });
});

function checkStudentNumberExists(studentNumber) {
    fetch(`/api/students/check-student-number?student_number=${studentNumber}&exclude={{ $student->id }}`)
        .then(response => response.json())
        .then(data => {
            const studentNumberInput = document.getElementById('student_number');
            if (data.exists) {
                studentNumberInput.classList.add('is-invalid');
                showFieldError('student_number', 'Nomor induk siswa sudah digunakan');
            } else {
                studentNumberInput.classList.remove('is-invalid');
                hideFieldError('student_number');
            }
        })
        .catch(error => {
            console.error('Error checking student number:', error);
        });
}

function displayValidationErrors(errors) {
    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.remove();
    });

    // Display new errors
    for (const [field, messages] of Object.entries(errors)) {
        const input = document.getElementById(field);
        if (input) {
            input.classList.add('is-invalid');
            showFieldError(field, messages[0]);
        }
    }
}

function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.add('is-invalid');
        
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
}

function hideFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endpush
