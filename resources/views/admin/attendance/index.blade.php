@extends('layouts.app')

@section('title', 'Validasi Absen')
@section('body_class', 'admin-attendance-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/admin-attendance-index.css') }}">
@endpush

@section('content')
<div class="soft-card p-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Validasi Absen</h1>
            <p class="text-muted small mb-0">Monitoring absensi masuk dan pulang seluruh siswa magang.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-mobile-cards align-middle mb-0">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                    <tr>
                        <td data-label="Siswa">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm rounded-circle bg-light border d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($attendance->user?->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $attendance->user?->name }}</div>
                                    <div class="text-muted small">{{ $attendance->user?->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Tanggal">
                            <span class="badge bg-light text-dark border rounded-pill px-3 py-1-5">
                                <i class="bi bi-calendar-event me-1"></i> {{ optional($attendance->date)->format('d M Y') }}
                            </span>
                        </td>
                        <td data-label="Jam Masuk">
                            @if($attendance->clock_in)
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1-5">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> {{ optional($attendance->clock_in)->format('H:i') }}
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td data-label="Jam Pulang">
                            @if($attendance->clock_out)
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1-5">
                                    <i class="bi bi-box-arrow-right me-1"></i> {{ optional($attendance->clock_out)->format('H:i') }}
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td data-label="Status">
                            <span class="badge bg-success text-white rounded-pill px-3 py-1-5">
                                <i class="bi bi-check-circle-fill me-1"></i> {{ ucfirst($attendance->status ?? 'hadir') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data absensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $attendances->links() }}</div>
</div>
@endsection
