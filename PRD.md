# PRD: LMS Berbasis Web untuk SMA (PHP Native + MySQL)

> Sumber: Proposal Penawaran No. 001/VS-PNW/IX/2026 (PT. Vantage Sejahtera → SMAN 10 Garut).
> Dokumen ini adalah acuan utama untuk implementasi. Kalau ada konflik dengan asumsi lain, PRD ini yang menang. Hal yang tidak ada di proposal ditandai **[ASUMSI]**.

---

## 1. Ringkasan

LMS ringan untuk satu sekolah SMA: materi, tugas, kuis pilihan ganda, rekap nilai, dan pengumuman dalam satu platform. Akun dibuat admin sekolah (tanpa akun Google), struktur mengikuti kelas / tingkat / mata pelajaran / guru pengampu, tampilan responsif untuk HP.

**Stack wajib:** PHP 8.2 native (OOP, pola MVC, **tanpa framework**), MySQL/MariaDB via PDO, Bootstrap 5, JavaScript vanilla. Jalan di XAMPP (dev) dan shared hosting/VPS (demo).

## 2. Tujuan & Non-Tujuan

**Tujuan**
1. Tiga peran (admin, guru, siswa) dengan hak akses ketat.
2. 12 fitur Wajib (F01–F12) selesai dan lolos black box test. F13–F14 opsional.
3. Aman: tahan SQL injection, XSS, CSRF, dan upload berbahaya.
4. Kode rapi MVC agar bisa dinilai sebagai penerapan materi Pemrograman Web 2.

**Non-Tujuan (jangan dibangun)**
- Video conference / kelas live
- Aplikasi mobile native
- Chat real-time, forum diskusi
- Notifikasi email / WhatsApp
- Integrasi Dapodik / sistem rapor
- Soal esai dengan penilaian otomatis
- Multi-sekolah (multi-tenant)

## 3. Peran & Hak Akses

| Peran | Siapa | Boleh |
|---|---|---|
| Admin | Staf TU | Kelola pengguna, kelas, mapel, tahun ajaran, guru pengampu |
| Guru | Pengajar | Kelola materi, tugas, kuis, nilai, pengumuman **hanya pada kelas+mapel yang diampu** |
| Siswa | Kelas X–XII | Lihat/unduh materi, kumpulkan tugas, kerjakan kuis, lihat nilai & pengumuman **hanya kelasnya sendiri** |

Semua peran: login, logout, ganti password.

Aturan tegas: akses ditolak di **server** (router + controller), bukan hanya menyembunyikan menu. Akses lintas peran / lintas kelas → halaman "Akses Ditolak" (HTTP 403).

## 4. Kebutuhan Fungsional

Prioritas: **W** = Wajib, **O** = Opsional (dilepas kalau waktu habis).

### F01 Login, logout, ganti password (Semua, W)
- Login pakai username + password. Verifikasi `password_verify()`. Pesan gagal generik ("Username atau password salah").
- Akun `status = nonaktif` tidak bisa login.
- Setelah login redirect ke dashboard sesuai peran. `session_regenerate_id(true)` saat login. Logout menghancurkan sesi.
- Ganti password: wajib password lama, minimal 8 karakter, konfirmasi harus cocok.
- **[ASUMSI]** Rate limit sederhana: 5 gagal berturut-turut → tahan login 5 menit (berbasis sesi/IP di tabel atau sesi).

### F02 Kelola pengguna (Admin, W)
- Tambah, ubah, nonaktifkan (soft, bukan hapus), cari, filter per peran/kelas, paginasi.
- Reset password oleh admin.
- Siswa wajib punya `kelas_id`; guru dan admin tidak.
- **Impor CSV** siswa. **[ASUMSI]** format kolom: `nama,username,password,kelas` (kolom `kelas` = nama kelas yang sudah ada). Validasi per baris, laporkan baris yang gagal (nomor baris + alasan), baris valid tetap masuk (satu transaksi per file, atau lewati baris gagal, tampilkan ringkasan).
- Username unik.

### F03 Kelola kelas, mapel, tahun ajaran (Admin, W)
- CRUD tahun ajaran (nama, semester ganjil/genap, `aktif`). **Hanya satu tahun ajaran aktif** pada satu waktu (mengaktifkan satu menonaktifkan yang lain).
- CRUD kelas (nama, tingkat X/XI/XII, tahun ajaran).
- CRUD mapel (kode unik, nama).
- Hapus ditolak bila masih dipakai data lain (tampilkan alasan).

### F04 Tetapkan guru pengampu (Admin, W)
- Pasangan (guru, kelas, mapel). Unik per (kelas, mapel): satu mapel di satu kelas hanya satu guru pengampu.
- Pengampu inilah yang menjadi "konteks" semua konten (materi, tugas, kuis, pengumuman).

### F05 Upload, ubah, hapus materi (Guru, W)
- Pilih salah satu pengampu miliknya, isi judul, deskripsi, unggah file.
- Ubah judul/deskripsi/ganti file. Hapus menghapus record + file fisik.
- Validasi file: ekstensi `pdf, docx, pptx, jpg, jpeg, png`, MIME via `finfo`, maks **10 MB**.

### F06 Lihat dan unduh materi (Siswa, W)
- Siswa melihat daftar mapel kelasnya → daftar materi per mapel → unduh.
- Unduh lewat controller yang mengecek hak akses (tidak ada link langsung ke `uploads/`).

### F07 Buat tugas (Guru, W)
- Judul, deskripsi, lampiran opsional (aturan file sama), **tenggat** (datetime).
- Ubah/hapus tugas. Hapus tugas dengan pengumpulan ditolak atau meminta konfirmasi eksplisit **[ASUMSI: tolak]**.

### F08 Kumpulkan tugas (Siswa, W)
- Upload file (aturan file sama). Sistem mencatat `dikumpulkan_at` dan status otomatis: `tepat_waktu` jika ≤ tenggat, `terlambat` jika > tenggat. Pengumpulan terlambat **tetap diterima**.
- **[ASUMSI]** Satu pengumpulan per siswa per tugas. Siswa boleh mengumpulkan ulang (menimpa file lama, waktu & status dihitung ulang) **selama belum dinilai**. Setelah dinilai, terkunci.
- Siswa melihat status: belum mengumpulkan / tepat waktu / terlambat / sudah dinilai.

### F09 Nilai tugas & komentar (Guru, W)
- Guru melihat daftar siswa kelas itu per tugas (termasuk yang belum mengumpulkan), unduh file, isi nilai 0–100 dan komentar. Boleh ubah nilai.

### F10 Buat kuis pilihan ganda (Guru, W)
- Kuis: judul, `durasi_menit`, jendela `mulai` dan `selesai`.
- Soal: pertanyaan, opsi A–E (minimal A–B terisi, **[ASUMSI]** C–E boleh kosong), kunci jawaban (A–E, harus opsi yang terisi).
- CRUD soal. Kuis yang sudah ada hasil siswa: soal tidak boleh diubah/hapus **[ASUMSI]**.

### F11 Kerjakan kuis & nilai otomatis (Siswa, W)
- Hanya bisa dimulai dalam jendela `mulai`–`selesai`. **Satu percobaan per siswa per kuis.**
- Saat mulai: buat `hasil_kuis` (`mulai_at = NOW()`). Batas pengerjaan siswa = `MIN(mulai_at + durasi_menit, kuis.selesai)`.
- Countdown di browser (hitung dari waktu server, bukan jam klien). Jawaban disimpan otomatis saat dipilih (AJAX upsert ke `jawaban_kuis`).
- **Waktu habis → jawaban terkirim otomatis dan nilai dihitung.** Penegakan di **server**: setiap request setelah batas, atau saat siswa/guru membuka hasil, men-finalisasi kuis dengan jawaban yang sudah tersimpan. JS hanya bantuan UX.
- Nilai = `jumlah benar / jumlah soal × 100` (dibulatkan 2 desimal). Soal tak dijawab = salah.
- Setelah selesai siswa melihat nilainya. **[ASUMSI]** Kunci jawaban tidak ditampilkan.

### F12 Rekap nilai & ekspor (Guru, W)
- Per kelas+mapel: tabel siswa × (setiap tugas, setiap kuis) + rata-rata. Per siswa: rincian nilai.
- Ekspor **CSV** (UTF-8 dengan BOM agar terbuka benar di Excel). **[ASUMSI]** XLSX via PhpSpreadsheet hanya bila waktu cukup (nice-to-have).
- Tugas belum dikumpulkan / belum dinilai tampil kosong atau "-", tidak dihitung 0 kecuali diputuskan lain.

### F13 Pengumuman kelas (Guru buat, Siswa lihat, O)
- Guru membuat/ubah/hapus pengumuman untuk satu pengampu (kelas+mapel). Siswa melihat pengumuman kelasnya, terbaru di atas.

### F14 Dashboard per peran (Semua, O)
- Admin: jumlah pengguna / kelas / mapel, tahun ajaran aktif.
- Guru: tugas yang belum dinilai (jumlah pengumpulan nilai NULL), tugas/kuis mendatang, aktivitas terbaru.
- Siswa: tugas mendekati tenggat (belum dikumpulkan), kuis yang sedang dibuka, pengumuman terbaru.

## 5. Kebutuhan Non-Fungsional

- **Keamanan:** `password_hash()` (PASSWORD_DEFAULT); PDO prepared statement (`ATTR_EMULATE_PREPARES=false`); output di-escape `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`; token CSRF di semua form POST (dan AJAX); validasi upload ekstensi + MIME + ukuran; nama file disimpan acak (bukan nama asli).
- **Hak akses:** setiap rute dicek peran di router; kepemilikan data dicek di controller (guru→pengampu miliknya, siswa→kelasnya).
- **Responsif:** nyaman di layar HP (Bootstrap 5, tabel `table-responsive`).
- **Kemudahan:** fitur utama maksimal **3 klik dari dashboard**.
- **Kompatibilitas:** Chrome, Firefox, Edge versi terbaru.
- **Zona waktu:** **[ASUMSI]** `Asia/Jakarta`, set di PHP dan koneksi MySQL. Format tanggal tampil `d M Y H:i`.
- **Bahasa UI:** Bahasa Indonesia.

## 6. Arsitektur

Request → `public/index.php` (router: cek sesi + peran) → Controller → Model (PDO) → MySQL; Controller → View (PHP + Bootstrap).

```
lms-sma/
├── app/
│   ├── controllers/   # AuthController, MateriController, TugasController, ...
│   ├── models/        # User, Kelas, Materi, Tugas, Kuis, ...
│   ├── views/         # admin/, guru/, siswa/, layouts/
│   └── core/          # Router, Database (PDO), Auth, Csrf
├── public/
│   ├── index.php      # satu-satunya pintu masuk
│   └── assets/        # css, js, gambar
├── uploads/           # file materi & tugas (di luar document root / diblok .htaccess)
├── config/            # config.example.php (config.php di-gitignore)
├── database/lms.sql   # skema + seed
└── tests/             # skenario uji / skrip bantu
```

Ketentuan:
- Document root diarahkan ke `public/`. Jika di shared hosting tidak bisa, sediakan `.htaccess` yang memblok `app/`, `uploads/`, `config/`, `database/`.
- Autoloader sederhana (PSR-4 manual atau `spl_autoload_register`), boleh Composer hanya untuk PhpSpreadsheet.
- Tidak ada logika SQL di controller/view; semua di model.
- Base URL & kredensial DB dari `config/config.php` (tidak di-commit).

## 7. Skema Database

Engine InnoDB, charset `utf8mb4`. Kolom di bawah = minimum dari proposal; tambahan ditandai (+).

```sql
users(id PK, nama, username UNIQUE, password_hash, role ENUM('admin','guru','siswa'),
      kelas_id NULL FK→kelas, status ENUM('aktif','nonaktif') DEFAULT 'aktif', created_at (+))

tahun_ajaran(id PK, nama, semester ENUM('ganjil','genap'), aktif TINYINT(1))

kelas(id PK, nama, tingkat ENUM('X','XI','XII'), tahun_ajaran_id FK)

mapel(id PK, kode UNIQUE, nama)

pengampu(id PK, guru_id FK→users, kelas_id FK→kelas, mapel_id FK→mapel,
         UNIQUE(kelas_id, mapel_id))

materi(id PK, pengampu_id FK, judul, deskripsi, file, file_asli (+), created_at)

tugas(id PK, pengampu_id FK, judul, deskripsi, lampiran NULL, lampiran_asli (+), tenggat DATETIME, created_at (+))

pengumpulan(id PK, tugas_id FK, siswa_id FK→users, file, file_asli (+), dikumpulkan_at,
            status ENUM('tepat_waktu','terlambat') (+), nilai DECIMAL(5,2) NULL, komentar NULL,
            UNIQUE(tugas_id, siswa_id))

kuis(id PK, pengampu_id FK, judul, durasi_menit INT, mulai DATETIME, selesai DATETIME)

soal(id PK, kuis_id FK, pertanyaan, opsi_a, opsi_b, opsi_c NULL, opsi_d NULL, opsi_e NULL,
     kunci ENUM('a','b','c','d','e'))

hasil_kuis(id PK, kuis_id FK, siswa_id FK→users, nilai DECIMAL(5,2) NULL, mulai_at DATETIME,
           selesai_at DATETIME NULL, UNIQUE(kuis_id, siswa_id))

jawaban_kuis(id PK, hasil_kuis_id FK, soal_id FK, jawaban ENUM('a','b','c','d','e') NULL,
             UNIQUE(hasil_kuis_id, soal_id))

pengumuman(id PK, pengampu_id FK, judul, isi, created_at)
```

Seed minimum (`database/lms.sql`): 1 akun admin (password diganti saat pertama dipakai), 1 tahun ajaran aktif, beberapa kelas/mapel/guru/siswa demo untuk uji. Password demo jangan dipakai di produksi.

## 8. Halaman & Rute

| Peran | Halaman |
|---|---|
| Semua | Login, profil, ganti password |
| Admin | Dashboard, pengguna (+impor CSV), kelas & tahun ajaran, mapel, pengampu |
| Guru | Dashboard, kelas diampu, materi, tugas & penilaian, kuis & soal, rekap nilai, pengumuman |
| Siswa | Dashboard, daftar mapel, materi, tugas & pengumpulan, kuis, nilai saya |

Pola rute: `/{peran}/{modul}/{aksi}/{id?}` (contoh `/guru/tugas/nilai/12`). Semua perubahan data pakai POST + CSRF; GET tidak boleh mengubah data.

## 9. Aturan Bisnis Penting (ringkas)

1. Siswa hanya melihat data dengan `pengampu.kelas_id = users.kelas_id`.
2. Guru hanya mengakses data dengan `pengampu.guru_id = users.id`.
3. Status pengumpulan ditentukan server berdasarkan `NOW()` vs `tenggat`.
4. Kuis: satu percobaan, batas waktu ditegakkan server, nilai dihitung server.
5. Hanya satu tahun ajaran aktif; dashboard dan daftar kelas memprioritaskan tahun ajaran aktif.
6. Nonaktifkan, jangan hapus, pengguna yang sudah punya data.
7. File unduhan: cek hak akses → stream dengan header `Content-Disposition` memakai `file_asli`.

## 10. Rencana Pengujian

**Black box:** setiap F01–F14, skenario input benar dan salah. Skenario contoh dari proposal:

| Kode | Skenario | Hasil diharapkan |
|---|---|---|
| T01 | Login dengan password salah | Pesan gagal, tidak masuk |
| T02 | Kumpul tugas setelah tenggat | Tersimpan, status "terlambat" |
| T03 | Unggah file .exe | Ditolak, pesan format tidak diizinkan |
| T04 | Waktu kuis habis saat mengerjakan | Jawaban tersimpan otomatis, nilai terhitung |
| T05 | Siswa buka URL admin | Diarahkan ke "akses ditolak" |

**Tambahan [ASUMSI]:** T06 siswa kelas A akses materi kelas B via ID di URL → 403; T07 form POST tanpa token CSRF → ditolak; T08 file `.php` yang diganti nama jadi `.pdf` → ditolak oleh cek MIME; T09 input `<script>` pada judul tidak tereksekusi; T10 siswa membuka kuis kedua kali → diarahkan ke hasil; T11 kunjungan langsung ke `/uploads/xxx.pdf` → ditolak; T12 guru A mencoba menilai tugas guru B → 403.

**UAT:** guru & siswa, kuesioner Likert 1–5 (kemudahan, kegunaan). Target rata-rata ≥ 4.

## 11. Definition of Done

- [ ] 12 kebutuhan Wajib berjalan dan lolos black box testing
- [ ] Tidak ada halaman yang bisa diakses peran yang tidak berhak
- [ ] Checklist keamanan (bagian 5) terpenuhi, dicek manual per modul
- [ ] Semua halaman usable di lebar 360 px
- [ ] `database/lms.sql` bisa diimpor bersih di XAMPP baru dan README setup berjalan dari nol
- [ ] Sistem bisa diakses online dan didemokan (minggu 14)

## 12. Asumsi & Pertanyaan Terbuka

Asumsi yang dibuat di atas (ubah di sini kalau tidak cocok): format impor CSV; kebijakan kumpul ulang tugas; kunci kuis tidak ditampilkan; soal terkunci setelah ada hasil; tugas belum dikumpulkan tidak dihitung 0 di rekap; rate limit login; zona waktu Asia/Jakarta.

Catatan: proposal menyebut "server khusus milik sekolah" di fitur, tapi RAB memakai VPS sewa. Tidak memengaruhi kode; pastikan konsisten saat presentasi.
