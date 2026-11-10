## 📌 1. Ringkasan Proyek
- **Nama Proyek:** Sistem Pengelolaan Magang
- **Perusahaan / Instansi:** PT Klik Media Nusantara
- **Target Pengguna:** Admin (Pembimbing Lapangan) & Siswa (Anak Magang)
- **Gaya & Bahasa:** Profesional, bersih, modern, langsung pada poinnya, serta menggunakan emoji secara minim & pas (tidak lebay).

---

## 💻 2. Spesifikasi Tech Stack
- **Backend Framework:** PHP Laravel 11 (atau versi stabil terbaru)
- **Frontend Framework:** Bootstrap 5 (diintegrasikan via Vite atau CDN)
- **Scripting & Interaktivitas:** Vanilla JavaScript (ES6+ / Fetch API / AJAX)
- **Tipografi / Font:** Plus Jakarta Sans (Google Fonts)
- **Database:** MySQL / MariaDB

---

## 🎨 3. Sistem Desain UI/UX & Aturan Tema

### 3.1 Palet Warna Brand (Klik Media Nusantara)
Gunakan variabel warna HEX berikut secara konsisten di CSS, kustomisasi Bootstrap, dan komponen UI:

```css
:root {
  --klik-red: #D11018;        /* Tombol Utama (CTA), Absen Masuk, Status Aktif */
  --klik-red-hover: #A50D13;  /* Efek Hover Tombol Utama */
  --klik-black: #0D0E10;      /* Background Sidebar, Wadah Logo, Panel Gelap */
  --klik-blue: #007B98;       /* Aksen Header, Badge Informasi, Tombol Sekunder */
  --klik-blue-hover: #006076; /* Efek Hover Aksen */
  --klik-bg: #F8FAFC;         /* Background Utama Halaman */
  --klik-white: #FFFFFF;      /* Card, Form, Modal */
  --klik-text-main: #1E293B;  /* Teks Utama (Slate Dark) */
  --klik-text-muted: #64748B; /* Teks Subtitle dan Placeholder */
}
3.2 Tipografi
Font Utama: 'Plus Jakarta Sans', sans-serif

Pengaturan CSS Global:

CSS
@import url('[https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap](https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap)');

body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background-color: var(--klik-bg);
  color: var(--klik-text-main);
}
3.3 Aturan Layout Halaman Login
Struktur Layout: Wajib menggunakan posisi Centered Box di tengah layar (min-vh-100 d-flex align-items-center justify-content-center bg-light).

Gaya Kartu (Box): Kartu serba putih dengan sudut melengkung dan bayangan halus (card shadow-lg border-0 rounded-4 p-4), batas lebar maksimal sekitar 420px.

Header Branding:

Tampilkan teks/logo KLIK MEDIA NUSANTARA dengan warna brand (#D11018 untuk "KLIK MEDIA" dan #007B98 untuk "NUSANTARA").

Gaya Teks UI: Ringkas dan profesional dengan emoji yang pas.

Contoh Judul: "Selamat Datang 👋"

Contoh Subjudul: "Silakan masuk ke akun magang Anda"

⚙️ 4. Logika & Kebutuhan Fitur Utama
4.1 Absensi GPS Pintar (Radius 50 Meter)
Interaktivitas Frontend (JavaScript):

Ambil lokasi pengguna via HTML5 Geolocation API (navigator.geolocation.getCurrentPosition()).

Kirim koordinat (latitude, longitude) via fetch() ke API endpoint Laravel.

Logika Backend (Laravel Controller):

Hitung jarak koordinat pengguna dengan lokasi kantor Klik Media Nusantara (menggunakan rumus Haversine).

Kondisi Validasi:

Jika jarak <= 50 meter: Aktifkan tombol "Absen Masuk / Pulang" dan tampilkan badge hijau (📍 Dalam Radius (XX meter)).

Jika jarak > 50 meter: Nonaktifkan tombol absen dan tampilkan peringatan (⚠️ Di Luar Radius Office (XX meter)).

Aturan Keamanan: Selalu lakukan validasi ulang jarak koordinat di backend Laravel sebelum menyimpan data absensi ke database.

4.2 Jurnal Harian & Lampiran Foto Tugas (Bukti Kerja)
Tampilan Form Siswa:

<textarea> standar untuk mengisi ringkasan kegiatan harian.

Input file (<input type="file" name="task_photo" accept="image/*" class="form-control">) untuk unggah foto bukti kerja.

Pemrosesan File Backend (Laravel):

Validasi format dan ukuran gambar: image|mimes:jpeg,png,jpg|max:2048.

Simpan gambar secara aman menggunakan Storage::disk('public')->put('journals', $file).

Tampilan Monitoring Admin:

Dashboard admin menampilkan teks jurnal siswa berdampingan dengan thumbnail foto.

Jika thumbnail diklik, tampilkan gambar penuh menggunakan Modal Bootstrap.

🛠️ 5. Standar Arsitektur Laravel & Database
5.1 Proteksi Rute & Middleware
auth: Proteksi login umum.

role:admin: Membatasi akses khusus Dashboard Admin, Kelola Siswa (CRUD), Validasi Absen, dan Penilaian.

role:siswa: Membatasi akses khusus Portal Siswa (Absen, Isi Jurnal, Lihat Rekap Diri).

5.2 Skema Tabel Database Utama
Tabel users:

id, name, email, password, role (enum: ['admin', 'siswa']), timestamps

Tabel attendances:

id, user_id, date, clock_in, clock_out, latitude, longitude, is_within_radius (boolean), status (enum: ['hadir', 'izin', 'sakit']), timestamps

Tabel journals:

id, user_id, date, work_summary (text), task_photo_path (string, nullable), timestamps

Tabel grades:

id, user_id, final_score (decimal), feedback (text), timestamps

⛔ 6. Batasan & Larangan (Guardrails)
Dilarang Pakai Hitam Pekat: Jangan gunakan warna hitam murni #000000 untuk teks; gunakan warna slate gelap #1E293B atau #0D0E10.

Validasi Server-Side Wajib: Jangan hanya mengandalkan JavaScript untuk mengecek radius GPS atau foto; selalu lakukan validasi di Controller / Form Request Laravel.

Bahasa UI Tidak Lebay: Jaga agar pilihan kata pada UI tetap profesional, rapi, dan fungsional.

Kualitas Kode JS: Pakai sintaks JavaScript modern (async/await Fetch API) saat memanggil route Laravel.