# PLAN: Implementasi LMS SMA dengan Claude Code

Acuan: `PRD.md`. Satu fase = satu sesi Claude Code = satu branch = satu PR/merge. Fase disusun sesuai jadwal proposal (minggu 5–14).

## 0. Cara kerja

1. Taruh `PRD.md` dan `PLAN.md` di root repo.
2. Buat `CLAUDE.md` (isi di bagian 1) supaya aturan terbawa di setiap sesi.
3. Tiap fase: mulai dengan **Plan Mode**, minta rencana file yang akan dibuat/diubah, review, baru eksekusi.
4. `/clear` antar fase. Jangan campur dua fase dalam satu sesi.
5. Akhir fase: jalankan checklist "Selesai kalau" fase itu, uji manual di browser, commit, merge.
6. Kalau Claude Code mulai menambah hal di luar PRD (framework, fitur non-tujuan), tolak dan arahkan balik ke PRD.

## 1. Isi `CLAUDE.md`

```markdown
# LMS SMA

Baca PRD.md (spesifikasi) dan PLAN.md (urutan kerja) sebelum mengubah kode.

## Stack & batas
- PHP 8.2 native, OOP, MVC. DILARANG pakai framework (Laravel, CodeIgniter, dll).
- MySQL via PDO prepared statement saja. Tidak ada SQL di controller/view.
- Bootstrap 5 + JS vanilla. Tanpa npm build step. Bootstrap boleh via CDN atau file lokal.
- Composer hanya untuk PhpSpreadsheet (opsional).

## Aturan wajib
- Semua output di-escape: htmlspecialchars(..., ENT_QUOTES, 'UTF-8') lewat helper e().
- Semua form POST/AJAX pakai token CSRF. GET tidak boleh mengubah data.
- Password: password_hash/password_verify.
- Upload: validasi ekstensi + MIME (finfo) + ukuran 10 MB, simpan dengan nama acak di uploads/,
  unduh hanya lewat controller yang cek hak akses.
- Cek peran di router DAN cek kepemilikan data di controller.
- Waktu/status/nilai dihitung di server, jangan percaya input klien.
- Zona waktu Asia/Jakarta. UI Bahasa Indonesia.
- Jangan commit config/config.php, uploads/*, vendor/.

## Konvensi
- Nama kelas PascalCase (TugasController), file = nama kelas, tabel & kolom snake_case Indonesia sesuai PRD.
- Satu fitur = satu controller + satu model + folder views.
- Commit kecil, pesan format: `feat(tugas): kumpul tugas dengan status terlambat`.
- Setelah selesai fase, update checklist di PLAN.md.

## Menjalankan
- XAMPP, document root ke public/. Import database/lms.sql. Salin config/config.example.php ke config.php.
```

## 2. Fase

### Fase 0: Scaffold & setup (minggu 5, awal)
**Branch:** `feat/setup`

Tugas:
- [ ] Struktur folder sesuai PRD bagian 6, autoloader, `.gitignore`, `.htaccess` (blok folder sensitif, front controller)
- [ ] `config/config.example.php`, koneksi PDO (`Database` singleton, exception mode, emulate prepares off)
- [ ] `database/lms.sql`: seluruh skema PRD bagian 7 + seed (admin, 1 tahun ajaran aktif, data demo)
- [ ] Layout dasar Bootstrap 5 (navbar per peran, flash message, halaman 403/404)
- [ ] Helper: `e()`, `redirect()`, `flash()`, `csrf_field()`

Selesai kalau: `lms.sql` bisa diimpor bersih, halaman `/` menampilkan layout, tidak ada error PHP.

Prompt awal:
> Baca PRD.md dan PLAN.md. Kerjakan Fase 0. Masuk Plan Mode dulu: tunjukkan daftar file yang akan dibuat dan isi skema SQL sebelum menulis kode.

### Fase 1: Core & autentikasi (minggu 5) — F01
**Branch:** `feat/auth`

Tugas:
- [ ] `Router` (map URL → controller@aksi, middleware cek sesi + peran, 403 jika peran salah)
- [ ] `Auth` (login, logout, `session_regenerate_id`, cek `status`), `Csrf` (generate + verify)
- [ ] `AuthController`: login, logout, profil, ganti password
- [ ] Dashboard placeholder per peran
- [ ] Rate limit login sederhana

Selesai kalau: T01 dan T05 lolos; akun nonaktif ditolak; POST tanpa CSRF ditolak; tiga peran masuk ke dashboard masing-masing.

### Fase 2: Modul admin (minggu 6–7) — F02, F03, F04
**Branch:** `feat/admin`

Tugas:
- [ ] CRUD tahun ajaran (satu aktif) → CRUD kelas → CRUD mapel
- [ ] CRUD pengguna + nonaktifkan + reset password + cari/filter/paginasi
- [ ] Impor CSV siswa dengan laporan per baris
- [ ] Pengampu: form pilih guru/kelas/mapel, validasi unik (kelas, mapel)
- [ ] Hapus ditolak jika masih dipakai relasi

Selesai kalau: admin bisa menyiapkan satu sekolah lengkap dari nol (tahun ajaran → kelas → mapel → guru/siswa → pengampu) tanpa menyentuh DB. Impor CSV 30 siswa dengan 2 baris sengaja salah menghasilkan laporan benar.

### Fase 3: Materi & layanan upload (minggu 8) — F05, F06
**Branch:** `feat/materi`

Tugas:
- [ ] `FileUploader` di `core/` (ekstensi, MIME, ukuran, nama acak, simpan ke `uploads/`) — **dipakai ulang oleh tugas**
- [ ] `FileDownloader`/aksi unduh dengan cek hak akses + `Content-Disposition`
- [ ] Guru: daftar kelas diampu, CRUD materi
- [ ] Siswa: daftar mapel kelasnya → daftar materi → unduh

Selesai kalau: T03, T06, T08, T11 lolos; hapus materi ikut menghapus file fisik.

### Fase 4: Tugas & pengumpulan (minggu 9) — F07, F08, F09
**Branch:** `feat/tugas`

Tugas:
- [ ] Guru: CRUD tugas dengan lampiran dan tenggat
- [ ] Siswa: daftar tugas dengan status, form kumpul/kumpul ulang (selama belum dinilai), status tepat waktu/terlambat dari server
- [ ] Guru: daftar siswa per tugas (termasuk belum kumpul), unduh file, input nilai 0–100 + komentar
- [ ] Siswa melihat nilai dan komentar

Selesai kalau: T02 dan T12 lolos; kumpul ulang setelah dinilai ditolak; nilai di luar 0–100 ditolak.

### Fase 5: Kuis (minggu 10) — F10, F11
**Branch:** `feat/kuis`

Tugas:
- [ ] Guru: CRUD kuis dan soal (validasi kunci harus opsi terisi)
- [ ] Siswa: mulai kuis (buat `hasil_kuis`, satu percobaan), halaman soal, simpan jawaban AJAX, countdown dari waktu server
- [ ] Finalisasi di server: submit manual **atau** request setelah batas waktu → hitung nilai dari jawaban tersimpan
- [ ] Siswa melihat nilai; guru melihat hasil per kuis

Selesai kalau: T04 dan T10 lolos. Uji khusus: tutup tab di tengah kuis, buka lagi setelah waktu habis → hasil sudah final. Ubah jam laptop → tidak berpengaruh.

### Fase 6: Rekap nilai & ekspor (minggu 11) — F12
**Branch:** `feat/rekap`

Tugas:
- [ ] Rekap per kelas+mapel: siswa × (tugas, kuis) + rata-rata
- [ ] Rekap per siswa
- [ ] Ekspor CSV (UTF-8 BOM, `;` atau `,` mengikuti default Excel Indonesia — uji buka di Excel). XLSX hanya jika sisa waktu.

Selesai kalau: angka rekap cocok dengan hitungan manual dari data demo; CSV terbuka rapi di Excel.

### Fase 7: Pengumuman & dashboard (minggu 12) — F13, F14 (opsional)
**Branch:** `feat/pengumuman-dashboard`

Tugas:
- [ ] CRUD pengumuman guru, tampilan siswa
- [ ] Dashboard final per peran (tugas mendekati tenggat, belum dinilai, kuis terbuka, pengumuman terbaru)
- [ ] Rapikan UI: konsistensi, responsif 360 px, maksimal 3 klik ke fitur utama

Selesai kalau: dashboard tampil benar di tiap peran dan tidak lambat (cek query, pakai JOIN/indeks, hindari N+1).

Jika waktu mepet: lepas F13–F14, fokus pada rapi UI + Fase 8.

### Fase 8: Hardening & pengujian (minggu 13)
**Branch:** `chore/hardening`

Tugas:
- [ ] Audit keamanan: grep semua query non-prepared, output tanpa `e()`, form tanpa CSRF, rute tanpa cek peran
- [ ] Tulis `tests/black-box.md`: tabel skenario T01–T12 + F01–F14 (input, hasil diharapkan, hasil aktual, status)
- [ ] Jalankan semua skenario, perbaiki bug
- [ ] Siapkan kuesioner UAT Likert 1–5

Prompt:
> Lakukan audit keamanan terhadap seluruh kode berdasarkan checklist PRD bagian 5 dan CLAUDE.md. Laporkan temuan per file dan nomor baris dulu, jangan langsung mengubah. Setelah saya setujui baru perbaiki.

Selesai kalau: semua skenario lolos, tidak ada temuan keamanan terbuka.

### Fase 9: Deploy & dokumentasi (minggu 14)
**Branch:** `chore/deploy`

Tugas:
- [ ] `README.md` (setup XAMPP dari nol, setup hosting/VPS, akun demo)
- [ ] Config produksi: `display_errors` off, error ke log, cookie sesi `HttpOnly` + `Secure` + `SameSite`
- [ ] Deploy ke hosting/VPS, uji ulang T01–T05 di server asli
- [ ] Ganti semua password seed

Selesai kalau: URL online bisa dipakai tiga peran, README bisa diikuti orang lain dari nol.

## 3. Urutan dependensi (jangan dibalik)

`Fase 0 → 1 → 2 → 3 → 4 → 5 → 6 → 7 → 8 → 9`

- Fase 3 membuat `FileUploader`; Fase 4 memakainya.
- Fase 6 butuh data dari Fase 4 dan 5.
- Fase 2 adalah prasyarat semua konten (pengampu = konteks segalanya).

## 4. Tips supaya hasil Claude Code bagus

- **Spesifik dengan PRD:** tulis "kerjakan F08 sesuai PRD, termasuk asumsi kumpul ulang" daripada "buat fitur tugas".
- **Minta bukti, bukan klaim:** "jalankan skenario T02 dan tunjukkan hasil aktual".
- **Satu hal per prompt di dalam fase:** model → controller → view → uji.
- **Review diff** setiap commit; fokus ke cek hak akses dan query. Itu bagian yang paling sering bolong.
- **Jangan biarkan** Claude menambah dependensi baru tanpa alasan. Tanya kenapa dulu.
- Kalau sesi panjang mulai ngawur, `/clear` dan mulai lagi dengan "baca PRD.md, lanjut Fase X bagian Y".
- Untuk proyek tim: tiap anggota pakai branch sendiri dan commit atas nama sendiri supaya kontribusi terlihat di riwayat Git (sesuai aturan tim di proposal).

## 5. Pelacak progres

| Fase | Fitur | Status |
|---|---|---|
| 0 | Scaffold | ☑ |
| 1 | F01 | ☑ |
| 2 | F02–F04 | ☑ |
| 3 | F05–F06 | ☑ |
| 4 | F07–F09 | ☑ |
| 5 | F10–F11 | ☑ |
| 6 | F12 | ☑ |
| 7 | F13–F14 | ☑ |
| 8 | Hardening & uji | ☑ |
| 9 | Deploy & docs | ☑ kode & dokumentasi siap; deploy ke server dan UAT belum dijalankan |

Hasil pengujian lengkap ada di [tests/black-box.md](tests/black-box.md).
