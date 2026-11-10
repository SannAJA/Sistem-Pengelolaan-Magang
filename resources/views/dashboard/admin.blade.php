@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('body_class', 'dashboard-admin-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/dashboard-admin.css') }}">
@endpush

@section('content')
<!-- MOBILE HERO WELCOME CARD ADMIN (Layar < 992px - Inspirasi Layout Reference) -->
<div class="mobile-hero-card soft-card p-4 text-center text-white mb-4 d-block d-lg-none position-relative overflow-hidden shadow-sm" style="background: linear-gradient(135deg, var(--klik-black) 0%, #1e293b 100%); border-radius: 1.5rem;">
    <div class="text-uppercase fw-extrabold mb-1 opacity-75" style="letter-spacing: 0.08em; font-size: 0.72rem;">PANEL PEMBIMBING LAPANGAN</div>
    <div class="fw-bold fs-4 mb-3 text-truncate">{{ auth()->user()?->name }}</div>
    <div class="avatar-circle-lg mx-auto mb-3 bg-white text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold fs-2 shadow-sm" style="width: 72px; height: 72px; border: 3.5px solid rgba(255,255,255,0.9);">
        {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
    </div>
    <span class="badge bg-danger text-white rounded-pill px-3 py-1-5 fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
        Administrator
    </span>
</div>

<!-- QUICK ACTION GRID CARDS ADMIN ON MOBILE -->
<div class="row g-3 mb-4 d-flex d-lg-none">
    <div class="col-6 col-sm-3">
        <a href="{{ route('admin.students.index') }}" class="soft-card p-3 text-center text-decoration-none d-flex flex-column align-items-center justify-content-center h-100 border shadow-xs" style="min-height: 100px;">
            <div class="icon-wrap bg-danger-subtle text-danger rounded-4 mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.25rem;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="fw-bold text-dark small">Kelola Siswa</div>
        </a>
    </div>
    <div class="col-6 col-sm-3">
        <a href="{{ route('admin.attendance.index') }}" class="soft-card p-3 text-center text-decoration-none d-flex flex-column align-items-center justify-content-center h-100 border shadow-xs" style="min-height: 100px;">
            <div class="icon-wrap bg-info-subtle text-info-emphasis rounded-4 mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.25rem;">
                <i class="bi bi-calendar2-check-fill"></i>
            </div>
            <div class="fw-bold text-dark small">Validasi Absen</div>
        </a>
    </div>
    <div class="col-6 col-sm-3">
        <a href="{{ route('admin.journals.index') }}" class="soft-card p-3 text-center text-decoration-none d-flex flex-column align-items-center justify-content-center h-100 border shadow-xs" style="min-height: 100px;">
            <div class="icon-wrap bg-success-subtle text-success rounded-4 mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.25rem;">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <div class="fw-bold text-dark small">Jurnal Siswa</div>
        </a>
    </div>
    <div class="col-6 col-sm-3">
        <a href="{{ route('admin.grades.index') }}" class="soft-card p-3 text-center text-decoration-none d-flex flex-column align-items-center justify-content-center h-100 border shadow-xs" style="min-height: 100px;">
            <div class="icon-wrap bg-warning-subtle text-warning-emphasis rounded-4 mb-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.25rem;">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="fw-bold text-dark small">Penilaian</div>
        </a>
    </div>
</div>

<!-- DESKTOP TITLE & HEADER (Tampil Hanya di Desktop >= 992px) -->
<div class="d-none d-lg-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
    <div>
        <h1 class="page-title fw-bold mb-1">Dashboard Admin</h1>
        <p class="text-muted mb-0">Ringkasan aktivitas siswa, absensi, jurnal, dan penilaian.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['label' => 'Siswa Aktif', 'value' => $studentCount, 'color' => 'metric-red', 'icon' => 'bi-people-fill'],
        ['label' => 'Total Absen', 'value' => $attendanceCount, 'color' => 'metric-blue', 'icon' => 'bi-calendar2-check-fill'],
        ['label' => 'Total Jurnal', 'value' => $journalCount, 'color' => 'metric-green', 'icon' => 'bi-journal-bookmark-fill'],
        ['label' => 'Data Nilai', 'value' => $gradeCount, 'color' => 'metric-dark', 'icon' => 'bi-award-fill'],
    ] as $card)
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card p-3 p-xl-4 h-100 {{ $card['color'] }}">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.04em;">{{ $card['label'] }}</div>
                        <div class="fs-2 fw-extrabold text-dark mt-1">{{ $card['value'] }}</div>
                    </div>
                    <div class="metric-icon">
                        <i class="bi {{ $card['icon'] }}"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-6">
        <div class="soft-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h5 fw-bold mb-0">Absen Terbaru</h2>
                <a href="{{ route('admin.attendance.index') }}" class="small text-decoration-none fw-semibold">
                    Lihat semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-mobile-cards align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestAttendances as $attendance)
                            <tr>
                                <td data-label="Siswa">
                                    <div class="fw-semibold">{{ $attendance->user?->name }}</div>
                                    <div class="text-muted small">{{ $attendance->user?->email }}</div>
                                </td>
                                <td data-label="Tanggal">{{ optional($attendance->date)->format('d M Y') }}</td>
                                <td data-label="Status">
                                    <span class="badge rounded-pill bg-success">
                                        {{ ucfirst($attendance->status ?? 'hadir') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data absen.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="soft-card p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h5 fw-bold mb-0">Jurnal Terbaru</h2>
                <a href="{{ route('admin.journals.index') }}" class="small text-decoration-none fw-semibold">
                    Lihat semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-mobile-cards align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th>Ringkasan</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestJournals as $journal)
                            <tr>
                                <td data-label="Siswa" class="fw-semibold">{{ $journal->user?->name }}</td>
                                <td data-label="Ringkasan" class="text-muted small">{{ \Illuminate\Support\Str::limit($journal->work_summary, 80) }}</td>
                                <td data-label="Foto">
                                    @if($journal->task_photo_path)
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" data-preview-image data-preview-src="{{ asset('storage/'.$journal->task_photo_path) }}" data-preview-title="Foto Jurnal {{ $journal->user?->name }}">
                                            <i class="bi bi-image me-1"></i> Foto
                                        </button>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada jurnal masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
