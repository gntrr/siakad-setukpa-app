@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Siswa</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Data Siswa</a></li>
                <li class="breadcrumb-item active">{{ $student->name }}</li>
            </ol>
        </nav>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            @can('update', $student)
                            <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">
                                <i class="fas fa-edit mr-2"></i>Edit Data
                            </a>
                            @endcan
                            @can('viewAny', App\Models\Score::class)
                            <a href="{{ route('students.report', $student) }}" class="btn btn-info">
                                <i class="fas fa-file-alt mr-2"></i>Laporan Nilai
                            </a>
                            @endcan
                        </div>
                        <div class="d-flex gap-2">
                            @can('delete', $student)
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash mr-2"></i>Hapus
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Student Information Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user mr-2"></i>Informasi Siswa
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle bg-primary text-white mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                        <h5 class="font-weight-bold text-gray-800">{{ $student->name }}</h5>
                        <p class="text-muted">{{ $student->student_number }}</p>
                    </div>

                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-gray-600">Nomor Siswa:</td>
                                <td>{{ $student->student_number }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-600">Nama Lengkap:</td>
                                <td>{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-600">Jenis Kelamin:</td>
                                <td>
                                    @if($student->gender === 'L')
                                        <span class="badge badge-info">Laki-laki</span>
                                    @else
                                        <span class="badge badge-pink">Perempuan</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-600">Tanggal Lahir:</td>
                                <td>
                                    @if($student->birth_date)
                                        {{ \Carbon\Carbon::parse($student->birth_date)->format('d F Y') }}
                                        <small class="text-muted">({{ \Carbon\Carbon::parse($student->birth_date)->age }} tahun)</small>
                                    @else
                                        <span class="text-muted">Belum diisi</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-600">Terdaftar:</td>
                                <td>{{ $student->created_at->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-gray-600">Terakhir Update:</td>
                                <td>{{ $student->updated_at->format('d F Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar mr-2"></i>Statistik Nilai
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $scores = $student->scores;
                        $totalScores = $scores->count();
                        $averageScore = $totalScores > 0 ? $scores->avg('score') : 0;
                        $validatedScores = $scores->where('validated', true)->count();
                        $pendingScores = $scores->where('validated', false)->count();
                    @endphp

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right pb-2 mb-2">
                                <h3 class="text-primary font-weight-bold">{{ $totalScores }}</h3>
                                <p class="text-muted mb-0 small">Total Mata Kuliah</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="pb-2 mb-2">
                                <h3 class="text-success font-weight-bold">{{ number_format($averageScore, 1) }}</h3>
                                <p class="text-muted mb-0 small">Rata-rata Nilai</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <h4 class="text-success font-weight-bold">{{ $validatedScores }}</h4>
                                <p class="text-muted mb-0 small">Nilai Tervalidasi</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning font-weight-bold">{{ $pendingScores }}</h4>
                            <p class="text-muted mb-0 small">Menunggu Validasi</p>
                        </div>
                    </div>

                    @if($averageScore > 0)
                    <hr>
                    <div class="text-center">
                        <h6 class="font-weight-bold text-gray-600">Grade Rata-rata</h6>
                        @php
                            $grade = '';
                            $gradeColor = '';
                            if ($averageScore >= 85) { $grade = 'A'; $gradeColor = 'success'; }
                            elseif ($averageScore >= 80) { $grade = 'A-'; $gradeColor = 'success'; }
                            elseif ($averageScore >= 75) { $grade = 'B+'; $gradeColor = 'warning'; }
                            elseif ($averageScore >= 70) { $grade = 'B'; $gradeColor = 'warning'; }
                            elseif ($averageScore >= 65) { $grade = 'B-'; $gradeColor = 'warning'; }
                            elseif ($averageScore >= 60) { $grade = 'C+'; $gradeColor = 'info'; }
                            elseif ($averageScore >= 55) { $grade = 'C'; $gradeColor = 'info'; }
                            elseif ($averageScore >= 50) { $grade = 'C-'; $gradeColor = 'info'; }
                            elseif ($averageScore >= 45) { $grade = 'D+'; $gradeColor = 'danger'; }
                            elseif ($averageScore >= 40) { $grade = 'D'; $gradeColor = 'danger'; }
                            else { $grade = 'E'; $gradeColor = 'danger'; }
                        @endphp
                        <span class="badge badge-{{ $gradeColor }} badge-lg" style="font-size: 1.2rem; padding: 0.5rem 1rem;">{{ $grade }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Scores List -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-list mr-2"></i>Daftar Nilai
                    </h6>
                    @can('create', App\Models\Score::class)
                    <a href="{{ route('scores.create', ['student_id' => $student->id]) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus mr-1"></i>Tambah Nilai
                    </a>
                    @endcan
                </div>
                <div class="card-body">
                    @if($student->scores->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mata Kuliah</th>
                                        <th>Kode</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Grade</th>
                                        <th>Dosen</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Tanggal</th>
                                        @canany(['update', 'delete'], App\Models\Score::class)
                                        <th class="text-center">Aksi</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->scores()->with(['subject', 'teacher'])->orderBy('created_at', 'desc')->get() as $score)
                                    <tr>
                                        <td>
                                            <strong>{{ $score->subject->name }}</strong>
                                        </td>
                                        <td>
                                            <code>{{ $score->subject->code }}</code>
                                        </td>
                                        <td class="text-center">
                                            <span class="font-weight-bold text-primary">{{ number_format($score->score, 1) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $score->grade_color }}">{{ $score->grade }}</span>
                                        </td>
                                        <td>
                                            {{ $score->teacher->name ?? 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            @if($score->validated)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check mr-1"></i>Tervalidasi
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock mr-1"></i>Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <small class="text-muted">{{ $score->created_at->format('d/m/Y') }}</small>
                                        </td>
                                        @canany(['update', 'delete'], App\Models\Score::class)
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @can('update', $score)
                                                <a href="{{ route('scores.edit', $score) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('delete', $score)
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDeleteScore({{ $score->id }})" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </td>
                                        @endcanany
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-clipboard-list fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Belum Ada Nilai</h5>
                            <p class="text-muted">Siswa ini belum memiliki nilai untuk mata kuliah apapun.</p>
                            @can('create', App\Models\Score::class)
                            <a href="{{ route('scores.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Tambah Nilai Pertama
                            </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Student Modal -->
@can('delete', $student)
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus siswa <strong>{{ $student->name }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Perhatian:</strong> Tindakan ini akan menghapus semua data terkait siswa termasuk nilai-nilainya dan tidak dapat dibatalkan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-2"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- Delete Score Modal -->
<div class="modal fade" id="deleteScoreModal" tabindex="-1" aria-labelledby="deleteScoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteScoreModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus Nilai
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus nilai ini?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteScoreForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-2"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge-pink {
        background-color: #e91e63;
        color: white;
    }
    
    .badge-lg {
        font-size: 1.2rem;
        padding: 0.5rem 1rem;
    }
    
    .avatar-circle {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
    }
    
    .table th {
        font-weight: 600;
        color: #374151;
        border-top: none;
    }
    
    .table-borderless td {
        border: none;
        padding: 0.5rem 0;
    }
    
    .card-header h6 {
        font-size: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success message if any
    @if(session('success'))
        showAlert('success', '{{ session("success") }}');
    @endif

    // Show error message if any  
    @if(session('error'))
        showAlert('error', '{{ session("error") }}');
    @endif
});

function confirmDelete() {
    $('#deleteModal').modal('show');
}

function confirmDeleteScore(scoreId) {
    const form = document.getElementById('deleteScoreForm');
    form.action = `/scores/${scoreId}`;
    $('#deleteScoreModal').modal('show');
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
        ${message}
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endpush