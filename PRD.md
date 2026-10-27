# PRD: LMS Berbasis Web untuk SMA (PHP Native + MySQL)

> **Proyek:** Learning Management System SMAN 10 Garut  
> **Pengembang:** PT. Vantage Sejahtera (Proposal Penawaran No. 001/VS-PNW/IX/2026)  
> **Status:** Lengkap & Siap Produksi

---

## 1. Ringkasan Eksekutif

LMS SMA adalah portal pembelajaran daring terpadu berbasis web yang dirancang khusus untuk memenuhi kebutuhan proses belajar mengajar tingkat sekolah menengah atas. Sistem ini menyatukan materi ajar, penugasan, kuis interaktif, video pembelajaran, perpustakaan digital, pemantauan kehadiran (presensi), jadwal pelajaran, catatan siswa, dan kalender akademik ke dalam satu platform yang aman, responsif, dan mudah digunakan.

**Teknologi Utama:**
* **Backend:** PHP 8.2 Native (OOP, Arsitektur MVC murni tanpa framework)
* **Database:** MySQL 8.0 / MariaDB (Akses menggunakan PDO Prepared Statements)
* **Frontend:** Bootstrap 5, Vanilla JavaScript, CSS Desain Modern dengan *Left Sidebar*
* **Infrastruktur:** Kompatibel dengan Laragon, XAMPP, dan lingkungan VPS/Cloud Hosting

---

## 2. Peran Pengguna & Hak Akses

| Peran | Deskripsi | Hak Akses Utama |
|---|---|---|
| **Admin** | Staf Tata Usaha / Admin Sekolah | Mengelola data master (tahun ajaran, kelas, mata pelajaran, penugasan guru pengampu), mengelola akun guru dan siswa, serta impor massal siswa via CSV. |
| **Guru** | Tenaga Pendidik / Guru Mata Pelajaran | Mengelola modul materi, tugas siswa, bank soal kuis, video pembelajaran, presensi kehadiran kelas, rekap nilai, dan pengumuman kelas pada rombel yang diampu. |
| **Siswa** | Peserta Didik Kelas X, XI, XII | Mengakses materi, mengumpulkan tugas tepat waktu/terlambat, mengerjakan kuis dengan penghitung waktu, membaca e-book perpustakaan, membuat catatan mandiri, melihat jadwal, dan memantau rekap absensi. |

*Penegakan Keamanan:* Hak akses diuji secara ketat pada sisi server (*Server-side Authorization*) melalui router dan controller. Percobaan akses lintas peran atau lintas kelas otomatis menghasilkan respon **HTTP 403 Forbidden**.

---

## 3. Spesifikasi Kebutuhan Fungsional

### Modul Inti & Administrasi (F01 – F04)
* **F01 Autentikasi & Akun:** Login berbasis `username` dan verifikasi hash password (`password_verify()`), regenerasi sesi `session_regenerate_id()`, proteksi brute force (rate limit 5 kali percobaan gagal), serta ganti password mandiri.
* **F02 Kelola Pengguna & Impor CSV:** CRUD akun admin, guru, dan siswa. Dilengkapi fitur unggah berkas CSV untuk mendaftarkan puluhan siswa secara massal langsung ke kelas tujuan.
* **F03 Kelola Master Akademik:** CRUD Tahun Ajaran dengan penegakan hanya 1 tahun ajaran aktif, CRUD Kelas per tingkat (X, XI, XII), dan katalog Mata Pelajaran unik.
* **F04 Guru Pengampu:** Penugasan guru mengajar kombinasi `(kelas_id, mapel_id)`. Pasangan ini menjadi konteks kepemilikan seluruh aktivitas akademik.

### Modul Pembelajaran & Penugasan (F05 – F09)
* **F05 & F06 Materi Pembelajaran:** Guru mengunggah berkas materi (PDF, PPT, DOCX) maksimal 10 MB. Siswa mengunduh berkas materi melalui controller terproteksi (mencegah direct URL bypass ke direktori berkas).
* **F07 & F08 Tugas & Pengumpulan:** Guru membuat tugas dengan batas tenggat waktu. Siswa mengumpulkan berkas tugas dengan verifikasi waktu otomatis (*tepat waktu* atau *terlambat*).
* **F09 Penilaian & Umpan Balik:** Guru memberikan nilai numerik (0–100) dan catatan ulasan (*feedback*) konstruktif terhadap berkas pengumpulan siswa.

### Modul Evaluasi & Kuis (F10 – F12)
* **F10 Manajemen Kuis & Bank Soal:** Pembuatan kuis dengan durasi pengerjaan menit dan rentang tanggal mulai–selesai. Bank soal pilihan ganda 5 opsi (A–E) dan kunci jawaban.
* **F11 Pengerjaan Kuis Interaktif Siswa:** Pengerjaan kuis satu kali kesempatan per siswa. Waktu dihitung dari server (MySQL `NOW()`), auto-save pilihan jawaban, dan auto-grading instan saat waktu habis atau submit.
* **F12 Rekap Nilai & Ekspor CSV:** Tabel agregasi nilai tugas, nilai kuis, dan rata-rata per mata pelajaran dengan fitur ekspor ke file CSV siap olah di Excel.

### Modul Pengayaan & Media (F13 – F17)
* **F13 Papan Pengumuman Kelas:** Media komunikasi resmi guru pengampu untuk mempublikasikan arahan atau agenda penting ke siswa di kelas bersangkutan.
* **F14 Dashboard Terintegrasi:** Dasbor personal per peran yang menyajikan ringkasan statistik, agenda mendekati deadline, dan aktivitas terkini.
* **F15 Video Pembelajaran:** Integrasi video pembelajaran daring (YouTube Embed & video player) yang terhubung per mata pelajaran.
* **F16 Perpustakaan Digital (E-Book):** Repositori buku teks sekolah dan buku referensi pendukung kurikulum yang dapat dibaca atau diunduh siswa.
* **F17 Jadwal Pelajaran Mingguan:** Informasi jadwal kegiatan belajar mengajar per hari (Senin–Sabtu), jam mulai-selesai, dan alokasi ruangan kelas/lab.

### Modul Presensi & Kesiswaan (F18 – F22)
* **F18 Presensi & Rekap Kehadiran Siswa:** Pencatatan kehadiran siswa per pertemuan kelas oleh guru (Hadir, Sakit, Izin, Alpha) dengan catatan validasi. Siswa dapat melihat persentase kehadiran belajar secara transparan.
* **F19 Catatan Belajar Siswa:** Fitur *personal notebook* bagi siswa untuk mencatat rumus, poin penting materi, dan rangkuman belajar mandiri.
* **F20 Pencapaian & Badge Prestasi:** Sistem *gamification* penghargaan prestasi siswa (contoh: Nilai Sempurna 100, Pengumpul Tugas Tepat Waktu, Siswa Rajin Kuis).
* **F21 Kalender Akademik:** Agenda kegiatan tahunan sekolah (PTS, PAS, MPLS, Class Meeting, Hari Libur Nasional).
* **F22 Antarmuka Pengguna Modern:** Tata letak *Left Sidebar Navigation* responsif dengan ikon grafis rapi dan bilah status ringkas.

---

## 4. Kebutuhan Non-Fungsional & Keamanan

1. **Keamanan Data:**
   * Password di-hash menggunakan algoritma `bcrypt` (`password_hash`).
   * Seluruh query basis data menggunakan PDO prepared statements dengan parameter binding (`ATTR_EMULATE_PREPARES => false`).
   * Semua tampilan output variabel HTML di-escape menggunakan fungsi `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
   * Seluruh formulir perubahan data mewajibkan token proteksi CSRF.
   * Berkas unggahan diverifikasi ekstensi dan MIME type melalui PHP `finfo`, disimpan dengan nama acak MD5/SHA.
2. **Kinerja & Kompatibilitas:**
   * Beban muat halaman cepat tanpa dependensi *heavy build tool* (npm/webpack).
   * Responsif di berbagai perangkat mulai dari layar smartphone (360px) hingga monitor desktop.
   * Format zona waktu acuan: `Asia/Jakarta` (WIB).

---

## 5. Skema Basis Data (Tabel Lengkap)

Skema basis data berada pada file `database/lms_lengkap.sql` yang mencakup 19 tabel relasional:

1. `tahun_ajaran` (id, nama, semester, aktif)
2. `kelas` (id, nama, tingkat, tahun_ajaran_id)
3. `users` (id, nama, username, password_hash, role, kelas_id, status, nomor_induk, tempat_lahir, tanggal_lahir, alamat, no_hp, no_hp_ortu)
4. `mapel` (id, kode, nama)
5. `pengampu` (id, guru_id, kelas_id, mapel_id)
6. `materi` (id, pengampu_id, judul, deskripsi, file, file_asli, created_at)
7. `tugas` (id, pengampu_id, judul, deskripsi, lampiran, lampiran_asli, tenggat, created_at)
8. `pengumpulan` (id, tugas_id, siswa_id, file, file_asli, dikumpulkan_at, status, nilai, komentar, dinilai_at)
9. `kuis` (id, pengampu_id, judul, durasi_menit, mulai, selesai, created_at)
10. `soal` (id, kuis_id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, kunci)
11. `hasil_kuis` (id, kuis_id, siswa_id, nilai, mulai_at, selesai_at)
12. `jawaban_kuis` (id, hasil_kuis_id, soal_id, jawaban)
13. `pengumuman` (id, pengampu_id, judul, isi, created_at)
14. `video_pembelajaran` (id, pengampu_id, judul, deskripsi, tipe, file, file_asli, url_embed, durasi, created_at)
15. `perpustakaan` (id, judul, penulis, deskripsi, kategori, mapel_id, tingkat, file, file_asli, cover, uploader_id, created_at)
16. `jadwal` (id, pengampu_id, hari, jam_mulai, jam_selesai, ruangan)
17. `absensi` (id, pengampu_id, siswa_id, tanggal, status, keterangan, created_at)
18. `catatan_siswa` (id, siswa_id, mapel_id, judul, isi, created_at, updated_at)
19. `pencapaian` (id, siswa_id, tipe, label, deskripsi, ikon, diperoleh_at)
20. `kalender_akademik` (id, judul, deskripsi, tipe, tanggal_mulai, tanggal_selesai, tahun_ajaran_id, created_at)
