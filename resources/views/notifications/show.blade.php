@extends('layouts.app')

@section('title', 'Detail Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Notifikasi</h1>
        <div class="d-flex">
            <a href="{{ route('notifications.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @if(!$notification->read_at)
            <button class="btn btn-success mr-2" onclick="markAsRead({{ $notification->id }})">
                <i class="fas fa-check"></i> Tandai Dibaca
            </button>
            @endif
            @can('delete', $notification)
            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
            @endcan
        </div>
    </div>

    <div class="row">
        <!-- Notification Content -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        @switch($notification->type)
                            @case('info')
                                <i class="fas fa-info-circle text-info fa-lg mr-2"></i>
                                @break
                            @case('warning')
                                <i class="fas fa-exclamation-triangle text-warning fa-lg mr-2"></i>
                                @break
                            @case('success')
                                <i class="fas fa-check-circle text-success fa-lg mr-2"></i>
                                @break
                            @case('error')
                                <i class="fas fa-times-circle text-danger fa-lg mr-2"></i>
                                @break
                            @default
                                <i class="fas fa-bell text-primary fa-lg mr-2"></i>
                        @endswitch
                        <h6 class="m-0 font-weight-bold text-primary">{{ $notification->title }}</h6>
                    </div>
                    <div>
                        @if($notification->read_at)
                            <span class="badge badge-success">Dibaca</span>
                        @else
                            <span class="badge badge-warning">Belum Dibaca</span>
                        @endif
                        <span class="badge badge-{{ $notification->type == 'info' ? 'info' : ($notification->type == 'success' ? 'success' : ($notification->type == 'warning' ? 'warning' : 'danger')) }}">
                            {{ ucfirst($notification->type) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="notification-content">
                        <div class="mb-4">
                            <h4 class="text-gray-800 mb-3">{{ $notification->title }}</h4>
                            <div class="text-gray-700" style="line-height: 1.6; font-size: 1.1rem;">
                                {!! nl2br(e($notification->message)) !!}
                            </div>
                        </div>

                        @if($notification->data)
                        <div class="alert alert-light border">
                            <h6 class="font-weight-bold mb-3">
                                <i class="fas fa-info-circle"></i> Data Tambahan
                            </h6>
                            <div class="row">
                                @foreach($notification->data as $key => $value)
                                <div class="col-md-6 mb-2">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                    <span class="text-muted">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($notification->url)
                        <div class="mt-4">
                            <a href="{{ $notification->url }}" class="btn btn-primary">
                                <i class="fas fa-external-link-alt"></i> Lihat Detail
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <div class="row">
                        <div class="col-md-6">
                            <small>
                                <i class="fas fa-clock"></i> 
                                Dibuat: {{ $notification->created_at->format('d M Y H:i') }}
                                ({{ $notification->created_at->diffForHumans() }})
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            @if($notification->read_at)
                            <small>
                                <i class="fas fa-check-circle text-success"></i> 
                                Dibaca: {{ $notification->read_at->format('d M Y H:i') }}
                                ({{ $notification->read_at->diffForHumans() }})
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Notification Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Notifikasi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="font-weight-bold">Tipe:</label>
                        <div>
                            <span class="badge badge-{{ $notification->type == 'info' ? 'info' : ($notification->type == 'success' ? 'success' : ($notification->type == 'warning' ? 'warning' : 'danger')) }} p-2">
                                {{ ucfirst($notification->type) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="font-weight-bold">Status:</label>
                        <div>
                            @if($notification->read_at)
                                <span class="badge badge-success p-2">
                                    <i class="fas fa-check-circle"></i> Sudah Dibaca
                                </span>
                            @else
                                <span class="badge badge-warning p-2">
                                    <i class="fas fa-clock"></i> Belum Dibaca
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold">Penerima:</label>
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    <span class="text-white font-weight-bold text-sm">{{ substr($notification->user->name, 0, 2) }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-weight-bold">{{ $notification->user->name }}</div>
                                <small class="text-muted">{{ $notification->user->email }}</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="font-weight-bold">Waktu Dibuat:</label>
                        <div class="text-muted">{{ $notification->created_at->format('d M Y H:i:s') }}</div>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>

                    @if($notification->read_at)
                    <div class="mb-3">
                        <label class="font-weight-bold">Waktu Dibaca:</label>
                        <div class="text-muted">{{ $notification->read_at->format('d M Y H:i:s') }}</div>
                        <small class="text-muted">{{ $notification->read_at->diffForHumans() }}</small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body">
                    @if(!$notification->read_at)
                    <button class="btn btn-success btn-block mb-2" onclick="markAsRead({{ $notification->id }})">
                        <i class="fas fa-check"></i> Tandai Dibaca
                    </button>
                    @else
                    <button class="btn btn-warning btn-block mb-2" onclick="markAsUnread({{ $notification->id }})">
                        <i class="fas fa-times"></i> Tandai Belum Dibaca
                    </button>
                    @endif
                    
                    <a href="{{ route('notifications.index') }}" class="btn btn-secondary btn-block mb-2">
                        <i class="fas fa-list"></i> Lihat Semua Notifikasi
                    </a>
                    
                    @if($notification->url)
                    <a href="{{ $notification->url }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-external-link-alt"></i> Buka Link Terkait
                    </a>
                    @endif
                    
                    @can('delete', $notification)
                    <button class="btn btn-danger btn-block" onclick="deleteNotification({{ $notification->id }})">
                        <i class="fas fa-trash"></i> Hapus Notifikasi
                    </button>
                    @endcan
                </div>
            </div>

            <!-- Related Notifications -->
            @if($relatedNotifications && $relatedNotifications->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Notifikasi Terkait</h6>
                </div>
                <div class="card-body">
                    @foreach($relatedNotifications as $related)
                    <div class="d-flex align-items-start mb-3">
                        <div class="mr-2">
                            @switch($related->type)
                                @case('info')
                                    <i class="fas fa-info-circle text-info"></i>
                                    @break
                                @case('warning')
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    @break
                                @case('success')
                                    <i class="fas fa-check-circle text-success"></i>
                                    @break
                                @case('error')
                                    <i class="fas fa-times-circle text-danger"></i>
                                    @break
                                @default
                                    <i class="fas fa-bell text-primary"></i>
                            @endswitch
                        </div>
                        <div class="flex-grow-1">
                            <a href="{{ route('notifications.show', $related) }}" class="text-decoration-none">
                                <div class="font-weight-bold text-sm">{{ Str::limit($related->title, 30) }}</div>
                                <small class="text-muted">{{ $related->created_at->diffForHumans() }}</small>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
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
                window.location.href = '/notifications';
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

// Auto-mark as read when viewing
document.addEventListener('DOMContentLoaded', function() {
    @if(!$notification->read_at)
    setTimeout(function() {
        markAsRead({{ $notification->id }});
    }, 3000); // Mark as read after 3 seconds of viewing
    @endif
});
</script>
@endpush

@push('styles')
<style>
.notification-content {
    font-size: 1rem;
    line-height: 1.6;
}

.notification-content h4 {
    border-bottom: 2px solid #e3e6f0;
    padding-bottom: 10px;
}

.badge {
    font-size: 0.75rem;
}

.card-footer {
    background-color: #f8f9fc;
    border-top: 1px solid #e3e6f0;
}
</style>
@endpush
@endsection
