@extends('layouts.app')

@section('title', 'Absen')
@section('body_class', 'attendance-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/attendance-index.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/pages/attendance-index.js') }}" defer></script>
@endpush

@section('content')
@php($attendanceOld = old('_modal') === 'attendance-form')
<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="soft-card p-4 p-md-5 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center mb-4">
                <div>
                    <h1 class="page-title fw-bold mb-1">Absen Harian</h1>
                    <p class="text-muted small mb-0">Catat kehadiran masuk dan pulang Anda secara akurat berbasis lokasi.</p>
                </div>
                <button type="button" class="btn btn-klik-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#attendanceModal">
                    <i class="bi bi-geo-alt-fill me-1"></i> Isi Absensi
                </button>
            </div>

            @if($todayAttendance)
                <div class="mt-4 p-4 bg-light rounded-4 border-0">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="fw-bold text-dark fs-6">
                            <i class="bi bi-calendar2-check text-primary me-2"></i> Status Kehadiran Hari Ini
                        </div>
                        <span class="badge {{ $todayAttendance->status === 'hadir' ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3 py-1-5">
                            {{ ucfirst($todayAttendance->status) }}
                        </span>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-3 border">
                                <div class="text-muted small mb-1">
                                    <i class="bi bi-box-arrow-in-right text-success me-1"></i> Jam Masuk
                                </div>
                                <div class="fs-5 fw-bold text-dark">
                                    {{ optional($todayAttendance->clock_in)->format('H:i') ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-3 border">
                                <div class="text-muted small mb-1">
                                    <i class="bi bi-box-arrow-right text-primary me-1"></i> Jam Pulang
                                </div>
                                <div class="fs-5 fw-bold text-dark">
                                    {{ optional($todayAttendance->clock_out)->format('H:i') ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-4 p-4 bg-light rounded-4 text-center">
                    <i class="bi bi-geo-alt text-muted fs-1 d-block mb-2 opacity-50"></i>
                    <div class="fw-bold text-dark">Anda belum melakukan absensi hari ini</div>
                    <div class="text-muted small mt-1">Klik tombol <strong>Isi Absensi</strong> di atas untuk mencatat kehadiran.</div>
                </div>
            @endif
        </div>

        <div class="soft-card p-4 p-md-5">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center mb-4">
                <div>
                    <h2 class="h5 fw-bold mb-1">Rekap Absensi Saya</h2>
                    <p class="text-muted small mb-0">Riwayat catatan absensi dan verifikasi kehadiran Anda.</p>
                </div>
                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                    <i class="bi bi-clock-history me-1 text-primary"></i> {{ $attendances->total() }} Catatan
                </span>
            </div>

            <form method="GET" action="{{ route('student.attendance.index') }}" class="row g-2 mb-4 align-items-end">
                <div class="col-8 col-md-5">
                    <label class="form-label text-muted small mb-1 fw-semibold">Filter Bulan</label>
                    <input type="month" name="month" class="form-control rounded-3" value="{{ request('month') }}">
                </div>
                <div class="col-4 col-md-7 d-flex gap-2">
                    <button type="submit" class="btn btn-klik-primary rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    @if(request('month'))
                        <a href="{{ route('student.attendance.index') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i> Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-mobile-cards align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                            <tr>
                                <td data-label="Tanggal">
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1-5">
                                        <i class="bi bi-calendar-event me-1 text-primary"></i> {{ optional($att->date)->format('d M Y') }}
                                    </span>
                                </td>
                                <td data-label="Status">
                                    <span class="badge bg-success rounded-pill px-3 py-1-5">
                                        <i class="bi bi-check-circle-fill me-1"></i> Hadir
                                    </span>
                                </td>
                                <td data-label="Jam Masuk">
                                    <span class="fw-semibold text-dark">{{ optional($att->clock_in)->format('H:i') ?? '-' }}</span>
                                </td>
                                <td data-label="Jam Pulang">
                                    <span class="fw-semibold text-dark">{{ optional($att->clock_out)->format('H:i') ?? '-' }}</span>
                                </td>
                                <td data-label="Keterangan">
                                    <span class="text-muted small">
                                        <i class="bi bi-geo-alt me-1"></i>Absensi Berbasis Lokasi
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat absensi yang tersimpan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-form" id="attendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title">Isi Absensi Harian</h5>
                    <p class="text-muted mb-0 small">Pastikan izin GPS aktif dan Anda berada di area kantor.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form
                    action="{{ route('student.attendance.store') }}"
                    method="POST"
                    class="d-grid gap-3"
                    data-attendance-form
                    data-office-latitude="{{ $officeLatitude }}"
                    data-office-longitude="{{ $officeLongitude }}"
                    data-max-radius-meters="{{ $maxRadiusMeters }}"
                >
                    @csrf
                    <input type="hidden" name="_modal" value="attendance-form">
                    <input type="hidden" name="latitude" data-attendance-latitude value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" data-attendance-longitude value="{{ old('longitude') }}">
                    
                    <div class="d-grid d-md-flex gap-2 pt-2">
                        <button type="submit" name="action" value="clock_in" class="btn btn-klik-primary rounded-pill py-2-5 flex-fill fw-bold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Absen Masuk
                        </button>
                        <button type="submit" name="action" value="clock_out" class="btn btn-klik-secondary rounded-pill py-2-5 flex-fill fw-bold">
                            <i class="bi bi-box-arrow-right me-1"></i> Absen Pulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
