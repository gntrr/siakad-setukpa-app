@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Notifikasi</h1>
        <div class="d-flex">
            @can('create', App\Models\Notification::class)
            <a href="{{ route('notifications.create') }}" class="btn btn-primary mr-2">
                <i class="fas fa-plus"></i> Buat Notifikasi
            </a>
            @endcan
            <button class="btn btn-success" onclick="markAllAsRead()">
                <i class="fas fa-check-double"></i> Tandai Semua Dibaca
            </button>
        </div>
    </div>

    <!-- Notification Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Notifikasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notifications->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
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
                                Belum Dibaca</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unreadCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell-slash fa-2x text-gray-300"></i>
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
                                Sudah Dibaca</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $readCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Notifikasi</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('notifications.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Tipe</label>
                            <select class="form-control" id="type" name="type">
                                <option value="">Semua Tipe</option>
                                <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Info</option>
                                <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Peringatan</option>
                                <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Sukses</option>
                                <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Error</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search">Cari</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Cari notifikasi...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Notifikasi</h6>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="list-group">
                    @foreach($notifications as $notification)
                    <div class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'list-group-item-warning' }}" 
                         data-notification-id="{{ $notification->id }}">
                        <div class="d-flex w-100 justify-content-between">
                            <div class="d-flex align-items-start">
                                <div class="mr-3">
                                    @switch($notification->type)
                                        @case('info')
                                            <i class="fas fa-info-circle text-info fa-lg"></i>
                                            @break
                                        @case('warning')
                                            <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                                            @break
                                        @case('success')
                                            <i class="fas fa-check-circle text-success fa-lg"></i>
                                            @break
                                        @case('error')
                                            <i class="fas fa-times-circle text-danger fa-lg"></i>
                                            @break
                                        @default
                                            <i class="fas fa-bell text-primary fa-lg"></i>
                                    @endswitch
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $notification->title }}</h6>
                                    <p class="mb-1 text-muted">{{ Str::limit($notification->message, 100) }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                        @if($notification->read_at)
                                            <span class="badge badge-success mr-2">Dibaca</span>
                                        @else
                                            <span class="badge badge-warning mr-2">Belum Dibaca</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                            data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{ route('notifications.show', $notification) }}">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                        @if(!$notification->read_at)
                                        <button class="dropdown-item" onclick="markAsRead({{ $notification->id }})">
                                            <i class="fas fa-check"></i> Tandai Dibaca
                                        </button>
                                        @else
                                        <button class="dropdown-item" onclick="markAsUnread({{ $notification->id }})">
                                            <i class="fas fa-times"></i> Tandai Belum Dibaca
                                        </button>
                                        @endif
                                        @can('delete', $notification)
                                        <div class="dropdown-divider"></div>
                                        <button class="dropdown-item text-danger" onclick="deleteNotification({{ $notification->id }})">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $notifications->firstItem() }} - {{ $notifications->lastItem() }} 
                        dari {{ $notifications->total() }} notifikasi
                    </div>
                    {{ $notifications->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">Belum ada notifikasi</h5>
                    <p class="text-muted">Notifikasi akan muncul di sini ketika ada update atau informasi penting.</p>
                    @can('create', App\Models\Notification::class)
                    <a href="{{ route('notifications.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Notifikasi Pertama
                    </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
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
            alert('Gagal menandai notifikasi sebagai dibaca');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

function markAsUnread(notificationId) {
    fetch(`/notifications/${notificationId}/unread`, {
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
            alert('Gagal menandai notifikasi sebagai belum dibaca');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

function markAllAsRead() {
    if (confirm('Tandai semua notifikasi sebagai dibaca?')) {
        fetch('/notifications/mark-all-read', {
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
                alert('Gagal menandai semua notifikasi sebagai dibaca');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function deleteNotification(notificationId) {
    if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
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
                alert('Gagal menghapus notifikasi');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

// Auto-refresh unread count every 30 seconds
setInterval(function() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            // Update notification badge in navbar
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'inline' : 'none';
            }
        })
        .catch(error => {
            console.error('Error updating notification count:', error);
        });
}, 30000);
</script>
@endpush
@endsection
