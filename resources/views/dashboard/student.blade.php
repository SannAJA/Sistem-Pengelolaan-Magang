@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('body_class', 'dashboard-student-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/dashboard-student.css') }}">
@endpush

@section('content')
<!-- MOBILE HERO WELCOME CARD (Layar < 992px - Inspirasi Layout Reference) -->
<div class="mobile-hero-card soft-card p-4 text-center text-white mb-4 d-block d-lg-none position-relative overflow-hidden shadow-sm" style="background: linear-gradient(135deg, var(--klik-blue) 0%, #005a70 100%); border-radius: 1.5rem;">
    <div class="text-uppercase fw-extrabold mb-1 opacity-75" style="letter-spacing: 0.08em; font-size: 0.72rem;">SELAMAT DATANG</div>
    <div class="fw-bold fs-4 mb-3 text-truncate">{{ auth()->user()?->name }}</div>
    <div class="avatar-circle-lg mx-auto mb-3 bg-white text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold fs-2 shadow-sm" style="width: 72px; height: 72px; border: 3.5px solid rgba(255,255,255,0.9);">
        {{ strtoupper(substr(auth()->user()?->name ?? 'S', 0, 1)) }}
    </div>
    <span class="badge bg-white text-dark rounded-pill px-3 py-1-5 fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
        Siswa Magang
    </span>
</div>

<!-- QUICK ACTION GRID CARDS ON MOBILE -->
<div class="row g-3 mb-4 d-flex d-lg-none">
    <div class="col-6">
        <a href="{{ route('student.attendance.index') }}" class="soft-card p-3 text-center text-decoration-none d-flex flex-column align-items-center justify-content-center h-100 border shadow-xs" style="min-height: 110px;">
            <div class="icon-wrap bg-danger-subtle text-danger rounded-4 mb-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.4rem;">
                <i class="bi bi-calendar2-check-fill"></i>
            </div>
            <div class="fw-bold text-dark small">Absen Harian</div>
        </a>
    </div>
    <div class="col-6">
        <a href="{{ route('student.journals.index') }}" class="soft-card p-3 text-center text-decoration-none d-flex flex-column align-items-center justify-content-center h-100 border shadow-xs" style="min-height: 110px;">
            <div class="icon-wrap bg-info-subtle text-info-emphasis rounded-4 mb-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.4rem;">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div class="fw-bold text-dark small">Tulis Jurnal</div>
        </a>
    </div>
</div>

<!-- DESKTOP TITLE & HEADER (Tampil Hanya di Desktop >= 992px) -->
<div class="d-none d-lg-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <h1 class="page-title fw-bold mb-1">Dashboard Siswa</h1>
        <p class="text-muted mb-0">Pantau absensi hari ini, jurnal, dan ringkasan performa Anda.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="metric-card p-4 h-100 metric-red">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.04em;">Absensi Hari Ini</span>
                <div class="metric-icon">
                    <i class="bi bi-calendar2-check-fill"></i>
                </div>
            </div>
            <div class="fs-4 fw-bold text-dark mb-1">
                @if($todayAttendance)
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                        <i class="bi bi-check-circle-fill me-1"></i> Sudah Absen ({{ ucfirst($todayAttendance->status) }})
                    </span>
                @else
                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2">
                        <i class="bi bi-clock-fill me-1"></i> Belum Absen
                    </span>
                @endif
            </div>
            <div class="text-muted small mt-2">Gunakan menu absen harian untuk mencatat kehadiran hari ini.</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card p-4 h-100 metric-blue">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.04em;">Total Jurnal</span>
                <div class="metric-icon">
                    <i class="bi bi-journal-text"></i>
                </div>
            </div>
            <div class="fs-2 fw-extrabold text-dark">{{ $myJournalCount }}</div>
            <div class="text-muted small mt-1">Jurnal harian tersimpan dalam sistem.</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card p-4 h-100 metric-green">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.04em;">Status Nilai</span>
                <div class="metric-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
            </div>
            <div class="fs-2 fw-extrabold text-dark">{{ $myGrade?->final_score ? number_format($myGrade->final_score, 2) : '-' }}</div>
            <div class="text-muted small mt-1 text-truncate">{{ $myGrade?->feedback ?? 'Belum ada penilaian akhir.' }}</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="soft-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h5 fw-bold mb-0">
                    <i class="bi bi-award-fill text-warning me-2"></i>Hasil Penilaian & Evaluasi Magang
                </h2>
                @if($myGrade?->final_score !== null)
                    @php
                        $score = (float) $myGrade->final_score;
                        if ($score >= 85) {
                            $predicate = 'Sangat Baik (A)';
                            $badgeClass = 'bg-success text-white';
                        } elseif ($score >= 75) {
                            $predicate = 'Baik (B)';
                            $badgeClass = 'bg-primary text-white';
                        } elseif ($score >= 65) {
                            $predicate = 'Cukup (C)';
                            $badgeClass = 'bg-warning text-dark';
                        } else {
                            $predicate = 'Perlu Perbaikan (D)';
                            $badgeClass = 'bg-danger text-white';
                        }
                    @endphp
                    <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2 fw-semibold">
                        Predikat: {{ $predicate }}
                    </span>
                @endif
            </div>

            @if($myGrade?->final_score !== null || $myGrade?->feedback)
                <div class="row align-items-center g-3">
                    <div class="col-md-3 text-center border-end-md">
                        <div class="text-muted small fw-semibold text-uppercase mb-1" style="letter-spacing: 0.05em;">Nilai Akhir</div>
                        <div class="display-4 fw-extrabold text-primary">
                            {{ $myGrade->final_score !== null ? number_format($myGrade->final_score, 2) : '-' }}
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-clock-history me-1"></i>Diperbarui: {{ $myGrade->updated_at?->format('d M Y') ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-9 ps-md-4">
                        <div class="fw-semibold text-dark mb-1">
                            <i class="bi bi-chat-quote-fill text-muted me-1"></i> Catatan & Evaluasi Pembimbing:
                        </div>
                        <div class="p-3 bg-light rounded-4 border text-dark lh-base">
                            {{ $myGrade->feedback ?? 'Belum ada catatan tertulis dari pembimbing.' }}
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 bg-light rounded-4 text-center text-muted">
                    <i class="bi bi-hourglass-split fs-2 text-muted opacity-50 d-block mb-2"></i>
                    <div>Belum ada penilaian akhir.</div>
                    <div class="small">Nilai akhir dan feedback dari pembimbing akan ditampilkan di sini setelah diinput oleh admin.</div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="soft-card p-4 h-100 d-flex flex-column">
            <h2 class="h5 fw-bold mb-3">Aksi Cepat</h2>
            <div class="d-grid gap-3 my-auto">
                <a href="{{ route('student.attendance.index') }}" class="btn btn-klik-primary rounded-pill py-2-5 fw-bold">
                    <i class="bi bi-geo-alt-fill me-1"></i> Absen Harian
                </a>
                <a href="{{ route('student.journals.index') }}" class="btn btn-klik-secondary rounded-pill py-2-5 fw-bold">
                    <i class="bi bi-pencil-square me-1"></i> Tulis Jurnal Harian
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="soft-card p-4 h-100">
            <h2 class="h5 fw-bold mb-3">Jurnal Terakhir</h2>
            @if($latestJournal)
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-light text-dark rounded-pill px-3 py-1-5 border">
                        <i class="bi bi-calendar-event me-1"></i> {{ optional($latestJournal->date)->format('d M Y') }}
                    </span>
                </div>
                <p class="mb-0 text-muted p-3 bg-light rounded-4 border-0">{{ $latestJournal->work_summary }}</p>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x fs-1 text-muted opacity-50 d-block mb-2"></i>
                    Belum ada jurnal yang tersimpan.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
