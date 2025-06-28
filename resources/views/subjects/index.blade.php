@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-book text-primary"></i>
            Data Mata Pelajaran
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Mata Pelajaran</li>
            </ol>
        </nav>
    </div>

    <!-- Action Buttons -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @can('create', App\Models\Subject::class)
                    <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Mata Pelajaran
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="card shadow">
        <div class="card-body">
            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('subjects.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" 
                               value="{{ $search ?? '' }}" placeholder="Cari kode atau nama mata pelajaran...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="sort_by">
                            <option value="code" {{ ($sortBy ?? '') == 'code' ? 'selected' : '' }}>Urutkan: Kode</option>
                            <option value="name" {{ ($sortBy ?? '') == 'name' ? 'selected' : '' }}>Urutkan: Nama</option>
                            <option value="created_at" {{ ($sortBy ?? '') == 'created_at' ? 'selected' : '' }}>Urutkan: Tanggal</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" name="sort_order">
                            <option value="asc" {{ ($sortOrder ?? '') == 'asc' ? 'selected' : '' }}>A-Z</option>
                            <option value="desc" {{ ($sortOrder ?? '') == 'desc' ? 'selected' : '' }}>Z-A</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Kode</th>
                            <th width="30%">Nama Mata Pelajaran</th>
                            <th width="10%">SKS</th>
                            <th width="10%">Semester</th>
                            <th width="15%">Jumlah Nilai</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $index => $subject)
                        <tr>
                            <td>{{ $subjects->firstItem() + $index }}</td>
                            <td><strong>{{ $subject->code }}</strong></td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ $subject->credits ?? '-' }}</td>
                            <td>{{ $subject->semester ?? '-' }}</td>
                            <td>{{ $subject->scores_count }} nilai</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('view', $subject)
                                    <a href="{{ route('subjects.show', $subject) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('update', $subject)
                                    <a href="{{ route('subjects.edit', $subject) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    
                                    @can('delete', $subject)
                                    <form action="{{ route('subjects.destroy', $subject) }}" 
                                          method="POST" style="display: inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted"></i>
                                <p class="text-muted mt-2">Tidak ada data mata pelajaran</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($subjects->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <span class="text-muted">
                        Menampilkan {{ $subjects->firstItem() ?? 0 }} - {{ $subjects->lastItem() ?? 0 }} 
                        dari {{ $subjects->total() }} data
                    </span>
                </div>
                <div>
                    {{ $subjects->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show success/error messages
@if(session('success'))
    alert('{{ session("success") }}');
@endif

@if(session('error'))
    alert('{{ session("error") }}');
@endif
</script>
@endpush
