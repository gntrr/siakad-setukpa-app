@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pengguna</h1>
        <div class="d-flex">
            <a href="{{ route('users.index') }}" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @can('update', $user)
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning mr-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            @endcan
            @can('delete', $user)
            @if($user->id !== auth()->id())
            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
            @endif
            @endcan
        </div>
    </div>

    <div class="row">
        <!-- User Profile -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profil Pengguna</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <span class="text-white font-weight-bold h3">{{ substr($user->name, 0, 2) }}</span>
                        </div>
                    </div>
                    <h5 class="font-weight-bold text-gray-800">{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'warning' : 'info') }} badge-lg">
                        {{ ucfirst($user->role) }}
                    </span>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <div class="font-weight-bold text-primary">{{ $user->created_at->format('M Y') }}</div>
                                <small class="text-muted">Bergabung</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="font-weight-bold text-success">{{ $user->updated_at->diffForHumans() }}</div>
                            <small class="text-muted">Terakhir Update</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Permissions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hak Akses</h6>
                </div>
                <div class="card-body">
                    @php
                        $permissions = [
                            'admin' => [
                                'Mengelola semua pengguna',
                                'Mengelola semua data mahasiswa',
                                'Mengelola mata pelajaran',
                                'Mengelola nilai',
                                'Melihat laporan lengkap',
                                'Mengirim notifikasi'
                            ],
                            'teacher' => [
                                'Melihat data mahasiswa',
                                'Mengelola nilai mahasiswa',
                                'Melihat laporan terbatas',
                                'Update profil sendiri'
                            ],
                            'student' => [
                                'Melihat nilai sendiri',
                                'Update profil sendiri',
                                'Melihat notifikasi'
                            ]
                        ];
                    @endphp
                    
                    <ul class="list-unstyled">
                        @foreach($permissions[$user->role] ?? [] as $permission)
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success mr-2"></i>
                            {{ $permission }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Detail</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Nama Lengkap:</label>
                                <p class="form-control-plaintext">{{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Email:</label>
                                <p class="form-control-plaintext">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Role:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge badge-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'teacher' ? 'warning' : 'info') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Status Email:</label>
                                <p class="form-control-plaintext">
                                    @if($user->email_verified_at)
                                        <span class="badge badge-success">Terverifikasi</span>
                                        <small class="text-muted d-block">{{ $user->email_verified_at->format('d M Y') }}</small>
                                    @else
                                        <span class="badge badge-warning">Belum Verifikasi</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Tanggal Bergabung:</label>
                                <p class="form-control-plaintext">{{ $user->created_at->format('d M Y H:i') }}</p>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Terakhir Diupdate:</label>
                                <p class="form-control-plaintext">{{ $user->updated_at->format('d M Y H:i') }}</p>
                                <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Statistics -->
            @if($user->role == 'student')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Akademik</h6>
                </div>
                <div class="card-body">
                    @php
                        $student = $user->student;
                        $scores = $student ? $student->scores : collect();
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-right">
                                <div class="font-weight-bold text-primary h4">{{ $scores->count() }}</div>
                                <small class="text-muted">Total Mata Kuliah</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-right">
                                <div class="font-weight-bold text-success h4">{{ number_format($scores->avg('score') ?? 0, 2) }}</div>
                                <small class="text-muted">Rata-rata Nilai</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-right">
                                <div class="font-weight-bold text-info h4">{{ $scores->where('score', '>=', 60)->count() }}</div>
                                <small class="text-muted">Mata Kuliah Lulus</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="font-weight-bold text-warning h4">{{ $scores->where('score', '<', 60)->count() }}</div>
                            <small class="text-muted">Perlu Perbaikan</small>
                        </div>
                    </div>
                    
                    @if($scores->count() > 0)
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Nilai</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scores->take(5) as $score)
                                <tr>
                                    <td>{{ $score->subject->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $score->score >= 80 ? 'success' : ($score->score >= 70 ? 'warning' : ($score->score >= 60 ? 'info' : 'danger')) }}">
                                            {{ $score->score }}
                                        </span>
                                    </td>                                    <td>
                                        <span class="badge badge-{{ $score->grade_color }}">
                                            {{ $score->grade }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($score->score >= 60)
                                            <span class="badge badge-success">Lulus</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Lulus</span>
                                        @endif
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

            <!-- Recent Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
                </div>
                <div class="card-body">
                    @php
                        $activities = collect([
                            (object)[
                                'action' => 'Bergabung dengan sistem',
                                'date' => $user->created_at,
                                'icon' => 'fa-user-plus',
                                'color' => 'success'
                            ],
                            (object)[
                                'action' => 'Update profil',
                                'date' => $user->updated_at,
                                'icon' => 'fa-edit',
                                'color' => 'info'
                            ]
                        ]);
                        
                        if($user->email_verified_at) {
                            $activities->push((object)[
                                'action' => 'Verifikasi email',
                                'date' => $user->email_verified_at,
                                'icon' => 'fa-check-circle',
                                'color' => 'success'
                            ]);
                        }
                        
                        $activities = $activities->sortByDesc('date')->take(10);
                    @endphp
                    
                    @if($activities->count() > 0)
                    <div class="timeline">
                        @foreach($activities as $activity)
                        <div class="d-flex align-items-start mb-3">
                            <div class="mr-3">
                                <div class="bg-{{ $activity->color }} rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 35px; height: 35px;">
                                    <i class="fas {{ $activity->icon }} text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">{{ $activity->action }}</div>
                                <small class="text-muted">{{ $activity->date->format('d M Y H:i') }} ({{ $activity->date->diffForHumans() }})</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-history fa-2x mb-3"></i>
                        <p>Belum ada aktivitas yang tercatat</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.badge-lg {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 17px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline .d-flex:last-child::before {
    display: none;
}
</style>
@endpush
@endsection
