@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Profil</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">Profil</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Profile Information Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Informasi Profil</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" class="form-control" id="role" 
                                       value="{{ ucfirst(auth()->user()->role) }}" readonly>
                                <small class="text-muted">Role tidak dapat diubah</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="joined_date" class="form-label">Bergabung Sejak</label>
                                <input type="text" class="form-control" id="joined_date" 
                                       value="{{ auth()->user()->created_at->format('d M Y') }}" readonly>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Picture & Info -->
        <div class="col-lg-4">
            <!-- Profile Picture -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Foto Profil</h6>
                </div>
                <div class="card-body text-center">
                    <div class="profile-picture mb-3">
                        <div class="avatar-large">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <h5 class="card-title">{{ auth()->user()->name }}</h5>
                    <p class="card-text text-muted">{{ ucfirst(auth()->user()->role) }}</p>
                    <button class="btn btn-sm btn-outline-primary" onclick="alert('Fitur upload foto belum tersedia')">
                        <i class="fas fa-camera mr-2"></i>Ganti Foto
                    </button>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status Email:</strong>
                        @if(auth()->user()->email_verified_at)
                            <span class="badge bg-success ml-2">Terverifikasi</span>
                        @else
                            <span class="badge bg-warning ml-2">Belum Verifikasi</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>Bergabung:</strong>
                        <div class="text-muted">{{ auth()->user()->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Terakhir Update:</strong>
                        <div class="text-muted">{{ auth()->user()->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Section -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}" id="passwordForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key mr-2"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="col-lg-4">
            <div class="card shadow border-danger">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">Zona Berbahaya</h6>
                </div>
                <div class="card-body">
                    <h6 class="text-danger">Hapus Akun</h6>
                    <p class="text-muted small">
                        Setelah akun dihapus, semua data akan hilang permanen. 
                        Pastikan Anda sudah mengunduh data yang diperlukan.
                    </p>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDeleteAccount()">
                        <i class="fas fa-trash me-1"></i>Hapus Akun
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-danger">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Penghapusan Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                </div>
                <p>Untuk mengkonfirmasi penghapusan akun, masukkan password Anda:</p>
                <form id="deleteAccountForm" method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="delete_password" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="deleteAccount()">
                    <i class="fas fa-trash mr-2"></i>Ya, Hapus Akun Saya
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 600;
        margin: 0 auto;
    }
    
    .profile-picture {
        position: relative;
    }
    
    .card-title {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .border-danger {
        border-color: #dc3545 !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle profile form submission
    document.getElementById('profileForm').addEventListener('submit', function(e) {
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
                showAlert('success', 'Profil berhasil diperbarui!');
                // Redirect to profile show page after success
                setTimeout(() => {
                    window.location.href = '{{ route("profile.show") }}';
                }, 1500);
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan');
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
    
    // Handle password form submission
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;
        
        if (password !== confirmation) {
            showAlert('error', 'Konfirmasi password tidak sesuai');
            return;
        }
        
        const formData = new FormData(this);
        const button = this.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengubah...';
        
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
                showAlert('success', 'Password berhasil diubah!');
                this.reset();
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan');
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
});

function confirmDeleteAccount() {
    const modal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
    modal.show();
}

function deleteAccount() {
    const form = document.getElementById('deleteAccountForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Akun berhasil dihapus. Anda akan dialihkan...');
            setTimeout(() => {
                window.location.href = '/';
            }, 2000);
        } else {
            showAlert('error', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan sistem');
    });
}

function showAlert(type, message) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at the top of the container
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endpush
