@extends('layouts.app')

@section('title', 'Penilaian')
@section('body_class', 'admin-grades-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/admin-grades-index.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/pages/admin-grades-index.js') }}" defer></script>
@endpush

@section('content')
@php
    $gradeEditOld = old('_modal') === 'grade-edit';
    $gradeEditAction = $gradeEditOld && old('grade_id') ? route('admin.grades.update', old('grade_id')) : '';
@endphp
<div class="soft-card p-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Penilaian Siswa</h1>
            <p class="text-muted small mb-0">Kelola nilai akhir dan umpan balik siswa magang.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-mobile-cards align-middle mb-0">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Nilai Akhir</th>
                    <th>Feedback Pembimbing</th>
                    <th>Diperbarui</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $grade)
                    <tr>
                        <td data-label="Siswa">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm rounded-circle bg-light border d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($grade->user?->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $grade->user?->name }}</div>
                                    <div class="text-muted small">{{ $grade->user?->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Nilai Akhir">
                            @if($grade->final_score !== null)
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1-5 fw-bold fs-6">
                                    {{ number_format($grade->final_score, 2) }}
                                </span>
                            @else
                                <span class="badge bg-light text-muted border rounded-pill px-3 py-1-5">Belum dinilai</span>
                            @endif
                        </td>
                        <td data-label="Feedback">
                            <div class="text-muted small lh-base" style="max-width: 400px;">
                                {{ $grade->feedback ? \Illuminate\Support\Str::limit($grade->feedback, 120) : '-' }}
                            </div>
                        </td>
                        <td data-label="Diperbarui">
                            <span class="text-muted small">
                                {{ $grade->updated_at?->format('d M Y') }}
                            </span>
                        </td>
                        <td data-label="Aksi" class="text-end">
                            <button
                                type="button"
                                class="btn btn-sm btn-action-edit"
                                data-bs-toggle="modal"
                                data-bs-target="#gradeEditModal"
                                data-grade-id="{{ $grade->id }}"
                                data-grade-score="{{ $grade->final_score }}"
                                data-grade-feedback='@json($grade->feedback)'
                                data-grade-update-url="{{ route('admin.grades.update', $grade) }}"
                                data-grade-student-name="{{ $grade->user?->name }}"
                            >
                                <i class="bi bi-pencil-square me-1"></i> Ubah Nilai
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data nilai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $grades->links() }}</div>
</div>

<div class="modal fade modal-form" id="gradeEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title" data-grade-modal-title>Ubah Nilai</h5>
                    <p class="text-muted mb-0 small" data-grade-modal-subtitle>Perbarui nilai akhir dan feedback siswa magang.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ $gradeEditAction }}" method="POST" class="d-grid gap-3">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_modal" value="grade-edit">
                    <input type="hidden" name="grade_id" data-grade-id-field value="{{ $gradeEditOld ? old('grade_id') : '' }}">
                    <div>
                        <label class="form-label fw-semibold">Nilai Akhir (0 - 100)</label>
                        <input type="number" name="final_score" data-grade-score-field class="form-control {{ $gradeEditOld && $errors->has('final_score') ? 'is-invalid' : '' }}" min="0" max="100" step="0.01" value="{{ $gradeEditOld ? old('final_score') : '' }}" placeholder="Contoh: 88.5">
                        @if($gradeEditOld && $errors->has('final_score')) <div class="invalid-feedback d-block">{{ $errors->first('final_score') }}</div> @endif
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Feedback & Evaluasi Pembimbing</label>
                        <textarea name="feedback" rows="6" data-grade-feedback-field class="form-control {{ $gradeEditOld && $errors->has('feedback') ? 'is-invalid' : '' }}" placeholder="Berikan evaluasi performa, kelebihan, dan saran perbaikan...">{{ $gradeEditOld ? old('feedback') : '' }}</textarea>
                        @if($gradeEditOld && $errors->has('feedback')) <div class="invalid-feedback d-block">{{ $errors->first('feedback') }}</div> @endif
                    </div>
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-klik-primary rounded-pill px-4">
                            <i class="bi bi-check2 me-1"></i> Simpan Nilai
                        </button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
