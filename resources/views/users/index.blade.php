@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users mr-2"></i>
            Manajemen Pengguna
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Pengguna</li>
            </ol>
        </nav>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pengguna
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Admin
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->where('role', 'admin')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Dosen
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->where('role', 'dosen')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Staff
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->where('role', 'staff')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table mr-2"></i>
                    Daftar Pengguna
                </h6>
                @can('create', App\Models\User::class)
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>
                    Tambah Pengguna
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filter Form -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">Cari Pengguna</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search"
                                   name="search" 
                                   value="{{ $search ?? '' }}"
                                   placeholder="Nama atau email...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ ($role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="dosen" {{ ($role ?? '') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="staff" {{ ($role ?? '') == 'staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sort_by">Urutkan</label>
                            <select class="form-control" name="sort_by">
                                <option value="created_at" {{ ($sortBy ?? '') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                                <option value="name" {{ ($sortBy ?? '') == 'name' ? 'selected' : '' }}>Nama</option>
                                <option value="email" {{ ($sortBy ?? '') == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="role" {{ ($sortBy ?? '') == 'role' ? 'selected' : '' }}>Role</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="sort_order">Urutan</label>
                            <select class="form-control" name="sort_order">
                                <option value="asc" {{ ($sortOrder ?? '') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                <option value="desc" {{ ($sortOrder ?? '') == 'desc' ? 'selected' : '' }}>Z-A</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Nama</th>
                            <th width="25%">Email</th>
                            <th width="15%">Role</th>
                            <th width="15%">Tanggal Daftar</th>
                            <th width="10%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle mr-2">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="rounded-circle" 
                                                 width="32" 
                                                 height="32">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px; font-size: 14px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === auth()->id())
                                            <small class="text-muted">(Anda)</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $roleConfig = [
                                        'admin' => ['class' => 'danger', 'icon' => 'user-shield', 'text' => 'Admin'],
                                        'dosen' => ['class' => 'info', 'icon' => 'chalkboard-teacher', 'text' => 'Dosen'],
                                        'staff' => ['class' => 'warning', 'icon' => 'user-tie', 'text' => 'Staff'],
                                    ];
                                    $config = $roleConfig[$user->role] ?? ['class' => 'secondary', 'icon' => 'user', 'text' => ucfirst($user->role)];
                                @endphp
                                <span class="badge badge-{{ $config['class'] }}">
                                    <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                    {{ $config['text'] }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock me-1"></i>
                                        Belum Verifikasi
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @can('view', $user)
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan

                                    @can('update', $user)
                                    <a href="{{ route('users.edit', $user) }}" 
                                       class="btn btn-warning btn-sm" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan

                                    @can('delete', $user)
                                    @if($user->id !== auth()->id())
                                    <button type="button" 
                                            class="btn btn-danger btn-sm" 
                                            title="Hapus"
                                            onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada pengguna ditemukan</h5>
                                    <p class="text-muted">
                                        @if($search || $role)
                                            Coba ubah kriteria pencarian atau 
                                            <a href="{{ route('users.index') }}">lihat semua pengguna</a>
                                        @else
                                            Belum ada pengguna yang terdaftar.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <p class="text-muted small mb-0">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} 
                        dari {{ $users->total() }} pengguna
                    </p>
                </div>
                <div>
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus pengguna <strong id="userName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Tindakan ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.empty-state {
    padding: 2rem;
}
.avatar-circle {
    flex-shrink: 0;
}
</style>
@endpush

@push('scripts')
<script>
function deleteUser(userId, userName) {
    $('#userName').text(userName);
    $('#deleteForm').attr('action', `/users/${userId}`);
    $('#deleteModal').modal('show');
}

// Auto-submit form on Enter key in search input
$('#search').on('keypress', function(e) {
    if (e.which === 13) {
        $(this).closest('form').submit();
    }
});

// Clear search
function clearSearch() {
    window.location.href = '{{ route("users.index") }}';
}
</script>
@endpush
