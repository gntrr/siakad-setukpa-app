@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Mata Pelajaran</h1>
        <a href="{{ route('subjects.show', $subject) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Mata Pelajaran</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('subjects.update', $subject) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="form-label">Kode Mata Pelajaran <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code', $subject->code) }}" 
                                           placeholder="Masukkan kode mata pelajaran"
                                           required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $subject->name) }}" 
                                           placeholder="Masukkan nama mata pelajaran"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="credits" class="form-label">SKS <span class="text-danger">*</span></label>
                                    <select class="form-control @error('credits') is-invalid @enderror" 
                                            id="credits" 
                                            name="credits" 
                                            required>
                                        <option value="">Pilih SKS</option>
                                        @for($i = 1; $i <= 4; $i++)
                                            <option value="{{ $i }}" {{ old('credits', $subject->credits) == $i ? 'selected' : '' }}>
                                                {{ $i }} SKS
                                            </option>
                                        @endfor
                                    </select>
                                    @error('credits')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                                    <select class="form-control @error('semester') is-invalid @enderror" 
                                            id="semester" 
                                            name="semester" 
                                            required>
                                        <option value="">Pilih Semester</option>
                                        @for($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}" {{ old('semester', $subject->semester) == $i ? 'selected' : '' }}>
                                                Semester {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('semester')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Masukkan deskripsi mata pelajaran (opsional)">{{ old('description', $subject->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maksimal 500 karakter</small>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Mata Pelajaran
                                    </button>
                                    <a href="{{ route('subjects.show', $subject) }}" class="btn btn-secondary mr-2">
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
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi');
        }
    });
    
    // Remove invalid class on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Character counter for description
    $('#description').on('input', function() {
        let length = $(this).val().length;
        let maxLength = 500;
        
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
});
</script>
@endpush
@endsection
