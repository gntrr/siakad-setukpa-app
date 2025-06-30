@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Siswa</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Siswa</li>
            </ol>
        </nav>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            @can('create', App\Models\Student::class)
                            <a href="{{ route('students.create') }}" class="btn btn-primary mr-2">
                                <i class="fas fa-plus mr-2"></i>Tambah Siswa
                            </a>
                            @endcan
                              @can('viewAny', App\Models\Student::class)
                            <a href="{{ route('reports.export.students') }}" class="btn btn-success">
                                <i class="fas fa-download mr-2"></i>Export Excel
                            </a>
                            @endcan
                        </div>
                          <!-- Search & Filter -->
                        <div class="d-flex gap-2">
                            <!-- Server-side search form is below -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    <!-- Students Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Search & Filter Form -->
                    <form method="GET" action="{{ route('students.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" 
                                       value="{{ $search ?? '' }}" placeholder="Cari nama atau NIM...">
                            </div>
                            <div class="col-md-3">                            <select class="form-control" name="gender">
                                <option value="">Semua Gender</option>
                                <option value="Laki-laki" {{ ($gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ ($gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="sort_by">
                                    <option value="created_at" {{ ($sortBy ?? '') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                                    <option value="name" {{ ($sortBy ?? '') == 'name' ? 'selected' : '' }}>Nama</option>
                                    <option value="student_number" {{ ($sortBy ?? '') == 'student_number' ? 'selected' : '' }}>NIM</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="sort_order">
                                    <option value="asc" {{ ($sortOrder ?? '') == 'asc' ? 'selected' : '' }}>A-Z</option>
                                    <option value="desc" {{ ($sortOrder ?? '') == 'desc' ? 'selected' : '' }}>Z-A</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Nomor Induk</th>
                                    <th width="25%">Nama Lengkap</th>
                                    <th width="20%">Email</th>
                                    <th width="10%">Gender</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                <tr>
                                    <td>{{ $students->firstItem() + $index }}</td>
                                    <td><strong>{{ $student->student_number }}</strong></td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @if($student->gender == 'Laki-laki')
                                            <span class="badge badge-info">Laki-laki</span>
                                        @else
                                            <span class="badge badge-pink">Perempuan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($student->status == 'Aktif')
                                            <span class="badge badge-success">{{ $student->status }}</span>
                                        @elseif($student->status == 'Lulus')
                                            <span class="badge badge-primary">{{ $student->status }}</span>
                                        @elseif($student->status == 'Cuti')
                                            <span class="badge badge-warning">{{ $student->status }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $student->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $student)
                                            <a href="{{ route('students.show', $student) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('update', $student)
                                            <a href="{{ route('students.edit', $student) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('delete', $student)
                                            <form action="{{ route('students.destroy', $student) }}" 
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
                                        <p class="text-muted mt-2">Tidak ada data siswa</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="text-muted">
                                Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} 
                                dari {{ $students->total() }} data
                            </span>
                        </div>
                        <div>
                            {{ $students->appends(request()->query())->links() }}
                        </div>                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show success message if any
@if(session('success'))
    alert('{{ session("success") }}');
@endif

// Show error message if any  
@if(session('error'))
    alert('{{ session("error") }}');
@endif
</script>
@endpush

@push('styles')
<style>
    .badge-pink {
        background-color: #e91e63;
        color: white;
    }
</style>
@endpush
