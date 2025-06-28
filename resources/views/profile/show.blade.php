@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profil</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
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
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="joined_date" class="form-label">Bergabung Sejak</label>
                                <input type="text" class="form-control" id="joined_date" 
                                       value="{{ auth()->user()->created_at->format('d M Y') }}" readonly>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Picture & Stats -->
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

            <!-- Activity Stats -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Aktivitas</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="row">
                            @if(auth()->user()->role === 'dosen')
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="font-weight-bold text-primary mb-1" id="myScoresCount">-</h4>
                                    <span class="text-muted small">Nilai Diinput</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="font-weight-bold text-success mb-1" id="myValidatedScores">-</h4>
                                <span class="text-muted small">Tervalidasi</span>
                            </div>
                            @else
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="font-weight-bold text-info mb-1" id="myNotifications">-</h4>
                                    <span class="text-muted small">Notifikasi</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="font-weight-bold text-warning mb-1" id="myPendingTasks">-</h4>
                                <span class="text-muted small">Tugas Pending</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password -->
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadProfileStats();
    
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

function loadProfileStats() {
    fetch('/api/profile/stats')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const stats = data.data;
                
                // Update stats based on user role
                @if(auth()->user()->role === 'dosen')
                if (document.getElementById('myScoresCount')) {
                    document.getElementById('myScoresCount').textContent = stats.my_scores || 0;
                }
                if (document.getElementById('myValidatedScores')) {
                    document.getElementById('myValidatedScores').textContent = stats.my_validated_scores || 0;
                }
                @else
                if (document.getElementById('myNotifications')) {
                    document.getElementById('myNotifications').textContent = stats.my_notifications || 0;
                }
                if (document.getElementById('myPendingTasks')) {
                    document.getElementById('myPendingTasks').textContent = stats.my_pending_tasks || 0;
                }
                @endif
            }
        })
        .catch(error => {
            console.error('Error loading profile stats:', error);
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
