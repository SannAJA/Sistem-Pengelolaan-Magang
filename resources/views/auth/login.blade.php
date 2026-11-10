@extends('layouts.guest')

@section('title', 'Login')
@section('body_class', 'auth-login-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/auth-login.css') }}">
@endpush

@section('content')
<div class="auth-shell">
    <div class="auth-card p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="brand-logo-wrap mb-3">
                <img src="{{ asset('assets/images/klik-media1.png') }}" alt="Klik Media" class="brand-logo">
            </div>
            <h1 class="h4 fw-bold mb-1">Selamat Datang</h1>
            <p class="text-muted small mb-0">Silakan masuk ke Sistem Pengelolaan Magang</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-4 d-flex align-items-center gap-2 p-3 mb-3 small">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger flex-shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="d-grid gap-3">
            @csrf
            <div>
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3 text-muted">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" name="email" class="form-control border-start-0 rounded-end-pill @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                </div>
            </div>
            <div>
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group" data-password-toggle>
                    <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3 text-muted">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input
                        type="password"
                        name="password"
                        class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                        data-password-input
                        placeholder="Masukkan password"
                        required
                    >
                    <button
                        type="button"
                        class="input-group-text bg-light border-start-0 rounded-end-pill px-3 text-muted password-toggle-btn"
                        data-password-toggle-button
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                    >
                        <i class="bi bi-eye" data-password-toggle-icon></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-klik-primary rounded-pill py-2-5 fw-bold mt-2 shadow-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Akun
            </button>
        </form>
    </div>
</div>
@endsection
