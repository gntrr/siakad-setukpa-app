@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Jadwal Pelajaran</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Jadwal</li>
            </ol>
        </nav>
    </div>

    <!-- Schedule Information -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Jadwal</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Untuk informasi jadwal pelajaran yang lebih lengkap, silakan hubungi bagian akademik atau dosen yang bersangkutan.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Hari Kuliah</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Senin - Jumat
                                    <span class="badge badge-primary badge-pill">Aktif</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Sabtu
                                    <span class="badge badge-secondary badge-pill">Libur</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Minggu
                                    <span class="badge badge-secondary badge-pill">Libur</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Jam Kuliah</h5>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Sesi 1:</strong> 08:00 - 09:40
                                </li>
                                <li class="list-group-item">
                                    <strong>Istirahat:</strong> 09:40 - 10:00
                                </li>
                                <li class="list-group-item">
                                    <strong>Sesi 2:</strong> 10:00 - 11:40
                                </li>
                                <li class="list-group-item">
                                    <strong>Istirahat:</strong> 11:40 - 13:00
                                </li>
                                <li class="list-group-item">
                                    <strong>Sesi 3:</strong> 13:00 - 14:40
                                </li>
                                <li class="list-group-item">
                                    <strong>Sesi 4:</strong> 14:40 - 16:20
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Akses Cepat</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <a href="{{ route('subjects.index') }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-book mr-2"></i>Lihat Mata Pelajaran
                                    </a>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <a href="{{ route('dashboard') }}" class="btn btn-info btn-block">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Kembali ke Dashboard
                                    </a>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-block">
                                        <i class="fas fa-user mr-2"></i>Lihat Profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
