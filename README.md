# LMS SMAN 10 Garut

Platform **Learning Management System (LMS)** modern, lengkap, dan ringan untuk sekolah menengah atas (SMA): materi belajar, tugas, kuis pilihan ganda terintegrasi, video pembelajaran, perpustakaan digital, jadwal pelajaran, presensi kehadiran, catatan siswa, pencapaian prestasi, kalender akademik, dan rekap nilai dalam satu portal terpadu.

Dibangun untuk **SMAN 10 Garut** oleh **PT. Vantage Sejahtera** *(Proposal Penawaran No. 001/VS-PNW/IX/2026)*.

- Spesifikasi Produk: [PRD.md](PRD.md)
- Dokumen Rencana Kerja: [PLAN.md](PLAN.md)
- Hasil Pengujian: [tests/black-box.md](tests/black-box.md)

---

## Teknologi

| Lapisan | Teknologi | Keterangan |
|---|---|---|
| **Backend** | PHP 8.2 Native | Pola arsitektur MVC murni, OOP, tanpa ketergantungan framework pihak ketiga |
| **Basis Data** | MySQL 8.0 / MariaDB | Akses via PDO prepared statement murni (aman dari SQL Injection) |
| **Frontend** | Bootstrap 5 + Vanilla JS | Desain modern berorientasi *Left Sidebar*, tema dark-mode elegan, responsif di HP/desktop |
| **Penyimpanan** | File System Lokal (`uploads/`) | Akses berkas terlindungi otentikasi controller dan `.htaccess` |

Ekstensi PHP wajib: `pdo_mysql`, `fileinfo`, `mbstring` (aktif secara bawaan pada Laragon dan XAMPP).

---

## Fitur Utama Sistem

### 1. Manajemen Akademik & Pengguna (Admin)
- **Tahun Ajaran & Semester:** Pengaturan tahun ajaran aktif dan riwayat semester.
- **Tingkat & Kelas:** Pengelolaan 6 kelas aktif (X-1, X-2, XI-IPA, XI-IPS, XII-IPA, XII-IPS).
- **Mata Pelajaran:** Katalog mata pelajaran lengkap beserta kode uniknya.
- **Manajemen Guru & Siswa:** Penambahan, edit, pencarian, filter status aktif/nonaktif, dan fitur **Impor CSV Siswa massal**.
- **Penugasan Pengampu:** Pemetaan unik guru mengajar mata pelajaran tertentu di tiap kelas.

### 2. Kegiatan Belajar Mengajar (Guru)
- **Modul & Materi Pembelajaran:** Unggah bahan ajar PDF/dokumen dengan proteksi hak akses unduhan.
- **Tugas Siswa:** Pembuatan tugas belajar berbatas waktu (tenggat), penilaian tugas (0–100), dan umpan balik (*feedback*) catatan guru.
- **Kuis Pilihan Ganda & Bank Soal:** Pembuatan kuis dengan durasi menit, batas jendela waktu pengerjaan, kunci jawaban acuan, dan pemantauan hasil skor siswa secara langsung.
- **Video Pembelajaran Interaktif:** Integrasi materi visual berbasis YouTube embed atau pemutar video web.
- **Presensi & Kehadiran Kelas:** Pencatatan kehadiran siswa per tanggal pertemuan (Hadir, Sakit, Izin, Alpha) yang divalidasi langsung oleh guru pengampu.
- **Rekap Nilai & Ekspor CSV:** Rangkuman akumulasi nilai tugas dan kuis per kelas dengan ekspor format CSV Excel.
- **Pengumuman Kelas:** Papan pengumuman terarah untuk siswa di kelas yang diampu.

### 3. Portal Belajar Siswa
- **Dashboard Ringkasan:** Menampilkan materi baru, tugas mendekati tenggat, kuis aktif, dan jadwal pelajaran hari ini.
- **Pengumpulan Tugas:** Unggah file jawaban tugas dengan deteksi otomatis status *tepat waktu* atau *terlambat*.
- **Pengerjaan Kuis Interaktif:** Timer countdown server-side, penyimpanan jawaban instan, dan kalkulasi nilai otomatis.
- **Rekap Presensi Pribadi:** Pemantauan statistik kehadiran per mata pelajaran dan persentase kehadiran belajar.
- **Perpustakaan Digital:** Akses buku teks utama dan referensi pendukung secara online.
- **Catatan Belajar Siswa:** Buku saku digital untuk merangkum rumus, materi, dan catatan belajar mandiri.
- **Pencapaian & Badge:** Apresiasi penghargaan prestasi (Nilai Sempurna, Pengumpul Tepat Waktu, Rajin Latihan).
- **Kalender Akademik:** Agenda penting sekolah seperti UTS, UAS, MPLS, dan hari libur nasional.

---

## Petunjuk Instalasi & Menjalankan (Laragon / XAMPP)

### Langkah 1: Siapkan Folder Proyek
Pastikan proyek berada di folder web server Anda, misalnya:
- Laragon: `C:\laragon\www\ptvantagesejahtera-main`
- XAMPP: `C:\xampp\htdocs\ptvantagesejahtera`

### Langkah 2: Nyalakan Web Server & MySQL
Nyalakan Apache dan MySQL dari control panel Laragon atau XAMPP.

### Langkah 3: Impor Basis Data
Buka **Terminal Laragon** (atau Command Prompt) lalu jalankan:

```bash
mysql -u root < "C:\laragon\www\ptvantagesejahtera-main\database\lms_lengkap.sql"
```

*Atau melalui phpMyAdmin:*
1. Buka `http://localhost/phpmyadmin` di browser.
2. Klik tab **Import**.
3. Pilih file `database/lms_lengkap.sql` lalu klik **Import / Kirim**.

> File `database/lms_lengkap.sql` akan otomatis membuat database `lms_sma`, seluruh tabel, 14 akun guru, 60 akun siswa lengkap, presensi riil, materi, tugas, dan kuis.

### Langkah 4: Konfigurasi Aplikasi
Bila file `config/config.php` belum ada, salin dari contoh:
```bash
cp config/config.example.php config/config.php
```
Sesuaikan parameter database di `config/config.php` jika MySQL Anda menggunakan password.

### Langkah 5: Buka Aplikasi di Browser
Buka browser dan akses URL:
* `http://localhost/ptvantagesejahtera-main/public` (atau via virtual host sesuai konfigurasi Laragon Anda).

---

## Akun Demo untuk Pengujian

Semua akun sudah siap pakai dan terisi data demo riil:

### 1. Akun Administrator
| Role | Username | Password | Keterangan |
|---|---|---|---|
| Admin | `admin` | `admin12345` | Pengelolaan data master sekolah |

### 2. Akun Guru Mata Pelajaran
*Password semua guru:* `guru12345`

| Nama Guru | Username | Mata Pelajaran Diampu |
|---|---|---|
| Siti Rahmawati, S.Pd. | `guru.siti` | Matematika (X-1, X-2, XII-IPA) |
| Budi Hartono, S.Si., M.Pd. | `guru.budi` | Fisika (X-1, X-2, XI-IPA, XII-IPA) |
| Dewi Lestari, M.Pd. | `guru.dewi` | Bahasa Indonesia (X-1, XI-IPA, XII-IPA) |
| Agus Setiawan, S.Kom., M.T. | `guru.agus` | Informatika (X-1, X-2, XII-IPA) |
| Rina Fitriani, S.Pd., M.Hum. | `guru.rina` | Bahasa Inggris (X-1, XI-IPA, XII-IPA) |
| Hendra Kusuma, S.Pd. | `guru.hendra` | PJOK (Seluruh Kelas) |
| Dra. Hj. Maya Anggraeni, M.Si. | `guru.maya` | Kimia (X-1, X-2, XI-IPA, XII-IPA) |
| Asep Saepudin, S.Pd., M.Pkim. | `guru.asep` | Biologi (X-1, X-2, XI-IPA, XII-IPA) |
| Drs. Bambang Sutrisno, M.Pd. | `guru.bambang` | Sejarah Indonesia |
| Dra. Nurul Hidayati, M.Pd. | `guru.nurul` | PPKN |
| Fajar Ramadhan, S.Kom., M.T. | `guru.fajar` | Informatika Lanjut (XI & XII) |
| Ratna Sari Dewi, S.Pd. | `guru.ratna` | Bahasa Inggris (X-2, XI-IPS, XII-IPS) |
| Drs. Slamet Wahyudi, M.Pd. | `guru.slamet` | Matematika Lanjut (XI-IPA, XI-IPS, XII-IPS) |
| Dian Permatasari, S.Pd. | `guru.dian` | Bahasa Indonesia (X-2, XI-IPS, XII-IPS) |

### 3. Akun Siswa (Tersedia 60 Siswa di 6 Kelas)
*Password semua siswa:* `siswa12345`

| Kelas | Contoh Username | Nama Siswa | Kelengkapan Data Demo |
|---|---|---|---|
| **X-1** | `siswa.ahmad` | Ahmad Fauzi | Memiliki rekap presensi hadir/izin, nilai kuis 100, tugas selesai |
| **X-1** | `siswa.bella` | Bella Puspita | Presensi terdata, kuis selesai nilai 80, badge prestasi |
| **X-2** | `siswa.irfan` | Irfan Maulana | Kuis logika informatika nilai 100, presensi lengkap |
| **XI-IPA**| `siswa.putri` | Putri Wulandari | Kuis trigonometri nilai 100, catatan rumus belajar |
| **XI-IPS**| `siswa.aditya`| Aditya Nugroho | Presensi mapel IPS terdata, jadwal kelas XI-IPS |
| **XII-IPA**| `siswa.xena`  | Xena Olivia | Kuis fisika modern nilai 100, pengumpulan tugas praktikum |
| **XII-IPS**| `siswa.hendri`| Hendri Gunawan | Presensi mapel XII-IPS terdata, kalender kelulusan |

---

## Standar Keamanan Sistem
- **Anti SQL Injection:** Menggunakan PDO prepared statement pada seluruh query basis data model.
- **Anti Cross-Site Scripting (XSS):** Semua output HTML disanitasi menggunakan helper `e()` (`htmlspecialchars`).
- **Anti Cross-Site Request Forgery (CSRF):** Validasi token CSRF pada seluruh request form POST dan AJAX.
- **Proteksi Berkas:** Validasi ekstensi, MIME type (via `finfo`), dan batas ukuran 10 MB. Seluruh berkas disimpan dengan nama acak terenkripsi di folder `uploads/` yang tidak dapat diakses langsung tanpa otentikasi login.
