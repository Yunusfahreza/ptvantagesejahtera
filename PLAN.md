# PLAN: Pelaksanaan & Tahapan Pengembangan LMS SMA

Acuan: [PRD.md](PRD.md). Dokumen ini mencatat tahapan kerja implementasi proyek LMS SMAN 10 Garut dari awal perancangan hingga penyelesaian seluruh modul dan data demo.

---

## Ringkasan Progres Proyek

| Fase | Fokus Pengembangan | Status |
|---|---|---|
| **Fase 0** | Scaffold Arsitektur MVC, Database & Layout Dasar | Selesai (100%) |
| **Fase 1** | Autentikasi Pengguna & Penegakan Sesi / CSRF (F01) | Selesai (100%) |
| **Fase 2** | Manajemen Master Akademik: Tahun Ajaran, Kelas, Mapel (F03) | Selesai (100%) |
| **Fase 3** | Manajemen Pengguna & Penugasan Guru Pengampu (F02, F04) | Selesai (100%) |
| **Fase 4** | Modul Materi Pembelajaran & Berkas Terproteksi (F05, F06) | Selesai (100%) |
| **Fase 5** | Modul Penugasan Siswa, Unggah Berkas & Penilaian Guru (F07–F09) | Selesai (100%) |
| **Fase 6** | Kuis Interaktif, Bank Soal & Auto-Grading (F10–F12) | Selesai (100%) |
| **Fase 7** | Pengumuman Kelas & Dashboard Peran (F13, F14) | Selesai (100%) |
| **Fase 8** | Fitur Modern: Video, Perpustakaan, Jadwal, Absensi, Kalender, UI | Selesai (100%) |
| **Fase 9** | Dummy Data Lengkap 14 Guru, 60 Siswa, Presensi & Kuis Valid | Selesai (100%) |

---

## Rincian Checklist Implementasi

### Fase 0: Scaffold & Fondasi Arsitektur
- [x] Struktur folder MVC native (`app/controllers/`, `app/models/`, `app/core/`, `app/views/`, `public/`).
- [x] Pengaturan `.htaccess` dan pengamanan direktori sensitif.
- [x] Konfigurasi database PDO Singleton (`app/core/Database.php`) dengan exception handling.
- [x] Helper esensial: `e()`, `url()`, `redirect()`, `flash()`, `csrf_field()`, `tanggal_id()`.
- [x] Layout dasar Bootstrap 5 dan penanganan error HTTP 403 / 404 / 500.

### Fase 1: Autentikasi & Keamanan (F01)
- [x] Halaman login terintegrasi verifikasi hash `password_verify()`.
- [x] Regenerasi sesi aman `session_regenerate_id()` saat login berhasil.
- [x] Proteksi brute-force login (rate limit 5 kali percobaan gagal).
- [x] Fitur ubah password mandiri dengan validasi kecocokan password lama.
- [x] Logout bersih yang menghancurkan sesi dan cookie terkait.

### Fase 2: Master Akademik (F03)
- [x] CRUD Tahun Ajaran dengan penegakan hanya satu tahun ajaran aktif.
- [x] CRUD Kelas terhubung ke tahun ajaran aktif dan pembagian tingkat (X, XI, XII).
- [x] CRUD Mata Pelajaran dengan kode mapel unik.

### Fase 3: Pengguna & Penugasan Pengampu (F02, F04)
- [x] CRUD Akun pengguna dengan peran Admin, Guru, dan Siswa.
- [x] Fitur Impor massal siswa dari berkas format CSV.
- [x] Manajemen Guru Pengampu pasangan unik `(kelas_id, mapel_id)`.

### Fase 4: Modul Materi Pembelajaran (F05, F06)
- [x] Formulir unggah materi ajar berkas PDF, DOCX, PPTX dengan validasi MIME `finfo`.
- [x] Pembatasan ukuran unggah maksimal 10 MB dan penamaan acak aman di folder `uploads/`.
- [x] Controller unduhan terproteksi hak akses siswa sesuai rombel kelasnya.

### Fase 5: Tugas & Penilaian (F07, F08, F09)
- [x] Pembuatan tugas oleh guru dengan batas tenggat waktu (*deadline*).
- [x] Pengumpulan tugas oleh siswa dengan penentuan otomatis status *tepat waktu* / *terlambat*.
- [x] Antarmuka penilaian guru: input skor (0–100) dan catatan ulasan *feedback*.

### Fase 6: Kuis Interaktif & Rekap Nilai (F10, F11, F12)
- [x] Pembuatan kuis dengan durasi menit dan batas rentang tanggal aktif.
- [x] Bank soal pilihan ganda 5 opsi jawaban (A–E) beserta kunci jawaban.
- [x] Halaman pengerjaan kuis siswa dengan timer server-side dan auto-save jawaban.
- [x] Penilaian instan otomatis (*auto-grading*) saat waktu habis atau submit.
- [x] Rekap akumulasi nilai kelas dan fitur ekspor ke file CSV siap pakai.

### Fase 7: Pengumuman & Dashboard Terintegrasi (F13, F14)
- [x] Papan pengumuman guru pengampu untuk siswa di kelas binaannya.
- [x] Dashboard dinamis Administrator (statistik pengguna, kelas, mapel).
- [x] Dashboard dinamis Guru (tugas perlu dinilai, jadwal mengajar).
- [x] Dashboard dinamis Siswa (materi baru, tugas deadline, kuis aktif).

### Fase 8: Pengayaan Fitur Modern & Redesain UI
- [x] **Video Pembelajaran Interaktif:** Integrasi pemutar video dan embed YouTube.
- [x] **Perpustakaan Digital:** Katalog e-book teks utama dan modul referensi kurikulum.
- [x] **Jadwal Pelajaran Mingguan:** Pemetaan waktu belajar Senin–Sabtu per kelas.
- [x] **Presensi & Kehadiran:** Sistem absensi kehadiran siswa divalidasi guru per pertemuan.
- [x] **Catatan Belajar Siswa:** Personal notebook untuk mencatat materi mandiri.
- [x] **Pencapaian & Badge:** Sistem gamification apresiasi penghargaan prestasi siswa.
- [x] **Kalender Akademik:** Agenda penting sekolah dan hari libur nasional.
- [x] **Modern Layout Redesign:** Navigasi Left Sidebar elegan dengan header status ringkas.

### Fase 9: Data Demo Komprehensif & Pengujian
- [x] Database lengkap siap pakai di `database/lms_lengkap.sql`.
- [x] 14 akun Guru mata pelajaran aktif lengkap dengan NIP dan penugasan kelas.
- [x] 60 akun Siswa aktif lengkap di 6 kelas (X-1, X-2, XI-IPA, XI-IPS, XII-IPA, XII-IPS).
- [x] Rekap presensi multi-pertemuan tervalidasi riil untuk seluruh siswa.
- [x] 8 kuis interaktif lengkap dengan 40 butir soal, hasil nilai, dan rekap jawaban kuis.
- [x] Pembersihan berkas usang dan verifikasi seluruh alur aplikasi berjalan lancar.
