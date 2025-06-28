@extends('layouts.app')

@section('title', 'Detail Mata Pelajaran')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Mata Pelajaran</h1>
        <div class="d-flex">
            <a href="{{ route('subjects.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @can('update', $subject)
            <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-warning mr-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            @endcan
            @can('delete', $subject)
            <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
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
        <!-- Subject Details Card -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Mata Pelajaran</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Kode Mata Pelajaran:</label>
                                <p class="form-control-plaintext">{{ $subject->code }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Mata Pelajaran:</label>
                                <p class="form-control-plaintext">{{ $subject->name }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">SKS:</label>
                                <p class="form-control-plaintext">{{ $subject->credits }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Semester:</label>
                                <p class="form-control-plaintext">{{ $subject->semester }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Deskripsi:</label>
                        <p class="form-control-plaintext">{{ $subject->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Dibuat:</label>
                                <p class="form-control-plaintext">{{ $subject->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Diupdate:</label>
                                <p class="form-control-plaintext">{{ $subject->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="mb-3">
                            <span class="text-primary font-weight-bold h4">{{ $subject->scores->count() }}</span>
                            <p class="text-muted mb-0">Total Nilai</p>
                        </div>
                        @if($subject->scores->count() > 0)
                        <div class="mb-3">
                            <span class="text-success font-weight-bold h4">{{ number_format($subject->scores->avg('score'), 2) }}</span>
                            <p class="text-muted mb-0">Rata-rata Nilai</p>
                        </div>
                        <div class="mb-3">
                            <span class="text-info font-weight-bold h4">{{ $subject->scores->max('score') }}</span>
                            <p class="text-muted mb-0">Nilai Tertinggi</p>
                        </div>
                        <div class="mb-3">
                            <span class="text-warning font-weight-bold h4">{{ $subject->scores->min('score') }}</span>
                            <p class="text-muted mb-0">Nilai Terendah</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scores Table -->
    @if($subject->scores->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Nilai Mahasiswa</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="scoresTable">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Nilai</th>
                            <th>Grade</th>
                            <th>Tanggal Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subject->scores as $index => $score)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $score->student->nim }}</td>
                            <td>{{ $score->student->name }}</td>
                            <td>
                                <span class="badge badge-{{ $score->score >= 80 ? 'success' : ($score->score >= 70 ? 'warning' : ($score->score >= 60 ? 'info' : 'danger')) }}">
                                    {{ $score->score }}
                                </span>
                            </td>                            <td>
                                <span class="badge badge-{{ $score->grade_color }}">
                                    {{ $score->grade }}
                                </span>
                            </td>
                            <td>{{ $score->created_at->format('d M Y') }}</td>
                            <td>
                                @can('update', $score)
                                <a href="{{ route('scores.edit', $score) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('view', $score)
                                <a href="{{ route('scores.show', $score) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#scoresTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 10,
        "order": [[ 3, "desc" ]] // Sort by score descending
    });
});
</script>
@endpush
@endsection
