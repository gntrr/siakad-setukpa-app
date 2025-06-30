@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-plus mr-2"></i>
            Tambah Pengguna Baru
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Pengguna</a></li>
                <li class="breadcrumb-item active">Tambah Baru</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit mr-2"></i>
                        Informasi Pengguna
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" id="userForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Name Field -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Masukkan nama lengkap"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="contoh@email.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Role Field -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role" class="form-label">
                                        Role <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('role') is-invalid @enderror" 
                                            id="role" 
                                            name="role" 
                                            required>
                                        <option value="">Pilih Role</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                        <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>
                                            Dosen
                                        </option>
                                        <option value="manajemen" {{ old('role') == 'manajemen' ? 'selected' : '' }}>
                                            Manajemen/Staff
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Minimal 8 karakter"
                                               required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Password harus minimal 8 karakter dan mengandung huruf serta angka.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">
                                        Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Ulangi password"
                                               required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    id="togglePasswordConfirmation">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group mt-4">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Kembali
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-warning mr-2">
                                        <i class="fas fa-undo mr-2"></i>
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-2"></i>
                                        Simpan Pengguna
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Role Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Role
                    </h6>
                </div>
                <div class="card-body">
                    <div class="role-info" id="roleInfo">
                        <div class="text-center text-muted">
                            <i class="fas fa-arrow-left fa-2x mb-2"></i>
                            <p>Pilih role untuk melihat informasi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Requirements Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Syarat Password
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Minimal 8 karakter
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Mengandung huruf dan angka
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Konfirmasi password harus sama
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.role-info .role-content {
    display: none;
}
.role-info .role-content.active {
    display: block;
}
.password-strength {
    height: 5px;
    background-color: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
}
.password-strength-bar {
    height: 100%;
    transition: all 0.3s ease;
    width: 0%;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Role information content
    const roleInfo = {
        admin: {
            title: 'Administrator',
            icon: 'user-shield',
            color: 'danger',
            description: 'Memiliki akses penuh ke semua fitur sistem.',
            permissions: [
                'Mengelola semua pengguna',
                'Mengelola semua data siswa',
                'Mengelola semua mata pelajaran',
                'Mengelola semua nilai',
                'Melihat semua laporan',
                'Konfigurasi sistem'
            ]
        },
        dosen: {
            title: 'Dosen',
            icon: 'chalkboard-teacher',
            color: 'info',
            description: 'Dapat mengelola nilai untuk mata pelajaran yang diajar.',
            permissions: [
                'Melihat profil sendiri',
                'Mengelola nilai mata pelajaran sendiri',
                'Validasi nilai siswa',
                'Melihat laporan mata pelajaran',
                'Melihat data siswa terkait'
            ]
        },
        staff: {
            title: 'Staff',
            icon: 'user-tie',
            color: 'warning',
            description: 'Membantu dalam administrasi dan pengelolaan data.',
            permissions: [
                'Melihat profil sendiri',
                'Melihat data siswa',
                'Melihat data mata pelajaran',
                'Membantu input data',
                'Melihat laporan terbatas'
            ]
        }
    };

    // Role selection handler
    $('#role').on('change', function() {
        const selectedRole = $(this).val();
        const infoContainer = $('#roleInfo');
        
        if (selectedRole && roleInfo[selectedRole]) {
            const info = roleInfo[selectedRole];
            const html = `
                <div class="role-content active">
                    <div class="text-center mb-3">
                        <div class="icon-circle bg-${info.color} text-white mb-2" style="width: 60px; height: 60px; margin: 0 auto;">
                            <i class="fas fa-${info.icon} fa-2x" style="line-height: 60px;"></i>
                        </div>
                        <h5 class="text-${info.color}">${info.title}</h5>
                        <p class="text-muted small">${info.description}</p>
                    </div>
                    <h6 class="font-weight-bold mb-2">Hak Akses:</h6>
                    <ul class="list-unstyled">
                        ${info.permissions.map(permission => `
                            <li class="mb-1">
                                <i class="fas fa-check text-success mr-2"></i>
                                <small>${permission}</small>
                            </li>
                        `).join('')}
                    </ul>
                </div>
            `;
            infoContainer.html(html);
        } else {
            infoContainer.html(`
                <div class="text-center text-muted">
                    <i class="fas fa-arrow-left fa-2x mb-2"></i>
                    <p>Pilih role untuk melihat informasi</p>
                </div>
            `);
        }
    });

    // Password visibility toggle
    $('#togglePassword').on('click', function() {
        const passwordField = $('#password');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('#togglePasswordConfirmation').on('click', function() {
        const passwordField = $('#password_confirmation');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Password validation
    $('#password, #password_confirmation').on('keyup', function() {
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();
        
        // Check if passwords match
        if (confirmation && password !== confirmation) {
            $('#password_confirmation').addClass('is-invalid');
            $('#password_confirmation').siblings('.invalid-feedback').remove();
            $('#password_confirmation').after('<div class="invalid-feedback">Password tidak cocok</div>');
        } else {
            $('#password_confirmation').removeClass('is-invalid');
            $('#password_confirmation').siblings('.invalid-feedback').remove();
        }
    });

    // Form validation
    $('#userForm').on('submit', function(e) {
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();
        
        if (password !== confirmation) {
            e.preventDefault();
            $('#password_confirmation').focus();
            alert('Password dan konfirmasi password tidak cocok!');
            return false;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            $('#password').focus();
            alert('Password harus minimal 8 karakter!');
            return false;
        }
    });
});
</script>
@endpush
