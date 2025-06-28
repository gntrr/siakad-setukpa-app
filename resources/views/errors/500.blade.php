@extends('layouts.app')

@section('title', '500 - Server Error')

@section('content')
<div class="container-fluid">
    <div class="text-center">
        <div class="error mx-auto" data-text="500">500</div>
        <p class="lead text-gray-800 mb-5">Server Error</p>
        <p class="text-gray-500 mb-0">Terjadi kesalahan pada server.</p>
        <p class="text-gray-500 mb-4">Mohon coba lagi dalam beberapa saat.</p>
        <a href="{{ route('dashboard') }}">&larr; Kembali ke Dashboard</a>
    </div>
</div>

@push('styles')
<style>
.error {
    font-size: 7rem;
    position: relative;
    line-height: 1;
    width: 12.5rem;
}

.error:before {
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, #f39c12, #d68910);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-fill-color: transparent;
    z-index: -1;
}
</style>
@endpush
@endsection
