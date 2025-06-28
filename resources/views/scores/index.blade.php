@extends('layouts.app')

@section('title', 'Data Nilai')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800">Data Nilai</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Nilai</li>
                    </ol>
                </nav>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            @can('create', App\Models\Score::class)
                            <a href="{{ route('scores.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Nilai
                            </a>
                            @endcan
                            
                            @can('validate', App\Models\Score::class)
                            <a href="{{ route('scores.pending') }}" class="btn btn-warning">
                                <i class="fas fa-clock"></i> Validasi Nilai
                                @if($scores->where('validated', false)->count() > 0)
                                <span class="badge badge-light">{{ $scores->where('validated', false)->count() }}</span>
                                @endif
                            </a>
                            @endcan
                        </div>
                    </div>

                    <!-- Filter and Search -->
                    <form method="GET" action="{{ route('scores.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="search" 
                                       value="{{ $search ?? '' }}" placeholder="Cari mahasiswa atau mata kuliah...">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="student_id">
                                    <option value="">Semua Mahasiswa</option>
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ ($studentId ?? '') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="subject_id">
                                    <option value="">Semua Mata Kuliah</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ ($subjectId ?? '') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="validated">
                                    <option value="">Semua Status</option>
                                    <option value="true" {{ ($validated ?? '') === 'true' ? 'selected' : '' }}>Tervalidasi</option>
                                    <option value="false" {{ ($validated ?? '') === 'false' ? 'selected' : '' }}>Belum Tervalidasi</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" name="sort_by">
                                    <option value="created_at" {{ ($sortBy ?? '') == 'created_at' ? 'selected' : '' }}>Tanggal</option>
                                    <option value="score" {{ ($sortBy ?? '') == 'score' ? 'selected' : '' }}>Nilai</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen</th>
                                    <th>Nilai</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($scores as $index => $score)
                                <tr>
                                    <td>{{ $scores->firstItem() + $index }}</td>
                                    <td>{{ $score->student->name }}</td>
                                    <td>{{ $score->student->student_number }}</td>
                                    <td>{{ $score->subject->name }} ({{ $score->subject->code }})</td>
                                    <td>{{ $score->teacher->name }}</td>
                                    <td>{{ $score->score }}</td>                                    <td>
                                        <span class="badge badge-{{ $score->grade_color }}">
                                            {{ $score->grade }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($score->validated)
                                        <span class="badge badge-success">Tervalidasi</span>
                                        @else
                                        <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $score->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view', $score)
                                            <a href="{{ route('scores.show', $score) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('update', $score)
                                            <a href="{{ route('scores.edit', $score) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('delete', $score)
                                            <form action="{{ route('scores.destroy', $score) }}" 
                                                  method="POST" style="display: inline;"
                                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                            
                                            @can('validate', $score)
                                            @if(!$score->validated)
                                            <form action="{{ route('scores.validate', $score) }}" 
                                                  method="POST" style="display: inline;"
                                                  onsubmit="return confirm('Yakin ingin memvalidasi nilai ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted"></i>
                                        <p class="text-muted mt-2">Tidak ada data nilai</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($scores->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span>Menampilkan {{ $scores->firstItem() ?? 0 }} - {{ $scores->lastItem() ?? 0 }} dari {{ $scores->total() }} data</span>
                        </div>
                        <div>
                            {{ $scores->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show success/error messages
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session("error") }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endpush
