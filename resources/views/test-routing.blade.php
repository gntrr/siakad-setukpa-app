@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Test Routing</h1>
            <p>Halaman ini digunakan untuk menguji routing dan koneksi.</p>
            
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Status System</h5>
                    <p class="card-text">Sistem berjalan dengan normal.</p>
                    <p>Laravel Version: {{ app()->version() }}</p>
                    <p>PHP Version: {{ phpversion() }}</p>
                    <p>Database Connected: 
                        @try
                            {{ DB::connection()->getPdo() ? 'Yes' : 'No' }}
                        @catch(Exception $e)
                            No ({{ $e->getMessage() }})
                        @endtry
                    </p>
                    
                    <h6 class="mt-4">Quick Links</h6>
                    <a href="{{ route('students.index') }}" class="btn btn-primary mr-2">Students</a>
                    <a href="{{ route('subjects.index') }}" class="btn btn-success mr-2">Subjects</a>
                    <a href="{{ route('scores.index') }}" class="btn btn-info mr-2">Scores</a>
                    <a href="{{ route('notifications.index') }}" class="btn btn-warning">Notifications</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
