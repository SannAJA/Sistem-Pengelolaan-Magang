@extends('layouts.app')

@section('title', 'Validasi Absen')
@section('body_class', 'admin-attendance-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/admin-attendance-index.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/pages/admin-attendance-index.js') }}" defer></script>
@endpush

@section('content')
<div class="soft-card p-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Validasi Absen</h1>
            <p class="text-muted small mb-0">Monitoring absensi masuk dan pulang seluruh siswa magang.</p>
        </div>
        <div>
            <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#locationManagerModal">
                <i class="bi bi-geo-alt-fill me-1 text-danger"></i> Kelola Lokasi Cabang ({{ $locations->count() }})
            </button>
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

{{-- MODAL KELOLA LOKASI CABANG (LIST & TAMBAH) --}}
<div class="modal fade" id="locationManagerModal" tabindex="-1" aria-labelledby="locationManagerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-soft">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="locationManagerModalLabel">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> Kelola Lokasi Kantor & Cabang
                    </h5>
                    <p class="text-muted small mb-0">Siswa magang dapat melakukan absensi jika berada di dalam radius salah satu lokasi ini.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                {{-- Form Tambah Lokasi Baru --}}
                <div class="card border rounded-3 p-3 mb-4 bg-light">
                    <div class="fw-bold text-dark mb-2 small text-uppercase tracking-wider">
                        <i class="bi bi-plus-circle text-primary me-1"></i> Tambah Lokasi Cabang Baru
                    </div>
                    <form action="{{ route('admin.locations.store') }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-12 col-md-4">
                            <label class="form-label small text-muted mb-1 fw-semibold">Nama Kantor / Cabang</label>
                            <input type="text" name="name" class="form-control form-control-sm rounded-3" placeholder="Contoh: Cabang Bandung" required>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small text-muted mb-1 fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" class="form-control form-control-sm rounded-3" placeholder="-6.330731" required>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small text-muted mb-1 fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" class="form-control form-control-sm rounded-3" placeholder="106.715682" required>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label small text-muted mb-1 fw-semibold">Radius (m)</label>
                            <input type="number" name="radius_meters" class="form-control form-control-sm rounded-3" value="100" min="10" max="10000" required>
                        </div>
                        <div class="col-12 d-flex justify-content-between align-items-center mt-2 pt-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-get-current-coords>
                                <i class="bi bi-crosshair me-1"></i> Gunakan GPS Saya
                            </button>
                            <button type="submit" class="btn btn-sm btn-klik-primary rounded-pill px-4 fw-semibold">
                                <i class="bi bi-check2 me-1"></i> Simpan Lokasi
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar Lokasi --}}
                <div class="fw-bold text-dark mb-2 small text-uppercase tracking-wider">
                    <i class="bi bi-list-check me-1 text-primary"></i> Daftar Lokasi Aktif ({{ $locations->count() }})
                </div>
                <div class="table-responsive border rounded-3 bg-white">
                    <table class="table align-middle mb-0 table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Cabang</th>
                                <th>Koordinat</th>
                                <th>Radius</th>
                                <th class="text-end pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($locations as $loc)
                                <tr>
                                    <td class="fw-bold text-dark">
                                        <i class="bi bi-building me-1 text-muted"></i> {{ $loc->name }}
                                    </td>
                                    <td>
                                        <code class="text-dark small">{{ number_format($loc->latitude, 6) }}, {{ number_format($loc->longitude, 6) }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill">{{ $loc->radius_meters }} m</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-2 py-0-5 me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#locationEditModal"
                                            data-location-id="{{ $loc->id }}"
                                            data-location-name="{{ $loc->name }}"
                                            data-location-latitude="{{ $loc->latitude }}"
                                            data-location-longitude="{{ $loc->longitude }}"
                                            data-location-radius="{{ $loc->radius_meters }}"
                                            data-location-update-url="{{ route('admin.locations.update', $loc) }}"
                                            title="Ubah Lokasi"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.locations.destroy', $loc) }}" method="POST" class="d-inline" data-confirm="Yakin ingin menghapus lokasi cabang {{ $loc->name }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-0-5" title="Hapus Lokasi">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Belum ada lokasi kantor/cabang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT LOKASI CABANG --}}
<div class="modal fade" id="locationEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-soft">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" data-edit-location-title>Ubah Lokasi Cabang</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" method="POST" class="d-grid gap-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label small text-muted mb-1 fw-semibold">Nama Kantor / Cabang</label>
                        <input type="text" name="name" class="form-control rounded-3" data-edit-location-name required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1 fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" class="form-control rounded-3" data-edit-location-latitude required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted mb-1 fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" class="form-control rounded-3" data-edit-location-longitude required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label small text-muted mb-1 fw-semibold">Batas Radius Maksimal (Meter)</label>
                        <input type="number" name="radius_meters" class="form-control rounded-3" min="10" max="10000" data-edit-location-radius required>
                        <div class="form-text small text-muted">Contoh: 100 meter dari titik koordinat.</div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-get-current-coords>
                            <i class="bi bi-crosshair me-1"></i> Gunakan GPS Saya
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-klik-primary rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
