@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>    <!-- Statistics Cards -->
    <div class="row mb-4">
        @can('viewAny', App\Models\Student::class)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Siswa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_students'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('viewAny', App\Models\Subject::class)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Mata Pelajaran
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_subjects'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('viewAny', App\Models\Score::class)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Nilai
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_scores'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('validate', App\Models\Score::class)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Nilai Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_scores'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <div class="row">
        <!-- Recent Activity -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div id="recentActivity">
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                            <p class="text-muted mt-2">Memuat aktivitas...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @can('create', App\Models\Student::class)
                        <a href="{{ route('students.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user-plus text-primary mr-2"></i>
                            Tambah Siswa Baru
                        </a>
                        @endcan

                        @can('create', App\Models\Subject::class)
                        <a href="{{ route('subjects.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-book text-success mr-2"></i>
                            Tambah Mata Pelajaran
                        </a>
                        @endcan

                        @can('create', App\Models\Score::class)
                        <a href="{{ route('scores.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus text-info mr-2"></i>
                            Input Nilai Siswa
                        </a>
                        @endcan

                        @can('validate', App\Models\Score::class)
                        <a href="{{ route('scores.pending') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-check text-warning mr-2"></i>
                            Validasi Nilai
                        </a>
                        @endcan

                        @can('viewAny', App\Models\User::class)
                        <a href="{{ route('reports.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-alt text-secondary mr-2"></i>
                            Buat Laporan
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('viewAny', App\Models\Score::class)
    <!-- Score Statistics Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Nilai</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container position-relative" style="height: 400px;">
                        <canvas id="scoreChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection

@push('styles')
<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .activity-item {
        border-left: 3px solid #e3e6f0;
        padding-left: 15px;
        margin-bottom: 15px;
        position: relative;
    }
    .activity-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #858796;
    }
    .activity-item.primary::before {
        background-color: #4e73df;
    }
    .activity-item.success::before {
        background-color: #1cc88a;
    }
    .activity-item.info::before {
        background-color: #36b9cc;
    }
    .activity-item.warning::before {
        background-color: #f6c23e;
    }
    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
    }
    
    @media (max-width: 768px) {
        .chart-container {
            height: 300px;
        }
    }
    
    @media (max-width: 576px) {
        .chart-container {
            height: 250px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load recent activity from server data
    loadRecentActivity();
    
    @can('viewAny', App\Models\Score::class)
    loadScoreChart();
    @endcan
});

function loadRecentActivity() {
    const container = document.getElementById('recentActivity');
    const notifications = @json($recentNotifications ?? []);
    
    if (notifications.length > 0) {
        container.innerHTML = notifications.map(activity => `
            <div class="activity-item ${getActivityClass(activity.type)}">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">${activity.title || 'Aktivitas'}</h6>
                        <p class="mb-1 text-muted">${activity.message}</p>
                        <small class="text-muted">${new Date(activity.created_at).toLocaleString()}</small>
                    </div>
                </div>
            </div>
        `).join('');
    } else {
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-2x text-muted"></i>
                <p class="text-muted mt-2">Belum ada aktivitas terbaru</p>
            </div>
        `;
    }
}

function getActivityClass(type) {
    switch(type) {
        case 'score_added': return 'info';
        case 'score_validated': return 'success';
        case 'student_registered': return 'primary';
        case 'score_pending': return 'warning';
        default: return '';
    }
}

@can('viewAny', App\Models\Score::class)
function loadScoreChart() {
    const scoreStatistics = @json($scoreStatistics ?? []);
    const ctx = document.getElementById('scoreChart');
    
    if (ctx) {
        const grades = ['A', 'B', 'C', 'D', 'E'];
        const values = grades.map(grade => scoreStatistics[grade] || 0);
        
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: grades,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        '#1cc88a', // A - Green
                        '#36b9cc', // B - Blue
                        '#f6c23e', // C - Yellow
                        '#fd7e14', // D - Orange
                        '#e74a3b'  // E - Red
                    ],
                    hoverBackgroundColor: [
                        '#17a673',
                        '#2c9faf',
                        '#dda20a',
                        '#e8540b',
                        '#c0392b'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10,
                        left: 10,
                        right: 10
                    }
                }
            }
        });
    }
}
@endcan
</script>
@endpush
