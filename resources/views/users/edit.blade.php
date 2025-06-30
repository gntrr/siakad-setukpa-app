@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Pengguna</h1>
        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Pengguna</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           placeholder="Masukkan nama lengkap"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           placeholder="Masukkan alamat email"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-control @error('role') is-invalid @enderror" 
                                            id="role" 
                                            name="role" 
                                            required
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                            Admin - Akses penuh ke sistem
                                        </option>
                                        <option value="dosen" {{ old('role', $user->role) == 'dosen' ? 'selected' : '' }}>
                                            Dosen - Dapat mengelola nilai mahasiswa
                                        </option>
                                        <option value="manajemen" {{ old('role', $user->role) == 'manajemen' ? 'selected' : '' }}>
                                            Manajemen/Staff - Dapat mengelola data mahasiswa
                                        </option>
                                    </select>
                                    @if($user->id === auth()->id())
                                        <input type="hidden" name="role" value="{{ $user->role }}">
                                        <small class="form-text text-muted">Anda tidak dapat mengubah role sendiri</small>
                                    @endif
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email_verified" class="form-label">Status Email</label>
                                    <div class="form-control-plaintext">
                                        @if($user->email_verified_at)
                                            <span class="badge badge-success">Terverifikasi</span>
                                            <small class="text-muted d-block">{{ $user->email_verified_at->format('d M Y') }}</small>
                                        @else
                                            <span class="badge badge-warning">Belum Verifikasi</span>
                                            @can('update', $user)
                                            <button type="button" class="btn btn-sm btn-outline-primary mr-2" 
                                                    onclick="verifyEmail({{ $user->id }})">
                                                Verifikasi Sekarang
                                            </button>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <button class="btn btn-link text-decoration-none p-0" type="button" 
                                            id="passwordToggle">
                                        <i class="fas fa-key"></i> Ubah Password
                                        <i class="fas fa-chevron-down ml-2" id="passwordChevron"></i>
                                    </button>
                                </h6>
                            </div>
                            <div id="passwordSection" style="display: none;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password" class="form-label">Password Baru</label>
                                                <input type="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" 
                                                       name="password" 
                                                       placeholder="Masukkan password baru (opsional)">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="form-text text-muted">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="password_confirmation" 
                                                       name="password_confirmation" 
                                                       placeholder="Konfirmasi password baru">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="force_password_change" name="force_password_change" value="1">
                                        <label class="form-check-label" for="force_password_change">
                                            Paksa pengguna untuk mengubah password saat login berikutnya
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Info -->
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle"></i> Informasi Saat Ini
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Dibuat:</small>
                                        <div>{{ $user->created_at->format('d M Y H:i') }}</div>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Terakhir Update:</small>
                                        <div>{{ $user->updated_at->format('d M Y H:i') }}</div>
                                        <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Pengguna
                                    </button>
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-secondary mr-2">
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
        </div>

        <!-- Role Information -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-shield"></i> Informasi Role
                    </h6>
                </div>
                <div class="card-body">
                    <div class="role-info" data-role="admin" style="display: none;">
                        <h6 class="font-weight-bold text-danger">Administrator</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success"></i> Mengelola semua pengguna</li>
                            <li><i class="fas fa-check text-success"></i> Mengelola semua data mahasiswa</li>
                            <li><i class="fas fa-check text-success"></i> Mengelola mata pelajaran</li>
                            <li><i class="fas fa-check text-success"></i> Mengelola nilai</li>
                            <li><i class="fas fa-check text-success"></i> Melihat laporan lengkap</li>
                            <li><i class="fas fa-check text-success"></i> Mengirim notifikasi</li>
                        </ul>
                    </div>
                    
                    <div class="role-info" data-role="dosen" style="display: none;">
                        <h6 class="font-weight-bold text-warning">Dosen</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success"></i> Melihat data mahasiswa</li>
                            <li><i class="fas fa-check text-success"></i> Mengelola nilai mahasiswa</li>
                            <li><i class="fas fa-check text-success"></i> Melihat laporan terbatas</li>
                            <li><i class="fas fa-check text-success"></i> Update profil sendiri</li>
                            <li><i class="fas fa-times text-danger"></i> Mengelola pengguna lain</li>
                            <li><i class="fas fa-times text-danger"></i> Mengelola mata pelajaran</li>
                        </ul>
                    </div>
                    
                    <div class="role-info" data-role="manajemen" style="display: none;">
                        <h6 class="font-weight-bold text-info">Manajemen/Staff</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success"></i> Mengelola data mahasiswa</li>
                            <li><i class="fas fa-check text-success"></i> Melihat laporan</li>
                            <li><i class="fas fa-check text-success"></i> Update profil sendiri</li>
                            <li><i class="fas fa-times text-danger"></i> Mengelola pengguna lain</li>
                            <li><i class="fas fa-times text-danger"></i> Mengelola nilai</li>
                            <li><i class="fas fa-times text-danger"></i> Mengelola mata pelajaran</li>
                        </ul>
                    </div>
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <small class="text-muted">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            <strong>Perhatian:</strong> Mengubah role akan mempengaruhi hak akses pengguna dalam sistem.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password section toggle
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordSection = document.getElementById('passwordSection');
    const passwordChevron = document.getElementById('passwordChevron');
    
    passwordToggle.addEventListener('click', function() {
        if (passwordSection.style.display === 'none') {
            passwordSection.style.display = 'block';
            passwordChevron.classList.remove('fa-chevron-down');
            passwordChevron.classList.add('fa-chevron-up');
        } else {
            passwordSection.style.display = 'none';
            passwordChevron.classList.remove('fa-chevron-up');
            passwordChevron.classList.add('fa-chevron-down');
        }
    });

    // Show role information based on selection
    function showRoleInfo() {
        const selectedRole = document.getElementById('role').value;
        const roleInfos = document.querySelectorAll('.role-info');
        
        // Hide all role info sections
        roleInfos.forEach(function(info) {
            info.style.display = 'none';
        });
        
        // Show selected role info
        if (selectedRole) {
            const selectedInfo = document.querySelector(`.role-info[data-role="${selectedRole}"]`);
            if (selectedInfo) {
                selectedInfo.style.display = 'block';
            }
        }
    }
    
    // Initialize role info display
    showRoleInfo();
    
    // Update role info on change
    document.getElementById('role').addEventListener('change', showRoleInfo);
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        const requiredFields = document.querySelectorAll('input[required], select[required]');
        requiredFields.forEach(function(field) {
            if (!field.value) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Check password confirmation
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        
        if (password && password !== passwordConfirmation) {
            isValid = false;
            const confirmField = document.getElementById('password_confirmation');
            confirmField.classList.add('is-invalid');
            
            // Remove existing feedback
            const existingFeedback = confirmField.parentNode.querySelector('.invalid-feedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            // Add new feedback
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = 'Konfirmasi password tidak cocok';
            confirmField.parentNode.appendChild(feedback);
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon periksa kembali input Anda');
        }
    });
    
    // Remove invalid class on input
    const inputs = document.querySelectorAll('input, select');
    inputs.forEach(function(input) {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        input.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
    });
    
    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let feedback = '';
        
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        switch (strength) {
            case 0:
            case 1:
                feedback = '<small class="text-danger">Password sangat lemah</small>';
                break;
            case 2:
                feedback = '<small class="text-warning">Password lemah</small>';
                break;
            case 3:
                feedback = '<small class="text-info">Password sedang</small>';
                break;
            case 4:
                feedback = '<small class="text-success">Password kuat</small>';
                break;
            case 5:
                feedback = '<small class="text-success">Password sangat kuat</small>';
                break;
        }
        
        // Remove existing strength indicator
        const existingStrength = this.parentNode.querySelector('.password-strength');
        if (existingStrength) {
            existingStrength.remove();
        }
        
        // Add new strength indicator
        if (password) {
            const strengthDiv = document.createElement('div');
            strengthDiv.className = 'password-strength';
            strengthDiv.innerHTML = feedback;
            this.parentNode.appendChild(strengthDiv);
        }
    });
});

function verifyEmail(userId) {
    if (confirm('Apakah Anda yakin ingin memverifikasi email pengguna ini?')) {
        fetch(`/users/${userId}/verify-email`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memverifikasi email');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}
</script>
@endpush
@endsection
