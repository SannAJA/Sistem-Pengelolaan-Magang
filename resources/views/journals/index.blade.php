@extends('layouts.app')

@section('title', 'Jurnal Harian')
@section('body_class', 'journals-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/journals-index.css') }}">
@endpush

@section('content')
@php($journalOld = old('_modal') === 'journalModal')

<div class="soft-card p-4 p-lg-5 mb-4 journal-form-card">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-start mb-3">
        <div>
            <h1 class="page-title fw-bold mb-1">Jurnal Harian</h1>
            <p class="text-muted small mb-0">Tulis ringkasan kegiatan harian dan lampirkan foto bukti kerja bila ada.</p>
        </div>
        <button type="button" class="btn btn-klik-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#journalModal">
            <i class="bi bi-pencil-square me-1"></i> Tulis Jurnal
        </button>
    </div>

    <div class="text-muted small">
        <i class="bi bi-info-circle me-1 text-primary"></i> Isi jurnal hari ini secara ringkas, jelas, dan sesuai aktivitas yang telah Anda kerjakan.
    </div>
</div>

<div class="soft-card p-4 p-lg-5 journal-history-card">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-4">
        <div>
            <h2 class="h5 fw-bold mb-1">Riwayat Jurnal</h2>
            <p class="text-muted small mb-0">Daftar rekaman jurnal yang telah Anda kirim sebelumnya.</p>
        </div>
        <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
            <i class="bi bi-journal-check me-1 text-primary"></i> {{ $journals->total() }} Entri Tersimpan
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-mobile-cards align-middle mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Ringkasan Kegiatan</th>
                    <th>Bukti Foto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($journals as $journal)
                    <tr>
                        <td data-label="Tanggal">
                            <span class="badge bg-light text-dark border rounded-pill px-3 py-1-5">
                                <i class="bi bi-calendar-event me-1"></i> {{ optional($journal->date)->format('d M Y') }}
                            </span>
                        </td>
                        <td data-label="Ringkasan Kegiatan">
                            <div class="text-dark small lh-base" style="max-width: 520px;">
                                {{ $journal->work_summary }}
                            </div>
                        </td>
                        <td data-label="Bukti Foto">
                            @if($journal->task_photo_path)
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" data-preview-image data-preview-src="{{ asset('storage/'.$journal->task_photo_path) }}" data-preview-title="Foto Jurnal - {{ optional($journal->date)->format('d M Y') }}">
                                    <i class="bi bi-image me-1"></i> Lihat Foto
                                </button>
                            @else
                                <span class="text-muted small">Tanpa Foto</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Belum ada jurnal yang tersimpan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $journals->links() }}
    </div>
</div>

<div class="modal fade modal-form modal-lg" id="journalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title">Tulis Jurnal Harian</h5>
                    <p class="text-muted mb-0 small">Masukkan ringkasan kegiatan dan unggah foto bukti kerja jika ada.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('student.journals.store') }}" method="POST" enctype="multipart/form-data" class="d-grid gap-3">
                    @csrf
                    <input type="hidden" name="_modal" value="journalModal">
                    <div>
                        <label class="form-label fw-semibold">Ringkasan Kegiatan & Hasil Kerja</label>
                        <textarea name="work_summary" rows="6" class="form-control {{ $journalOld && $errors->has('work_summary') ? 'is-invalid' : '' }}" placeholder="Jelaskan secara spesifik apa saja yang dikerjakan hari ini..." required>{{ old('work_summary') }}</textarea>
                        @if($journalOld && $errors->has('work_summary')) <div class="invalid-feedback d-block">{{ $errors->first('work_summary') }}</div> @endif
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Foto Bukti Tugas / Aktivitas (Opsional)</label>
                        <input type="file" name="task_photo" accept="image/*" class="form-control {{ $journalOld && $errors->has('task_photo') ? 'is-invalid' : '' }}">
                        <div class="form-text small text-muted mt-1">Format: JPG, PNG, JPEG. Ukuran file maksimal 2 MB.</div>
                        @if($journalOld && $errors->has('task_photo')) <div class="invalid-feedback d-block">{{ $errors->first('task_photo') }}</div> @endif
                    </div>
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-klik-primary rounded-pill py-2 fw-bold px-4">
                            <i class="bi bi-check2 me-1"></i> Simpan Jurnal
                        </button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill py-2 px-4" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
