<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Sistem Pengelolaan Magang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    @stack('styles')
</head>
<body class="@yield('body_class')" data-auto-open-modal="{{ old('_modal') }}">
@php($user = auth()->user())
<div class="app-layout d-flex flex-column min-vh-100">
    <!-- TOP NAVIGATION BAR (Desktop & Mobile Header) -->
    <header class="app-navbar navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-3 px-md-4 px-xl-5">
            
            <!-- HEADER MOBILE: Garis Tiga Bertumpuk (Hamburger) & Profil (< 992px) -->
            <div class="d-flex align-items-center justify-content-between w-100 d-lg-none">
                <!-- Tombol Garis Tiga Bertumpuk (Geser Kiri-ke-Kanan) -->
                <button type="button" class="mobile-hamburger-btn btn p-0 border-0 d-flex align-items-center justify-content-center" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebarOffcanvas" aria-label="Buka Navigasi">
                    <i class="bi bi-list text-dark" style="font-size: 1.85rem;"></i>
                </button>

                <!-- Tombol Profil Pengguna Kanan Atas Mobile -->
                <button type="button" class="mobile-profile-btn btn p-0 border-0 d-flex align-items-center justify-content-center" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebarOffcanvas" aria-label="Profil Pengguna">
                    <div class="avatar-circle-sm bg-klik-blue text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.9rem; border: 2px solid #ffffff; box-shadow: 0 2px 8px rgba(0,0,0,0.12);">
                        {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                    </div>
                </button>
            </div>

            <!-- DESKTOP HEADER & LOGO (Hanya Tampil di Desktop >= 992px) -->
            <a class="navbar-brand d-none d-lg-flex align-items-center gap-2 py-1 me-auto me-lg-4" href="{{ route('dashboard') }}" title="PT Klik Media Nusantara">
                <div class="navbar-logo-wrap">
                    <img src="{{ asset('assets/images/klik-media1.png') }}" alt="PT Klik Media Nusantara" class="navbar-logo-img">
                </div>
            </a>

            <!-- DESKTOP NAVIGASI UTAMA (Hanya Tampil di Desktop >= 992px) -->
            <div class="collapse navbar-collapse d-none d-lg-flex" id="appNavbarMenu">
                <ul class="navbar-nav me-auto mb-0 gap-1 align-items-center flex-nowrap py-0">
                    <li class="nav-item">
                        <a class="nav-link top-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    @if($user?->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link top-nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                                <i class="bi bi-people-fill"></i>
                                <span>Kelola Siswa</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link top-nav-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}" href="{{ route('admin.attendance.index') }}">
                                <i class="bi bi-calendar2-check-fill"></i>
                                <span>Validasi Absen</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link top-nav-link {{ request()->routeIs('admin.journals.*') ? 'active' : '' }}" href="{{ route('admin.journals.index') }}">
                                <i class="bi bi-journal-bookmark-fill"></i>
                                <span>Monitoring Jurnal</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link top-nav-link {{ request()->routeIs('admin.grades.*') ? 'active' : '' }}" href="{{ route('admin.grades.index') }}">
                                <i class="bi bi-award-fill"></i>
                                <span>Penilaian</span>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link top-nav-link {{ request()->routeIs('student.attendance.*') ? 'active' : '' }}" href="{{ route('student.attendance.index') }}">
                                <i class="bi bi-calendar2-check-fill"></i>
                                <span>Absen Harian</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link top-nav-link {{ request()->routeIs('student.journals.*') ? 'active' : '' }}" href="{{ route('student.journals.index') }}">
                                <i class="bi bi-journal-text"></i>
                                <span>Jurnal Harian</span>
                            </a>
                        </li>
                    @endif
                </ul>

                <!-- Desktop User Section Kanan Atas -->
                <div class="navbar-user-section d-flex flex-column flex-xl-row align-items-stretch align-items-xl-center gap-2 gap-xl-3 pt-3 pt-xl-0 border-top border-xl-0 mt-2 mt-xl-0">
                    @if($user?->role === 'siswa')
                        <div class="user-entry-badge user-entry-clickable d-flex align-items-center gap-2 px-3 py-1-5 rounded-pill" data-bs-toggle="modal" data-bs-target="#studentProfileModal" role="button" tabindex="0" title="Klik untuk mengelola profil & password Anda">
                            <div class="user-entry-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="user-entry-info me-1">
                                <div class="user-entry-label d-flex align-items-center gap-1">
                                    <span>Masuk sebagai:</span>
                                    <i class="bi bi-pencil-square text-primary" style="font-size: 0.7rem;" title="Klik untuk ganti password"></i>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold user-entry-name text-truncate">{{ $user?->name }}</span>
                                    <span class="badge badge-role-student rounded-pill">Siswa</span>
                                </div>
                            </div>
                            <i class="bi bi-gear-fill text-muted opacity-50 ms-auto" style="font-size: 0.85rem;"></i>
                        </div>
                    @else
                        <div class="user-entry-badge d-flex align-items-center gap-2 px-3 py-1-5 rounded-pill">
                            <div class="user-entry-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="user-entry-info">
                                <div class="user-entry-label">Masuk sebagai:</div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold user-entry-name text-truncate">{{ $user?->name }}</span>
                                    <span class="badge badge-role-admin rounded-pill">Admin</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="mb-0" data-confirm="Apakah Anda yakin ingin keluar dari sistem magang?" data-confirm-title="Konfirmasi Keluar" data-confirm-btn="Ya, Keluar">
                        @csrf
                        <button type="submit" class="btn btn-logout rounded-pill px-3 py-1-5 w-100" title="Keluar dari akun">
                            <i class="bi bi-box-arrow-right me-1"></i> <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT (Tampilan Lega & Responsif) -->
    <main class="main-content flex-grow-1 container-fluid px-3 px-sm-4 px-xl-5 py-3 py-lg-5">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center gap-3 p-3 mb-4">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <div class="fw-medium">{{ session('success') }}</div>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 d-flex align-items-start gap-3 p-3 mb-4">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger mt-1"></i>
                <div>
                    <div class="fw-bold mb-1">Terdapat kendala:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- OFFCANVAS SIDEBAR NAVIGATION FOR MOBILE (Geser Kiri ke Kanan) -->
<div class="offcanvas offcanvas-start border-0 shadow-lg d-lg-none" tabindex="-1" id="mobileSidebarOffcanvas" aria-labelledby="mobileSidebarOffcanvasLabel" style="width: 85vw; max-width: 320px;">
    <div class="offcanvas-header border-bottom py-3 px-4 bg-white">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('assets/images/klik-media1.png') }}" alt="PT Klik Media Nusantara" style="max-height: 40px; width: auto;">
        </div>
        <button type="button" class="btn-close shadow-none p-2" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>
    
    <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
        <div>
            <!-- Banner Profil Pengguna di Drawer -->
            <div class="p-4 text-center text-white position-relative" style="background: linear-gradient(135deg, var(--klik-blue) 0%, #005a70 100%);">
                <div class="avatar-circle-lg mx-auto mb-2 bg-white text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold fs-3 shadow-sm" style="width: 64px; height: 64px; border: 3px solid rgba(255,255,255,0.8);">
                    {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                </div>
                <div class="fw-bold fs-6 mb-1 text-truncate px-2">{{ $user?->name }}</div>
                <span class="badge bg-white text-dark rounded-pill px-3 py-1 fw-bold text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">
                    {{ ucfirst($user?->role) }}
                </span>
                <div class="small opacity-75 mt-2 text-truncate px-2" style="font-size: 0.78rem;">
                    <i class="bi bi-envelope me-1"></i>{{ $user?->email }}
                </div>
                @if($user?->role === 'siswa')
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-light rounded-pill px-3 py-1 text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#studentProfileModal" data-bs-dismiss="offcanvas">
                            <i class="bi bi-key-fill me-1 text-primary"></i> Ubah Password
                        </button>
                    </div>
                @endif
            </div>

            <!-- Daftar Navigation Menu Links -->
            <div class="p-3">
                <div class="text-uppercase fw-bold text-muted px-3 mb-2" style="font-size: 0.68rem; letter-spacing: 0.08em;">Menu Navigasi</div>
                <div class="list-group list-group-flush gap-1">
                    <a href="{{ route('dashboard') }}" class="mobile-drawer-link list-group-item list-group-item-action border-0 rounded-3 px-3 py-2-5 d-flex align-items-center gap-3 {{ request()->routeIs('dashboard') ? 'active-drawer' : '' }}">
                        <i class="bi bi-grid-1x2-fill fs-5"></i>
                        <span class="fw-semibold">Dashboard</span>
                    </a>

                    @if($user?->role === 'admin')
                        <a href="{{ route('admin.students.index') }}" class="mobile-drawer-link list-group-item list-group-item-action border-0 rounded-3 px-3 py-2-5 d-flex align-items-center gap-3 {{ request()->routeIs('admin.students.*') ? 'active-drawer' : '' }}">
                            <i class="bi bi-people-fill fs-5"></i>
                            <span class="fw-semibold">Kelola Siswa</span>
                        </a>
                        <a href="{{ route('admin.attendance.index') }}" class="mobile-drawer-link list-group-item list-group-item-action border-0 rounded-3 px-3 py-2-5 d-flex align-items-center gap-3 {{ request()->routeIs('admin.attendance.*') ? 'active-drawer' : '' }}">
                            <i class="bi bi-calendar2-check-fill fs-5"></i>
                            <span class="fw-semibold">Validasi Absen</span>
                        </a>
                        <a href="{{ route('admin.journals.index') }}" class="mobile-drawer-link list-group-item list-group-item-action border-0 rounded-3 px-3 py-2-5 d-flex align-items-center gap-3 {{ request()->routeIs('admin.journals.*') ? 'active-drawer' : '' }}">
                            <i class="bi bi-journal-bookmark-fill fs-5"></i>
                            <span class="fw-semibold">Monitoring Jurnal</span>
                        </a>
                        <a href="{{ route('admin.grades.index') }}" class="mobile-drawer-link list-group-item list-group-item-action border-0 rounded-3 px-3 py-2-5 d-flex align-items-center gap-3 {{ request()->routeIs('admin.grades.*') ? 'active-drawer' : '' }}">
                            <i class="bi bi-award-fill fs-5"></i>
                            <span class="fw-semibold">Penilaian</span>
                        </a>
                    @else
                        <a href="{{ route('student.attendance.index') }}" class="mobile-drawer-link list-group-item list-group-item-action border-0 rounded-3 px-3 py-2-5 d-flex align-items-center gap-3 {{ request()->routeIs('student.attendance.*') ? 'active-drawer' : '' }}">
                            <i class="bi bi-calendar2-check-fill fs-5"></i>
                            <span class="fw-semibold">Absen Harian</span>
                        </a>
                        <a href="{{ route('student.journals.index') }}" class="mobile-drawer-link list-group-item list-group-item-action border-0 rounded-3 px-3 py-2-5 d-flex align-items-center gap-3 {{ request()->routeIs('student.journals.*') ? 'active-drawer' : '' }}">
                            <i class="bi bi-journal-text fs-5"></i>
                            <span class="fw-semibold">Jurnal Harian</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Logout Button di Bagian Bawah Drawer -->
        <div class="p-3 border-top bg-light">
            <form action="{{ route('logout') }}" method="POST" class="mb-0" data-confirm="Apakah Anda yakin ingin keluar dari sistem magang?" data-confirm-title="Konfirmasi Keluar" data-confirm-btn="Ya, Keluar">
                @csrf
                <button type="submit" class="btn btn-logout rounded-pill py-2-5 w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-right fs-5"></i> <span>Keluar Dari Sistem</span>
                </button>
            </form>
        </div>
    </div>
</div>

@if($user?->role === 'siswa')
<div class="modal fade modal-form" id="studentProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title">Profil & Pengaturan Password</h5>
                    <p class="text-muted mb-0 small">Perbarui password akun Anda.</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="card border-0 bg-light rounded-4 p-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle-md bg-klik-blue text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 48px; height: 48px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-6">{{ $user->name }}</div>
                            <div class="small text-muted">{{ $user->email }}</div>
                            <span class="badge badge-role-student rounded-pill mt-1">Siswa Magang</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('student.profile.password.update') }}" method="POST" class="d-grid gap-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="form-label fw-semibold">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password" id="studentNewPassword" class="form-control" placeholder="Minimal 8 karakter" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password-btn" data-target="studentNewPassword" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="studentConfirmPassword" class="form-control" placeholder="Ketik ulang password baru" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password-btn" data-target="studentConfirmPassword" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-klik-primary rounded-pill px-4">
                            <i class="bi bi-shield-lock me-1"></i> Simpan Password Baru
                        </button>
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-soft">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="imagePreviewTitle">Pratinjau Gambar</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-3 p-md-4 text-center">
                <img id="imagePreviewSource" src="" alt="Pratinjau" class="img-fluid rounded-4 shadow-sm w-100 modal-preview-img">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/base.js') }}" defer></script>
@stack('scripts')
</body>
</html>

