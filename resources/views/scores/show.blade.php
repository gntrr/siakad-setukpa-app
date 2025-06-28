@extends('layouts.app')

@section('title', 'Detail Nilai')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Nilai</h1>
        <div class="d-flex">
            <a href="{{ route('scores.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @can('update', $score)
            <a href="{{ route('scores.edit', $score) }}" class="btn btn-warning mr-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            @endcan
            @can('delete', $score)
            <form action="{{ route('scores.destroy', $score) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
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
        <!-- Score Details Card -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Nilai</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Mahasiswa:</label>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <span class="text-white font-weight-bold">{{ substr($score->student->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="form-control-plaintext mb-0 font-weight-bold">{{ $score->student->name }}</p>
                                        <small class="text-muted">NIM: {{ $score->student->nim }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Mata Pelajaran:</label>
                                <p class="form-control-plaintext">{{ $score->subject->name }}</p>
                                <small class="text-muted">Kode: {{ $score->subject->code }} | SKS: {{ $score->subject->credits }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group text-center">
                                <label class="font-weight-bold">Nilai Angka:</label>
                                <div class="display-4 font-weight-bold text-{{ $score->score >= 80 ? 'success' : ($score->score >= 70 ? 'warning' : ($score->score >= 60 ? 'info' : 'danger')) }}">
                                    {{ $score->score }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">                            <div class="form-group text-center">
                                <label class="font-weight-bold">Grade:</label>
                                <div class="display-4 font-weight-bold text-{{ $score->grade_color }}">
                                    {{ $score->grade }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group text-center">
                                <label class="font-weight-bold">Status:</label>
                                <div class="display-6">
                                    <span class="badge badge-{{ $score->score >= 60 ? 'success' : 'danger' }} p-3">
                                        {{ $score->score >= 60 ? 'LULUS' : 'TIDAK LULUS' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Input:</label>
                                <p class="form-control-plaintext">{{ $score->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Terakhir Diupdate:</label>
                                <p class="form-control-plaintext">{{ $score->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($score->notes)
                    <div class="form-group">
                        <label class="font-weight-bold">Catatan:</label>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="card-text">{{ $score->notes }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics & Context Card -->
        <div class="col-lg-4">
            <!-- Grade Scale Reference -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Skala Nilai</h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="d-flex justify-content-between mb-1">
                            <span>A</span>
                            <span>85 - 100</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>A-</span>
                            <span>80 - 84</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>B+</span>
                            <span>75 - 79</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>B</span>
                            <span>70 - 74</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>B-</span>
                            <span>65 - 69</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>C+</span>
                            <span>60 - 64</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>C</span>
                            <span>55 - 59</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>C-</span>
                            <span>50 - 54</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>D+</span>
                            <span>45 - 49</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>D</span>
                            <span>40 - 44</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>E</span>
                            <span>0 - 39</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subject Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Mata Pelajaran</h6>
                </div>
                <div class="card-body">
                    @php
                        $subjectScores = $score->subject->scores;
                        $avgScore = $subjectScores->avg('score');
                        $maxScore = $subjectScores->max('score');
                        $minScore = $subjectScores->min('score');
                        $totalStudents = $subjectScores->count();
                        $passedStudents = $subjectScores->where('score', '>=', 60)->count();
                    @endphp
                    
                    <div class="text-center mb-3">
                        <small class="text-muted">{{ $score->subject->name }}</small>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <div class="font-weight-bold text-primary">{{ number_format($avgScore, 1) }}</div>
                                <small class="text-muted">Rata-rata</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="font-weight-bold text-success">{{ $maxScore }}</div>
                            <small class="text-muted">Tertinggi</small>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <div class="font-weight-bold text-warning">{{ $minScore }}</div>
                                <small class="text-muted">Terendah</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="font-weight-bold text-info">{{ $passedStudents }}/{{ $totalStudents }}</div>
                            <small class="text-muted">Lulus</small>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $totalStudents > 0 ? ($passedStudents / $totalStudents) * 100 : 0 }}%">
                            </div>
                        </div>
                        <small class="text-muted">{{ $totalStudents > 0 ? round(($passedStudents / $totalStudents) * 100, 1) : 0 }}% tingkat kelulusan</small>
                    </div>
                </div>
            </div>

            <!-- Student Performance Context -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Posisi Mahasiswa</h6>
                </div>
                <div class="card-body">
                    @php
                        $studentScores = $score->student->scores;
                        $studentAvg = $studentScores->avg('score');
                        $studentRank = $subjectScores->where('score', '>', $score->score)->count() + 1;
                    @endphp
                    
                    <div class="text-center mb-3">
                        <small class="text-muted">{{ $score->student->name }}</small>
                    </div>
                    
                    <div class="text-center">
                        <div class="font-weight-bold text-primary h4">{{ $studentRank }}</div>
                        <small class="text-muted">dari {{ $totalStudents }} mahasiswa</small>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="text-center">
                        <div class="font-weight-bold text-info">{{ number_format($studentAvg, 1) }}</div>
                        <small class="text-muted">Rata-rata IPK</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.display-6 {
    font-size: 2rem;
    font-weight: 600;
    line-height: 1.2;
}
</style>
@endpush
@endsection
