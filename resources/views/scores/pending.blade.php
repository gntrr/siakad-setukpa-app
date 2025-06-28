@extends('layouts.app')

@section('title', 'Validasi Nilai')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800">Validasi Nilai</h1>
                @if($pendingScores->count() > 0)
                <form action="{{ route('scores.bulk-validate') }}" method="POST" id="bulk-validate-form">
                    @csrf
                    <button type="button" class="btn btn-success" id="bulk-validate-btn" disabled>
                        <i class="fas fa-check-double"></i> Validasi Terpilih
                    </button>
                </form>
                @endif
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <!-- Filter and Search -->
                    <form method="GET" action="{{ route('scores.pending') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" 
                                       value="{{ $search ?? '' }}" placeholder="Cari mahasiswa atau mata kuliah...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="subject_id">
                                    <option value="">Semua Mata Kuliah</option>
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ ($subjectId ?? '') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }} ({{ $subject->code }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="teacher_id">
                                    <option value="">Semua Dosen</option>
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ ($teacherId ?? '') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    @if($pendingScores->count() > 0)
                                    <th width="30">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    @endif
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen</th>
                                    <th>Nilai</th>
                                    <th>Grade</th>
                                    <th>Tanggal Input</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingScores as $score)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="score-checkbox" value="{{ $score->id }}" name="score_ids[]" form="bulk-validate-form">
                                    </td>
                                    <td>{{ $score->student->name }}</td>
                                    <td>{{ $score->student->student_number }}</td>
                                    <td>{{ $score->subject->name }} ({{ $score->subject->code }})</td>
                                    <td>{{ $score->teacher->name }}</td>
                                    <td>{{ $score->score }}</td>
                                    <td><span class="grade-{{ $score->grade }}">{{ $score->grade }}</span></td>
                                    <td>{{ $score->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('scores.validate', $score) }}" method="POST" style="display: inline;"
                                              onsubmit="return confirm('Yakin ingin memvalidasi nilai ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted"></i>
                                        <p class="text-muted mt-2">Tidak ada nilai yang perlu divalidasi</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($pendingScores->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span>Menampilkan {{ $pendingScores->firstItem() ?? 0 }} - {{ $pendingScores->lastItem() ?? 0 }} dari {{ $pendingScores->total() }} data</span>
                        </div>
                        <div>
                            {{ $pendingScores->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.grade-A { color: #28a745; font-weight: bold; }
.grade-B { color: #007bff; font-weight: bold; }
.grade-C { color: #ffc107; font-weight: bold; }
.grade-D { color: #fd7e14; font-weight: bold; }
.grade-E { color: #dc3545; font-weight: bold; }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#select-all').change(function() {
        const isChecked = $(this).is(':checked');
        $('.score-checkbox').prop('checked', isChecked);
        updateBulkValidateButton();
    });

    // Individual checkbox change
    $(document).on('change', '.score-checkbox', function() {
        updateBulkValidateButton();
    });

    // Bulk validate button click
    $('#bulk-validate-btn').click(function() {
        const selectedCount = $('.score-checkbox:checked').length;
        if (selectedCount > 0) {
            if (confirm(`Yakin ingin memvalidasi ${selectedCount} nilai yang dipilih?`)) {
                $('#bulk-validate-form').submit();
            }
        }
    });

    function updateBulkValidateButton() {
        const checkedCount = $('.score-checkbox:checked').length;
        $('#bulk-validate-btn').prop('disabled', checkedCount === 0);
    }
});

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
