@extends('layouts.app')

@section('title', 'Monitoring Jurnal')
@section('body_class', 'admin-journals-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/admin-journals-index.css') }}">
@endpush

@section('content')
<div class="soft-card p-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Monitoring Jurnal</h1>
            <p class="text-muted small mb-0">Lihat ringkasan kegiatan siswa beserta foto bukti kerja harian.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-mobile-cards align-middle mb-0">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Tanggal</th>
                    <th>Ringkasan Kegiatan</th>
                    <th>Bukti Kerja</th>
                </tr>
            </thead>
            <tbody>
                @forelse($journals as $journal)
                    <tr>
                        <td data-label="Siswa">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm rounded-circle bg-light border d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($journal->user?->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $journal->user?->name }}</div>
                                    <div class="text-muted small">{{ $journal->user?->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Tanggal">
                            <span class="badge bg-light text-dark border rounded-pill px-3 py-1-5">
                                <i class="bi bi-calendar-event me-1"></i> {{ optional($journal->date)->format('d M Y') }}
                            </span>
                        </td>
                        <td data-label="Ringkasan Kegiatan">
                            <div class="text-dark small lh-base" style="max-width: 480px;">{{ $journal->work_summary }}</div>
                        </td>
                        <td data-label="Bukti Kerja">
                            @if($journal->task_photo_path)
                                <button type="button" class="btn p-0 border-0 bg-transparent position-relative photo-thumb-btn" data-preview-image data-preview-src="{{ asset('storage/'.$journal->task_photo_path) }}" data-preview-title="Foto Jurnal - {{ $journal->user?->name }} ({{ optional($journal->date)->format('d M Y') }})">
                                    <img src="{{ asset('storage/'.$journal->task_photo_path) }}" alt="Foto jurnal" class="photo-thumb">
                                    <span class="photo-thumb-badge"><i class="bi bi-zoom-in"></i></span>
                                </button>
                            @else
                                <span class="text-muted small">Tanpa Foto</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada jurnal siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $journals->links() }}</div>
</div>
@endsection
