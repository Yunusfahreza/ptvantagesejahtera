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
