@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan SETUKPA</h1>
        <div class="d-flex">
            <button class="btn btn-success mr-2" onclick="exportReport()">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="btn btn-info" onclick="printReport()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="report_type">Jenis Laporan</label>
                            <select class="form-control" id="report_type" name="report_type">
                                <option value="overview" {{ request('report_type') == 'overview' ? 'selected' : '' }}>
                                    Ringkasan Umum
                                </option>
                                <option value="students" {{ request('report_type') == 'students' ? 'selected' : '' }}>
                                    Laporan Mahasiswa
                                </option>
                                <option value="subjects" {{ request('report_type') == 'subjects' ? 'selected' : '' }}>
                                    Laporan Mata Pelajaran
                                </option>
                                <option value="scores" {{ request('report_type') == 'scores' ? 'selected' : '' }}>
                                    Laporan Nilai
                                </option>
                                <option value="performance" {{ request('report_type') == 'performance' ? 'selected' : '' }}>
                                    Laporan Performa
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <select class="form-control" id="semester" name="semester">
                                <option value="">Semua</option>
                                @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ request('semester') == $i ? 'selected' : '' }}>
                                    Semester {{ $i }}
                                </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="year">Tahun</label>
                            <select class="form-control" id="year" name="year">
                                <option value="">Semua</option>
                                @php
                                    $currentYear = date('Y');
                                    for($year = $currentYear; $year >= $currentYear - 5; $year--) {
                                        echo "<option value='$year'" . (request('year') == $year ? ' selected' : '') . ">$year</option>";
                                    }
                                @endphp
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_range">Rentang Tanggal</label>
                            <input type="text" class="form-control" id="date_range" name="date_range" 
                                   value="{{ request('date_range') }}" placeholder="Pilih rentang tanggal">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Content -->
    @if(request('report_type') == 'students' || !request('report_type'))
        <!-- Students Report -->
        <div class="card shadow mb-4" id="students-report">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Laporan Mahasiswa</h6>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Mahasiswa</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_students'] ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Mahasiswa Aktif</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['active_students'] ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Rata-rata IPK</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($statistics['average_gpa'] ?? 0, 2) }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Perlu Perhatian</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['low_performing_students'] ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($students) && $students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="studentsTable">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Total Mata Kuliah</th>
                                <th>Rata-rata Nilai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->nim }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->scores_count }}</td>
                                <td>
                                    @if($student->scores_avg_score)
                                        <span class="badge badge-{{ $student->scores_avg_score >= 80 ? 'success' : ($student->scores_avg_score >= 70 ? 'warning' : ($student->scores_avg_score >= 60 ? 'info' : 'danger')) }}">
                                            {{ number_format($student->scores_avg_score, 2) }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->scores_avg_score >= 60)
                                        <span class="badge badge-success">Baik</span>
                                    @elseif($student->scores_avg_score >= 40)
                                        <span class="badge badge-warning">Perlu Perhatian</span>
                                    @elseif($student->scores_avg_score)
                                        <span class="badge badge-danger">Bermasalah</span>
                                    @else
                                        <span class="badge badge-secondary">Belum Ada Nilai</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    @endif

    @if(request('report_type') == 'subjects' || !request('report_type'))
        <!-- Subjects Report -->
        <div class="card shadow mb-4" id="subjects-report">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Laporan Mata Pelajaran</h6>
            </div>
            <div class="card-body">
                @if(isset($subjects) && $subjects->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="subjectsTable">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Mata Pelajaran</th>
                                <th>SKS</th>
                                <th>Semester</th>
                                <th>Jumlah Mahasiswa</th>
                                <th>Rata-rata Nilai</th>
                                <th>Tingkat Kelulusan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects as $index => $subject)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $subject->code }}</td>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->credits }}</td>
                                <td>{{ $subject->semester }}</td>
                                <td>{{ $subject->scores_count }}</td>
                                <td>
                                    @if($subject->scores_avg_score)
                                        <span class="badge badge-{{ $subject->scores_avg_score >= 80 ? 'success' : ($subject->scores_avg_score >= 70 ? 'warning' : ($subject->scores_avg_score >= 60 ? 'info' : 'danger')) }}">
                                            {{ number_format($subject->scores_avg_score, 2) }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $passRate = $subject->scores_count > 0 ? ($subject->passed_scores_count / $subject->scores_count) * 100 : 0;
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 mr-2" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $passRate >= 80 ? 'success' : ($passRate >= 60 ? 'warning' : 'danger') }}" 
                                                 style="width: {{ $passRate }}%"></div>
                                        </div>
                                        <small>{{ number_format($passRate, 1) }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('subjects.show', $subject) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    @endif

    @if(request('report_type') == 'performance' || !request('report_type'))
        <!-- Performance Charts -->
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tren Performa Mahasiswa</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="performanceChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Distribusi Grade</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="gradeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTables
    if ($('#studentsTable').length) {
        $('#studentsTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "pageLength": 25,
            "order": [[ 5, "desc" ]] // Sort by average score
        });
    }
    
    if ($('#subjectsTable').length) {
        $('#subjectsTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "pageLength": 25,
            "order": [[ 6, "desc" ]] // Sort by average score
        });
    }
    
    // Initialize date range picker
    $('#date_range').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD',
            separator: ' to ',
            applyLabel: 'Terapkan',
            cancelLabel: 'Batal',
            fromLabel: 'Dari',
            toLabel: 'Sampai',
            customRangeLabel: 'Custom',
            weekLabel: 'W',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            firstDay: 1
        },
        autoUpdateInput: false
    });
    
    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
    });
    
    $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    
    // Initialize charts
    initializeCharts();
});

function initializeCharts() {
    // Performance Chart
    if ($('#performanceChart').length) {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($performanceLabels ?? []) !!},
                datasets: [{
                    label: 'Rata-rata Nilai',
                    data: {!! json_encode($performanceData ?? []) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
    
    // Grade Distribution Chart
    if ($('#gradeChart').length) {
        const ctx2 = document.getElementById('gradeChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($gradeLabels ?? ['A', 'B', 'C', 'D', 'E']) !!},
                datasets: [{
                    data: {!! json_encode($gradeData ?? [10, 20, 30, 25, 15]) !!},
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#17a2b8',
                        '#fd7e14',
                        '#dc3545'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}

function exportReport() {
    const reportType = $('#report_type').val();
    const filters = $('#filterForm').serialize();
    
    window.open(`/reports/export?${filters}`, '_blank');
}

function printReport() {
    window.print();
}

// Auto-refresh report data every 5 minutes
setInterval(function() {
    // You can implement auto-refresh logic here if needed
}, 300000);
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn, .card-header, .no-print {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .table {
        font-size: 12px;
    }
}

.progress {
    background-color: #e9ecef;
}

.table th {
    background-color: #f8f9fc;
    border-top: none;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}
</style>
@endpush
@endsection
