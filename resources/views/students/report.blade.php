@extends('layouts.app')

@section('title', 'Laporan Nilai - ' . $student->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Nilai Siswa</h1>
        <div>
            <a href="{{ route('students.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Siswa
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Student Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Informasi Siswa</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>Nomor Induk:</strong></td>
                                    <td>{{ $student->student_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Lengkap:</strong></td>
                                    <td>{{ $student->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $student->email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="150"><strong>Jenis Kelamin:</strong></td>
                                    <td>{{ $student->gender }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Lahir:</strong></td>
                                    <td>{{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-{{ $student->status == 'Aktif' ? 'success' : ($student->status == 'Lulus' ? 'primary' : 'warning') }}">
                                            {{ $student->status }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Mata Pelajaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subjectCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Rata-rata Nilai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $averageScore }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Nilai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalScore }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Predikat</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($averageScore >= 90)
                                    A
                                @elseif($averageScore >= 80)
                                    B
                                @elseif($averageScore >= 70)
                                    C
                                @elseif($averageScore >= 60)
                                    D
                                @else
                                    E
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-medal fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scores by Subject -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Nilai per Mata Pelajaran</h6>
                </div>
                <div class="card-body">
                    @if($scores->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="scoresTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kode</th>
                                        <th>Nilai</th>
                                        <th>Predikat</th>
                                        <th>Status</th>
                                        <th>Pengajar</th>
                                        <th>Tanggal Input</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($scores as $subjectName => $subjectScores)
                                        @foreach($subjectScores as $score)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $score->subject->name }}</td>
                                                <td>{{ $score->subject->code }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $score->score >= 80 ? 'success' : ($score->score >= 70 ? 'warning' : 'danger') }} badge-lg">
                                                        {{ $score->score }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($score->score >= 90)
                                                        <span class="text-success font-weight-bold">A</span>
                                                    @elseif($score->score >= 80)
                                                        <span class="text-info font-weight-bold">B</span>
                                                    @elseif($score->score >= 70)
                                                        <span class="text-warning font-weight-bold">C</span>
                                                    @elseif($score->score >= 60)
                                                        <span class="text-secondary font-weight-bold">D</span>
                                                    @else
                                                        <span class="text-danger font-weight-bold">E</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($score->validated)
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check"></i> Tervalidasi
                                                        </span>
                                                    @else
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-clock"></i> Menunggu
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $score->teacher->name ?? '-' }}</td>
                                                <td>{{ $score->created_at->format('d M Y') }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-3x text-gray-300 mb-3"></i>
                            <h5 class="text-gray-500">Belum ada nilai yang diinput</h5>
                            <p class="text-gray-400">Nilai siswa akan ditampilkan di sini setelah pengajar menginput nilai.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    @if($scores->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Nilai per Mata Pelajaran</h6>
                </div>
                <div class="card-body">
                    <canvas id="scoresChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Print Footer -->
    <div class="d-print-block d-none">
        <hr>
        <div class="row">
            <div class="col-6">
                <p class="small text-muted">
                    Dicetak pada: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}<br>
                    Sistem Akademik SETUKPA
                </p>
            </div>
            <div class="col-6 text-right">
                <p class="small text-muted">
                    Halaman ini dicetak secara otomatis<br>
                    dan tidak memerlukan tanda tangan
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .btn, .card-header, .breadcrumb, .navbar, .sidebar {
            display: none !important;
        }
        
        .card {
            border: 1px solid #000 !important;
            box-shadow: none !important;
        }
        
        .table {
            font-size: 12px;
        }
        
        .badge {
            border: 1px solid #000;
            color: #000 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($scores->count() > 0)
    // Prepare data for chart
    const chartData = {
        labels: [
            @foreach($scores as $subjectName => $subjectScores)
                '{{ $subjectName }}',
            @endforeach
        ],
        datasets: [{
            label: 'Nilai',
            data: [
                @foreach($scores as $subjectName => $subjectScores)
                    {{ $subjectScores->avg('score') }},
                @endforeach
            ],
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(199, 199, 199, 0.2)',
                'rgba(83, 102, 255, 0.2)',
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 205, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)',
                'rgba(83, 102, 255, 1)',
            ],
            borderWidth: 1
        }]
    };

    // Create chart
    const ctx = document.getElementById('scoresChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: chartData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Nilai per Mata Pelajaran'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 10
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endpush
