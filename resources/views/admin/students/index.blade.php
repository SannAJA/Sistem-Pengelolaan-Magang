@extends('layouts.app')

@section('title', 'Kelola Siswa')
@section('body_class', 'admin-students-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/admin-students-index.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/pages/admin-students-index.js') }}" defer></script>
@endpush

@section('content')
@php
    $studentCreateOld = old('_modal') === 'student-create';
    $studentEditOld = old('_modal') === 'student-edit';
    $studentEditAction = $studentEditOld && old('student_id') ? route('admin.students.update', old('student_id')) : '';
@endphp
<div class="soft-card p-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Kelola Siswa</h1>
            <p class="text-muted small mb-0">Daftar siswa magang yang terdaftar di sistem.</p>
        </div>
        <button type="button" class="btn btn-klik-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#studentCreateModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Siswa
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-mobile-cards align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td data-label="Nama Siswa">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm rounded-circle bg-light border d-flex align-items-center justify-content-center fw-bold text-dark" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div class="fw-bold text-dark">{{ $student->name }}</div>
                            </div>
                        </td>
                        <td data-label="Email">{{ $student->email }}</td>
                        <td data-label="Password">
                            @if($student->plain_password)
                                <div class="d-inline-flex align-items-center gap-1 bg-light border px-2 py-1 rounded-3">
                                    <span class="font-monospace small text-dark student-password-masked" data-plain="{{ $student->plain_password }}" style="letter-spacing: 0.1em;">••••••••</span>
                                    <button type="button" class="btn btn-sm btn-link text-muted p-0 ms-1 toggle-table-password" title="Lihat/Sembunyikan Password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill" style="font-size: 0.7rem;">(Dihash)</span>
                            @endif
                        </td>
                        <td data-label="Role"><span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-1-5">Siswa</span></td>
                        <td data-label="Terdaftar">{{ $student->created_at?->format('d M Y') }}</td>
                        <td data-label="Aksi" class="text-end">
                            <div class="d-inline-flex gap-2">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-action-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#studentEditModal"
                                    data-student-id="{{ $student->id }}"
                                    data-student-name="{{ $student->name }}"
                                    data-student-email="{{ $student->email }}"
                                    data-student-plain-password="{{ $student->plain_password ?? '' }}"
                                    data-student-update-url="{{ route('admin.students.update', $student) }}"
                                >
                                    <i class="bi bi-pencil-square me-1"></i> Ubah
                                </button>
                                <form
                                    action="{{ route('admin.students.destroy', $student) }}"
                                    method="POST"
                                    class="mb-0"
                                    data-confirm="Apakah Anda yakin ingin menghapus data siswa '{{ $student->name }}'? Tindakan ini akan menghapus seluruh rekaman terkait."
                                    data-confirm-title="Hapus Data Siswa"
                                    data-confirm-btn="Ya, Hapus Siswa"
                                    data-confirm-type="danger"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-action-delete">
                                        <i class="bi bi-trash3 me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $students->links() }}</div>
</div>

<div class="modal fade modal-form" id="studentCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title">Tambah Siswa Baru</h5>
                    <p class="text-muted mb-0 small">Masukkan data akun siswa magang baru.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.students.store') }}" method="POST" class="d-grid gap-3">
                    @csrf
                    <input type="hidden" name="_modal" value="student-create">
                    <div>
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control {{ $studentCreateOld && $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $studentCreateOld ? old('name') : '' }}" placeholder="Nama siswa" required>
                        @if($studentCreateOld && $errors->has('name')) <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div> @endif
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" name="email" class="form-control {{ $studentCreateOld && $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $studentCreateOld ? old('email') : '' }}" placeholder="siswa@email.com" required>
                        @if($studentCreateOld && $errors->has('email')) <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div> @endif
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Password Akun</label>
                        <div class="input-group">
                            <input type="password" name="password" id="adminCreateStudentPassword" class="form-control {{ $studentCreateOld && $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Minimal 8 karakter" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password-btn" data-target="adminCreateStudentPassword" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @if($studentCreateOld && $errors->has('password')) <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div> @endif
                    </div>
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-klik-primary rounded-pill px-4">
                            <i class="bi bi-check2 me-1"></i> Simpan Siswa
                        </button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-form" id="studentEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title" data-student-modal-title>Ubah Data Siswa</h5>
                    <p class="text-muted mb-0 small">Perbarui data akun siswa magang.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ $studentEditAction }}" method="POST" class="d-grid gap-3">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_modal" value="student-edit">
                    <input type="hidden" name="student_id" data-student-id-field value="{{ $studentEditOld ? old('student_id') : '' }}">
                    <div>
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" data-student-name-field class="form-control {{ $studentEditOld && $errors->has('name') ? 'is-invalid' : '' }}" value="{{ $studentEditOld ? old('name') : '' }}" required>
                        @if($studentEditOld && $errors->has('name')) <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div> @endif
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" name="email" data-student-email-field class="form-control {{ $studentEditOld && $errors->has('email') ? 'is-invalid' : '' }}" value="{{ $studentEditOld ? old('email') : '' }}" required>
                        @if($studentEditOld && $errors->has('email')) <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div> @endif
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Password Baru (Opsional)</label>
                        <div class="input-group">
                            <input type="password" name="password" id="adminEditStudentPassword" data-student-password-field class="form-control {{ $studentEditOld && $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Kosongkan jika tidak ingin diubah">
                            <button type="button" class="btn btn-outline-secondary toggle-password-btn" data-target="adminEditStudentPassword" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="form-text small text-muted mt-1.5 d-flex align-items-center gap-1">
                            <span>Password terdaftar saat ini:</span>
                            <span data-student-current-password-text data-plain="" class="font-monospace fw-bold text-dark ms-1">••••••••</span>
                            <button type="button" class="btn btn-sm btn-link p-0 text-muted ms-1 toggle-modal-current-password" title="Lihat/Sembunyikan Password Saat Ini">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @if($studentEditOld && $errors->has('password')) <div class="invalid-feedback d-block">{{ $errors->first('password') }}</div> @endif
                    </div>
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-klik-primary rounded-pill px-4">
                            <i class="bi bi-check2 me-1"></i> Simpan Perubahan
                        </button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
