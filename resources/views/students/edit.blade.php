@extends('layouts.app')

@section('content')
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
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h4 class="alert-heading">Terjadi Kesalahan!</h4>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
                                       placeholder="Contoh: STK2024001" required>
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
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $student->email) }}" 
                                       placeholder="contoh@setukpa.ac.id" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $student->phone) }}" 
                                       placeholder="Contoh: 08123456789">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Jenis Kelamin -->
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
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

                        <div class="row">
                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="Aktif" {{ old('status', $student->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ old('status', $student->status) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="Lulus" {{ old('status', $student->status) == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                    <option value="Cuti" {{ old('status', $student->status) == 'Cuti' ? 'selected' : '' }}>Cuti</option>
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
                                      placeholder="Masukkan alamat lengkap">{{ old('address', $student->address) }}</textarea>
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
                        <li><i class="fas fa-check text-success mr-2"></i>Email</li>
                        <li><i class="fas fa-check text-success mr-2"></i>Jenis Kelamin</li>
                        <li><i class="fas fa-check text-success mr-2"></i>Tanggal Lahir</li>
                    </ul>

                    <hr>

                    <h6 class="text-primary">Catatan:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-info-circle text-info mr-2"></i>Nomor induk siswa harus unik</li>
                        <li><i class="fas fa-info-circle text-info mr-2"></i>Email harus valid dan unik</li>
                        <li><i class="fas fa-info-circle text-info mr-2"></i>Tanggal lahir harus sebelum hari ini</li>
                        <li><i class="fas fa-info-circle text-info mr-2"></i>Phone dan alamat bersifat opsional</li>
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
    
    // Auto-format student number input
    const studentNumberInput = document.getElementById('student_number');
    if (studentNumberInput) {
        studentNumberInput.addEventListener('input', function(e) {
            // Allow alphanumeric characters for student number
            let value = e.target.value.replace(/[^a-zA-Z0-9]/g, '');
            e.target.value = value;
        });
    }

    // Auto-format phone input
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            document.getElementById('studentForm').submit();
        }
        
        if (e.key === 'Escape') {
            window.location.href = '{{ route("students.index") }}';
        }
    });
});
</script>
@endpush
