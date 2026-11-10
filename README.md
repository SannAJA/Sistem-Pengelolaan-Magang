# Sistem Pengelolaan Magang

Starter project Laravel 11 untuk PT Klik Media Nusantara.

## Fitur yang sudah disiapkan

- Login centered box sesuai branding
- Dashboard admin dan siswa
- Absensi GPS dengan validasi radius 50 meter
- Jurnal harian dengan upload foto tugas
- Monitoring admin untuk absen, jurnal, dan penilaian
- Middleware role `admin` dan `siswa`
- Skema database utama: `users`, `attendances`, `journals`, `grades`

## Konfigurasi penting

Tambahkan nilai berikut ke `.env`:

```env
KLIK_OFFICE_LATITUDE=-6.200000
KLIK_OFFICE_LONGITUDE=106.816666
KLIK_ATTENDANCE_RADIUS=50
```

## Akun contoh

- Admin: `admin@klikmedia.test`
- Siswa: `siswa@klikmedia.test`
- Password: `password123, siswa123`
