@extends('layouts.app')

@section('title', 'Buat Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Notifikasi Baru</h1>
        <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Buat Notifikasi</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('notifications.store') }}" method="POST" id="notificationForm">
                        @csrf

                        <div class="form-group">
                            <label for="title" class="form-label">Judul Notifikasi <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   placeholder="Masukkan judul notifikasi"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      placeholder="Masukkan pesan notifikasi"
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maksimal 1000 karakter</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-label">Tipe Notifikasi <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>
                                            Info - Informasi umum
                                        </option>
                                        <option value="success" {{ old('type') == 'success' ? 'selected' : '' }}>
                                            Success - Pemberitahuan sukses
                                        </option>
                                        <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>
                                            Warning - Peringatan
                                        </option>
                                        <option value="error" {{ old('type') == 'error' ? 'selected' : '' }}>
                                            Error - Kesalahan atau masalah
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Preview Tipe</label>
                                    <div class="border rounded p-3 bg-light" id="typePreview">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-bell text-muted mr-2" id="typeIcon"></i>
                                            <span class="badge badge-secondary" id="typeBadge">Pilih Tipe</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="recipients" class="form-label">Penerima <span class="text-danger">*</span></label>
                            <select class="form-control @error('recipients') is-invalid @enderror" 
                                    id="recipients" 
                                    name="recipients[]" 
                                    multiple
                                    required>
                                <optgroup label="Berdasarkan Role">
                                    <option value="role:admin" {{ in_array('role:admin', old('recipients', [])) ? 'selected' : '' }}>
                                        Semua Admin
                                    </option>
                                    <option value="role:teacher" {{ in_array('role:teacher', old('recipients', [])) ? 'selected' : '' }}>
                                        Semua Teacher
                                    </option>
                                    <option value="role:student" {{ in_array('role:student', old('recipients', [])) ? 'selected' : '' }}>
                                        Semua Student
                                    </option>
                                    <option value="all" {{ in_array('all', old('recipients', [])) ? 'selected' : '' }}>
                                        Semua Pengguna
                                    </option>
                                </optgroup>
                                <optgroup label="Pengguna Spesifik">
                                    @foreach($users as $user)
                                    <option value="user:{{ $user->id }}" {{ in_array('user:' . $user->id, old('recipients', [])) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                                    </option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('recipients')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Pilih penerima notifikasi. Anda dapat memilih berdasarkan role atau pengguna spesifik.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="url" class="form-label">URL Terkait (Opsional)</label>
                            <input type="url" 
                                   class="form-control @error('url') is-invalid @enderror" 
                                   id="url" 
                                   name="url" 
                                   value="{{ old('url') }}" 
                                   placeholder="https://example.com">
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Link yang terkait dengan notifikasi ini (opsional)
                            </small>
                        </div>

                        <!-- Advanced Options -->
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <button class="btn btn-link text-decoration-none p-0" type="button" 
                                            data-toggle="collapse" data-target="#advancedOptions">
                                        <i class="fas fa-cog"></i> Opsi Lanjutan
                                    </button>
                                </h6>
                            </div>
                            <div class="collapse" id="advancedOptions">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="data" class="form-label">Data Tambahan (JSON)</label>
                                        <textarea class="form-control @error('data') is-invalid @enderror" 
                                                  id="data" 
                                                  name="data" 
                                                  rows="3" 
                                                  placeholder='{"key": "value", "another_key": "another_value"}'>{{ old('data') }}</textarea>
                                        @error('data')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Data tambahan dalam format JSON (opsional)
                                        </small>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="send_email" name="send_email" value="1"
                                               {{ old('send_email') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="send_email">
                                            Kirim juga via email
                                        </label>
                                        <small class="form-text text-muted">
                                            Notifikasi akan dikirim melalui email selain ditampilkan di sistem
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Kirim Notifikasi
                                    </button>
                                    <button type="button" class="btn btn-info mr-2" id="previewBtn">
                                        <i class="fas fa-eye"></i> Preview
                                    </button>
                                    <a href="{{ route('notifications.index') }}" class="btn btn-secondary mr-2">
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

        <!-- Preview Panel -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-eye"></i> Preview Notifikasi
                    </h6>
                </div>
                <div class="card-body">
                    <div id="notificationPreview" class="border rounded p-3 bg-light">
                        <div class="d-flex align-items-start">
                            <div class="mr-3">
                                <i class="fas fa-bell text-muted fa-lg" id="previewIcon"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1" id="previewTitle">Judul notifikasi akan muncul di sini...</h6>
                                <p class="mb-1 text-muted" id="previewMessage">Pesan notifikasi akan muncul di sini...</p>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> Sekarang
                                    <span class="badge badge-secondary mr-2" id="previewBadge">-</span>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6 class="font-weight-bold">Informasi:</h6>
                        <ul class="list-unstyled small text-muted">
                            <li><i class="fas fa-users"></i> Penerima: <span id="recipientCount">0</span> pengguna</li>
                            <li><i class="fas fa-envelope"></i> Email: <span id="emailStatus">Tidak</span></li>
                            <li><i class="fas fa-link"></i> URL: <span id="urlStatus">Tidak ada</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for better UX
    if (typeof $.fn.select2 !== 'undefined') {
        $('#recipients').select2({
            theme: 'bootstrap4',
            placeholder: 'Pilih penerima notifikasi',
            allowClear: true
        });
    }
    
    // Type change handler
    $('#type').on('change', function() {
        updateTypePreview();
        updateNotificationPreview();
    });
    
    // Real-time preview updates
    $('#title, #message').on('input', updateNotificationPreview);
    $('#recipients').on('change', updateRecipientCount);
    $('#url').on('input', updateUrlStatus);
    $('#send_email').on('change', updateEmailStatus);
    
    // Preview button
    $('#previewBtn').on('click', function() {
        $('#notificationPreview').addClass('border-primary');
        setTimeout(() => {
            $('#notificationPreview').removeClass('border-primary');
        }, 1000);
    });
    
    // Form validation
    $('#notificationForm').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val() || ($(this).is('select[multiple]') && $(this).val().length === 0)) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Validate JSON data if provided
        let jsonData = $('#data').val();
        if (jsonData) {
            try {
                JSON.parse(jsonData);
            } catch (e) {
                isValid = false;
                $('#data').addClass('is-invalid');
                $('#data').siblings('.invalid-feedback').text('Format JSON tidak valid');
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon periksa kembali input Anda');
        }
    });
    
    // Remove invalid class on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Character counter for message
    $('#message').on('input', function() {
        let length = $(this).val().length;
        let maxLength = 1000;
        
        if (length > maxLength) {
            $(this).val($(this).val().substring(0, maxLength));
            length = maxLength;
        }
        
        let counter = $(this).siblings('.form-text');
        counter.text(`${length}/${maxLength} karakter`);
        
        if (length > maxLength * 0.9) {
            counter.addClass('text-warning').removeClass('text-muted');
        } else {
            counter.addClass('text-muted').removeClass('text-warning');
        }
    });
    
    // Initialize preview
    updateNotificationPreview();
    updateRecipientCount();
    updateEmailStatus();
    updateUrlStatus();
});

function updateTypePreview() {
    const type = $('#type').val();
    const typeIcon = $('#typeIcon');
    const typeBadge = $('#typeBadge');
    
    switch(type) {
        case 'info':
            typeIcon.removeClass().addClass('fas fa-info-circle text-info mr-2');
            typeBadge.removeClass().addClass('badge badge-info').text('Info');
            break;
        case 'success':
            typeIcon.removeClass().addClass('fas fa-check-circle text-success mr-2');
            typeBadge.removeClass().addClass('badge badge-success').text('Success');
            break;
        case 'warning':
            typeIcon.removeClass().addClass('fas fa-exclamation-triangle text-warning mr-2');
            typeBadge.removeClass().addClass('badge badge-warning').text('Warning');
            break;
        case 'error':
            typeIcon.removeClass().addClass('fas fa-times-circle text-danger mr-2');
            typeBadge.removeClass().addClass('badge badge-danger').text('Error');
            break;
        default:
            typeIcon.removeClass().addClass('fas fa-bell text-muted mr-2');
            typeBadge.removeClass().addClass('badge badge-secondary').text('Pilih Tipe');
    }
}

function updateNotificationPreview() {
    const title = $('#title').val() || 'Judul notifikasi akan muncul di sini...';
    const message = $('#message').val() || 'Pesan notifikasi akan muncul di sini...';
    const type = $('#type').val();
    
    $('#previewTitle').text(title);
    $('#previewMessage').text(message.substring(0, 100) + (message.length > 100 ? '...' : ''));
    
    const previewIcon = $('#previewIcon');
    const previewBadge = $('#previewBadge');
    
    switch(type) {
        case 'info':
            previewIcon.removeClass().addClass('fas fa-info-circle text-info fa-lg');
            previewBadge.removeClass().addClass('badge badge-info').text('Info');
            break;
        case 'success':
            previewIcon.removeClass().addClass('fas fa-check-circle text-success fa-lg');
            previewBadge.removeClass().addClass('badge badge-success').text('Success');
            break;
        case 'warning':
            previewIcon.removeClass().addClass('fas fa-exclamation-triangle text-warning fa-lg');
            previewBadge.removeClass().addClass('badge badge-warning').text('Warning');
            break;
        case 'error':
            previewIcon.removeClass().addClass('fas fa-times-circle text-danger fa-lg');
            previewBadge.removeClass().addClass('badge badge-danger').text('Error');
            break;
        default:
            previewIcon.removeClass().addClass('fas fa-bell text-muted fa-lg');
            previewBadge.removeClass().addClass('badge badge-secondary').text('-');
    }
}

function updateRecipientCount() {
    const recipients = $('#recipients').val() || [];
    let count = 0;
    
    recipients.forEach(recipient => {
        if (recipient === 'all') {
            count = {{ $totalUsers }}; // Total users count
        } else if (recipient.startsWith('role:')) {
            // You would need to pass role counts from controller
            const role = recipient.split(':')[1];
            switch(role) {
                case 'admin':
                    count += {{ $adminCount ?? 0 }};
                    break;
                case 'teacher':
                    count += {{ $teacherCount ?? 0 }};
                    break;
                case 'student':
                    count += {{ $studentCount ?? 0 }};
                    break;
            }
        } else if (recipient.startsWith('user:')) {
            count += 1;
        }
    });
    
    $('#recipientCount').text(count);
}

function updateEmailStatus() {
    const sendEmail = $('#send_email').is(':checked');
    $('#emailStatus').text(sendEmail ? 'Ya' : 'Tidak');
}

function updateUrlStatus() {
    const url = $('#url').val();
    $('#urlStatus').text(url ? 'Ada' : 'Tidak ada');
}
</script>
@endpush
@endsection
