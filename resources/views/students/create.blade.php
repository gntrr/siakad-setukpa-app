@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Siswa Baru</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Data Siswa</a></li>
                <li class="breadcrumb-item active">Tambah Siswa</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Formulir Data Siswa</h6>
                </div>
                <div class="card-body">
                    <form id="studentForm" method="POST" action="{{ route('students.store') }}">
                        @csrf
                        
                        <div class="row">
                            <!-- NIM -->
                            <div class="col-md-6 mb-3">
                                <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nim') is-invalid @enderror" 
                                       id="nim" name="nim" value="{{ old('nim') }}" 
                                       placeholder="Contoh: 2024001" required>
                                @error('nim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">NIM harus unik dan tidak boleh kosong</div>
                            </div>

                            <!-- Nama Lengkap -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="contoh@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kelas -->
                            <div class="col-md-6 mb-3">
                                <label for="class" class="form-label">Kelas</label>
                                <select class="form-control @error('class') is-invalid @enderror" id="class" name="class">
                                    <option value="">-- Pilih Kelas --</option>
                                    <option value="1A" {{ old('class') == '1A' ? 'selected' : '' }}>Kelas 1A</option>
                                    <option value="1B" {{ old('class') == '1B' ? 'selected' : '' }}>Kelas 1B</option>
                                    <option value="2A" {{ old('class') == '2A' ? 'selected' : '' }}>Kelas 2A</option>
                                    <option value="2B" {{ old('class') == '2B' ? 'selected' : '' }}>Kelas 2B</option>
                                    <option value="3A" {{ old('class') == '3A' ? 'selected' : '' }}>Kelas 3A</option>
                                    <option value="3B" {{ old('class') == '3B' ? 'selected' : '' }}>Kelas 3B</option>
                                </select>
                                @error('class')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Tanggal Lahir -->
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Nomor Telepon -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="Contoh: 08123456789">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('students.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Simpan Siswa
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
                        <li><i class="fas fa-check text-success mr-2"></i>NIM</li>
                        <li><i class="fas fa-check text-success mr-2"></i>Nama Lengkap</li>
                        <li><i class="fas fa-check text-success mr-2"></i>Email</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary">Catatan:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-info-circle text-info mr-2"></i>NIM harus berupa angka dan unik</li>
                        <li><i class="fas fa-info-circle text-info mr-2"></i>Email harus valid dan unik</li>
                        <li><i class="fas fa-info-circle text-info mr-2"></i>Kelas bisa dikosongkan jika belum ditentukan</li>
                        <li><i class="fas fa-info-circle text-info mr-2"></i>Status default adalah Aktif</li>
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
    // Auto-format NIM input
    const nimInput = document.getElementById('nim');
    nimInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
        
        // Check if NIM already exists
        if (value.length >= 4) {
            checkNIMExists(value);
        }
    });

    // Auto-format phone input
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        let value = e.target.value.replace(/\D/g, '');
        e.target.value = value;
    });

    // Handle form submission
    document.getElementById('studentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const button = this.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Siswa berhasil ditambahkan!');
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
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan sistem');
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

function checkNIMExists(nim) {
    fetch(`/api/students/check-nim?nim=${nim}`)
        .then(response => response.json())
        .then(data => {
            const nimInput = document.getElementById('nim');
            if (data.exists) {
                nimInput.classList.add('is-invalid');
                showFieldError('nim', 'NIM sudah digunakan');
            } else {
                nimInput.classList.remove('is-invalid');
                hideFieldError('nim');
            }
        })
        .catch(error => {
            console.error('Error checking NIM:', error);
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
