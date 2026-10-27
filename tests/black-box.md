# Pengujian Black Box - LMS SMA

Acuan: `PRD.md` bagian 10. Kolom **Hasil aktual** diisi saat pengujian dijalankan.

- Lingkungan uji: PHP 8.2.12, MariaDB 10.4.32 (XAMPP), server uji `php -S 127.0.0.1:8099`
- Skrip bantu: `tests/uji.sh` (login, kirim POST bertoken, unggah berkas)
- Akun demo: `admin/admin12345`, `guru.siti` dan `guru.budi` (`guru12345`),
  `siswa.ahmad` (X-1) dan `siswa.fitri` (X-2) (`siswa12345`)

Cara menjalankan skrip bantu:

```bash
source tests/uji.sh
login admin admin admin12345
status admin /admin/pengguna
```

## A. Skenario dari proposal (T01-T05)

| Kode | Skenario | Hasil diharapkan | Hasil aktual | Status |
|---|---|---|---|---|
| T01 | Login dengan password salah | Pesan gagal generik, tidak masuk | HTTP 200 tetap di halaman login, pesan "Username atau password salah." | LOLOS |
| T02 | Kumpul tugas setelah tenggat | Tersimpan, status "terlambat" | Baris `pengumpulan` tersimpan `status=terlambat`, pesan "status TERLAMBAT (melewati tenggat)" | LOLOS |
| T03 | Unggah file `.exe` | Ditolak, pesan format tidak diizinkan | "Format berkas tidak diizinkan. Gunakan: pdf, docx, pptx, jpg, jpeg, png." | LOLOS |
| T04 | Waktu kuis habis saat mengerjakan | Jawaban tersimpan otomatis, nilai terhitung | (diisi di bagian D) | |
| T05 | Siswa buka URL admin | Diarahkan ke "akses ditolak" | HTTP 403 pada `/admin`, `/admin/pengguna`, `/admin/kelas`, `/admin/mapel`, `/admin/tahun`, `/admin/pengampu` | LOLOS |

## B. Skenario keamanan tambahan (T06-T12)

| Kode | Skenario | Hasil diharapkan | Hasil aktual | Status |
|---|---|---|---|---|
| T06 | Siswa kelas X-2 akses materi kelas X-1 lewat ID di URL | 403 | `siswa.fitri` -> `/siswa/materi/1` = 403, `/siswa/materi/unduh/1` = 403; `siswa.ahmad` (pemilik kelas) = 200 | LOLOS |
| T07 | Form POST tanpa token CSRF | Ditolak | HTTP 403 pada `/admin/mapel/simpan` dan `/logout` tanpa token | LOLOS |
| T08 | File `.php` diganti nama jadi `.pdf` | Ditolak oleh cek MIME | "Isi berkas tidak cocok dengan ekstensinya (terdeteksi: text/x-php)." | LOLOS |
| T09 | Input `<script>` pada judul | Tidak tereksekusi | Tersimpan lalu ditampilkan sebagai `&lt;script&gt;alert(1)&lt;/script&gt;` | LOLOS |
| T10 | Siswa membuka kuis kedua kali | Diarahkan ke hasil | (diisi di bagian D) | |
| T11 | Kunjungan langsung ke `/uploads/xxx.pdf` | Ditolak | HTTP 404 (document root ada di `public/`, `uploads/` di luar root dan diblok `.htaccess`) | LOLOS |
| T12 | Guru A menilai tugas guru B | 403 | `guru.budi` -> halaman nilai 403, POST simpan nilai 403, unduh pengumpulan 403, nilai di DB tetap NULL | LOLOS |

## C. Kebutuhan fungsional F01-F04

| Kode | Skenario | Hasil diharapkan | Hasil aktual | Status |
|---|---|---|---|---|
| F01.1 | Login tiga peran | Masuk ke dashboard sesuai peran | admin -> `/admin`, guru -> `/guru`, siswa -> `/siswa` | LOLOS |
| F01.2 | Login akun `status=nonaktif` | Ditolak dengan pesan generik | `guru.agus` ditolak, pesan sama dengan password salah | LOLOS |
| F01.3 | Ganti password: password lama salah | Ditolak | "Password lama tidak cocok." | LOLOS |
| F01.4 | Ganti password: konfirmasi beda | Ditolak | "Konfirmasi password tidak sama dengan password baru." | LOLOS |
| F01.5 | Ganti password: kurang dari 8 karakter | Ditolak | "Password baru minimal 8 karakter." | LOLOS |
| F01.6 | Ganti password: sama dengan yang lama | Ditolak | "Password baru harus berbeda dari password lama." | LOLOS |
| F01.7 | Ganti password valid | Tersimpan, bisa login dengan password baru | Redirect `/profil`, login berikutnya berhasil | LOLOS |
| F02.1 | Siswa tanpa kelas | Ditolak | "Siswa wajib ditempatkan di satu kelas." | LOLOS |
| F02.2 | Username duplikat | Ditolak | "Username sudah dipakai." | LOLOS |
| F02.3 | Username berformat salah | Ditolak | "Username 3 sampai 50 karakter, hanya huruf kecil, angka, titik, garis bawah, atau strip." | LOLOS |
| F02.4 | Password kurang dari 8 karakter | Ditolak | "Password minimal 8 karakter." | LOLOS |
| F02.5 | Nonaktifkan pengguna | Soft delete, tidak bisa login | `status=nonaktif` di DB, login ditolak, setelah diaktifkan bisa login lagi | LOLOS |
| F02.6 | Reset password oleh admin | Password baru berlaku | Login dengan password hasil reset berhasil | LOLOS |
| F02.7 | Admin menonaktifkan akun sendiri | Ditolak | "Anda tidak bisa menonaktifkan akun Anda sendiri." | LOLOS |
| F02.8 | Impor CSV 32 baris (28 valid, 4 sengaja salah) | 28 masuk, 4 dilaporkan dengan nomor baris dan alasan | 28 berhasil, 4 gagal: baris 30 kelas tidak ada, baris 31 nama kosong, baris 32 username sudah terdaftar, baris 33 password kurang dari 8 karakter | LOLOS |
| F02.9 | Cari, filter peran/kelas/status, paginasi | Hasil sesuai filter | `q=Impor 01` -> 1 baris; `kelas=2` -> 3 siswa X-2; `status=nonaktif` -> `guru.agus`; 15 baris per halaman, 3 halaman | LOLOS |
| F03.1 | Tahun ajaran: aktifkan satu | Yang lain otomatis nonaktif | `COUNT(aktif=1)` tetap 1 setelah pergantian | LOLOS |
| F03.2 | Hapus tahun ajaran yang aktif | Ditolak | "Tahun ajaran yang sedang aktif tidak bisa dihapus." | LOLOS |
| F03.3 | Hapus tahun ajaran yang punya kelas | Ditolak dengan alasan | "Tahun ajaran tidak bisa dihapus karena masih dipakai 1 kelas." | LOLOS |
| F03.4 | Kode mapel duplikat | Ditolak | "Kode mata pelajaran sudah dipakai." | LOLOS |
| F03.5 | Hapus mapel yang dipakai pengampu | Ditolak dengan alasan | "Mata pelajaran tidak bisa dihapus karena masih dipakai 3 penugasan guru pengampu." | LOLOS |
| F04.1 | Pengampu duplikat (kelas, mapel) | Ditolak | "Mata pelajaran itu sudah punya guru pengampu di kelas tersebut." | LOLOS |
| F04.2 | Pengampu dengan guru nonaktif | Ditolak | "Guru tersebut berstatus nonaktif." | LOLOS |
| F04.3 | Hapus pengampu yang masih punya konten | Ditolak dengan alasan | Pesan menyebut jumlah materi/tugas/kuis/pengumuman | LOLOS |

## D. Materi, tugas, kuis (F05-F12)

| Kode | Skenario | Hasil diharapkan | Hasil aktual | Status |
|---|---|---|---|---|
| F05.1 | Unggah materi PDF sah | Tersimpan dengan nama acak | DB: `file=87070d9c...e308.pdf`, `file_asli=materi-sah.pdf` | LOLOS |
| F05.2 | Ganti berkas materi | Berkas lama terhapus dari disk | Berkas lama hilang dari `uploads/`, berkas baru ada | LOLOS |
| F05.3 | Hapus materi | Record dan berkas fisik hilang | `COUNT(materi)=0`, folder `uploads/` kosong | LOLOS |
| F05.4 | Guru lain mengubah/mengunduh materi bukan miliknya | 403 | `guru.budi` -> `/guru/materi/form/1` = 403, `/guru/materi/unduh/1` = 403 | LOLOS |
| F06.1 | Siswa unduh materi kelasnya | Berkas terkirim dengan nama asli | Header `Content-Disposition: attachment; filename="materi-sah.pdf"`, isi dimulai `%PDF-1.4` | LOLOS |
| F07.1 | Buat tugas dengan tenggat | Tersimpan | 2 tugas tersimpan dengan tenggat benar | LOLOS |
| F07.2 | Hapus tugas yang sudah ada pengumpulan | Ditolak | "Tugas tidak bisa dihapus karena sudah ada 1 pengumpulan dari siswa." | LOLOS |
| F08.1 | Kumpul sebelum tenggat | Status `tepat_waktu` | DB: `status=tepat_waktu` | LOLOS |
| F08.2 | Kumpul setelah tenggat (= T02) | Status `terlambat`, tetap diterima | DB: `status=terlambat` | LOLOS |
| F08.3 | Kumpul ulang sebelum dinilai | Berkas lama terhapus, waktu dan status dihitung ulang | Berkas lama hilang dari disk, berkas baru tercatat | LOLOS |
| F08.4 | Kumpul ulang setelah dinilai | Ditolak (terkunci) | "Tugas ini sudah dinilai sehingga tidak bisa dikumpulkan ulang." | LOLOS |
| F08.5 | Siswa kelas lain buka/unduh tugas kelas lain | 403 | `siswa.fitri` -> `/siswa/tugas/detail/1` = 403, `/siswa/tugas/berkas/1` = 403 | LOLOS |
| F09.1 | Nilai di luar 0-100 | Ditolak | "Nilai harus di antara 0 dan 100." (untuk 150 dan -5) | LOLOS |
| F09.2 | Nilai bukan angka | Ditolak | "Nilai wajib berupa angka." | LOLOS |
| F09.3 | Nilai 85.5 dengan komentar | Tersimpan | DB: `nilai=85.50`, komentar tersimpan | LOLOS |
| F09.4 | Daftar penilaian memuat siswa yang belum mengumpulkan | Tampil dengan status "Belum mengumpulkan" | Semua siswa aktif kelas itu tampil | LOLOS |
| F10.1 | Kunci jawaban menunjuk opsi kosong | Ditolak | "Kunci jawaban menunjuk opsi yang kosong." | LOLOS |
| F10.2 | Opsi B dikosongkan | Ditolak | "Opsi B wajib diisi." | LOLOS |
| F10.3 | Opsi C-E dikosongkan | Diterima, disimpan NULL | Soal 2 tersimpan dengan `opsi_c..e = NULL` | LOLOS |
| F10.4 | Ubah/hapus soal pada kuis yang sudah ada hasilnya | Ditolak | "Kuis sudah dikerjakan siswa, soal tidak bisa diubah lagi." | LOLOS |
| F11.1 | Kunci jawaban terkirim ke browser siswa | TIDAK boleh | Kata "kunci" tidak ada di HTML halaman pengerjaan; opsi NULL tidak dirender | LOLOS |
| F11.2 | Countdown dihitung dari waktu server | `data-sisa` berasal dari server | `data-sisa="49"` untuk durasi 1 menit yang sudah jalan 11 detik | LOLOS |
| F11.3 | Autosave jawaban lewat AJAX | Tersimpan per pilihan | 3 balasan `{"ok":true,"sisa":48}`, 3 baris di `jawaban_kuis` | LOLOS |
| F11.4 | Waktu habis lalu kirim jawaban (= T04) | Ditolak, percobaan difinalisasi dari jawaban tersimpan | Balasan `{"ok":false,"selesai":true,...}`; nilai menjadi 50.00 (2 benar dari 4 soal); jawaban soal 4 tidak masuk | LOLOS |
| F11.5 | Tutup tab di tengah kuis, buka lagi setelah waktu habis | Hasil sudah final | Siswa kedua jawab 4 soal benar tanpa menekan Kumpulkan; setelah batas lewat, membuka `/siswa/kuis/kerjakan/1` -> redirect ke hasil, nilai 100.00 | LOLOS |
| F11.6 | Memulai kuis kedua kali (= T10) | Diarahkan ke hasil, tetap satu percobaan | POST `mulai` -> redirect `/siswa/kuis/hasil/1`; `COUNT(hasil_kuis)` tetap 1; nilai tidak berubah | LOLOS |
| F11.7 | Soal tidak dijawab | Dihitung salah | Soal 4 tidak dijawab, nilai 50.00 bukan 66.67 | LOLOS |
| F12.1 | Rekap per kelas+mapel cocok dengan hitungan manual | Angka sama | Ahmad 85,5 / - / 50 -> rata 67,75; Bella - / - / 100 -> rata 100; Candra, Dinda, Eko -> semua "-"; rata-rata kelas 83,88. Hitungan manual: (85,5+50)/2 = 67,75 dan (67,75+100)/2 = 83,88 | LOLOS |
| F12.2 | Nilai kosong tidak dihitung sebagai 0 | Rata-rata hanya dari nilai yang ada | Ahmad punya 2 nilai dari 3 kolom, rata-rata 67,75 (bukan 45,17 bila kosong dihitung 0) | LOLOS |
| F12.3 | Ekspor CSV | BOM UTF-8, terbuka rapi di Excel | `Content-Type: text/csv; charset=utf-8`, nama berkas `rekap-x-1-mtk-20261005.csv`, 3 byte pertama `ef bb bf`, pemisah `;`, desimal koma, sel kosong untuk nilai yang belum ada | LOLOS |
| F12.4 | Guru lain mengekspor rekap bukan miliknya | 403 | `guru.budi` -> `/guru/rekap/csv/1` = 403, `/guru/rekap/kelas/1` = 403 | LOLOS |
| F13.1 | Guru buat pengumuman, siswa kelasnya melihat | Tampil di halaman siswa | "Ulangan Harian Jumat" tampil untuk `siswa.ahmad` (X-1) | LOLOS |
| F13.2 | Siswa kelas lain melihat pengumuman kelas lain | Tidak boleh | `siswa.fitri` (X-2) tidak melihat pengumuman X-1 | LOLOS |
| F13.3 | Guru lain mengubah pengumuman bukan miliknya | 403 | `guru.budi` -> form ubah = 403 | LOLOS |
| F14.1 | Dashboard per peran | Angka sesuai data | Guru: 2 kelas-mapel, 1 pengumpulan belum dinilai, 1 kuis aktif. Siswa: jumlah mapel, materi, tugas belum dikumpulkan | LOLOS |

## E. Non-fungsional

| Kode | Skenario | Hasil diharapkan | Hasil aktual | Status |
|---|---|---|---|---|
| N01 | Rate limit login | 5 gagal berturut-turut lalu ditahan | Percobaan 1-5 "Username atau password salah", percobaan 6 "Terlalu banyak percobaan gagal. Coba lagi dalam 299 detik." | LOLOS |
| N02 | Password benar saat masa tahanan | Tetap ditolak | Ditolak dengan pesan tahanan yang sama | LOLOS |
| N03 | GET tidak boleh mengubah data | Rute aksi hanya menerima POST | `GET /admin/mapel/simpan` = 404, `GET /logout` = 404 | LOLOS |
| N04 | Zona waktu PHP dan MySQL sinkron | Keduanya Asia/Jakarta | PHP `Asia/Jakarta` dan MySQL session `+07:00` sama-sama melaporkan `2026-10-05 14:36:40` | LOLOS |
| N05 | Header keamanan | nosniff, DENY, same-origin | `X-Content-Type-Options: nosniff`, `X-Frame-Options: DENY` (terbukti memblok iframe saat pengujian), `Referrer-Policy: same-origin` | LOLOS |
| N06 | Cookie sesi | HttpOnly, SameSite | `httponly=true`, `samesite=Lax`, `secure` mengikuti `app.https` | LOLOS |
| N07 | Responsif 360 px | Tidak ada geser horizontal halaman | Semua halaman: `window.scrollX` tetap 0. Tabel lebar (penilaian, rekap) bergeser di dalam `.table-responsive`, bukan menggeser halaman | LOLOS |
| N08 | Navbar di layar HP | Menu bisa dibuka dan dipakai | Tombol toggler membuka 7 menu guru + dropdown akun; logout berfungsi | LOLOS |
| N09 | Semua halaman dapat diakses peran yang berhak | HTTP 200 | 11 halaman siswa, 8 halaman admin, 15 halaman guru semuanya 200 | LOLOS |
| N10 | Lint seluruh berkas PHP | Tidak ada error sintaks | 92 berkas `.php` lolos `php -l` | LOLOS |

## F. Audit keamanan statis

Dijalankan dengan grep terhadap seluruh pohon kode.

| No | Yang diperiksa | Temuan |
|---|---|---|
| 1 | SQL di controller atau view | Nihil. Seluruh SQL hanya di `app/models/` |
| 2 | Interpolasi variabel ke dalam string SQL | Nihil. Satu-satunya interpolasi adalah `LIMIT`/`OFFSET` yang sudah di-cast `(int)`, dan fragmen `WHERE` yang disusun dari string literal tetap dengan placeholder `?` |
| 3 | Output tanpa `e()` | Nihil. Sisa `<?= ... ?>` tanpa `e()` seluruhnya ternary yang mengeluarkan string literal tetap (`'selected'`, `'Simpan'`) |
| 4 | `nl2br()` tanpa `e()` di dalamnya | Nihil |
| 5 | Form POST tanpa `csrf_field()` | Nihil |
| 6 | Rute POST tanpa `wajibPost()` / `wajibPostJson()` | Nihil dari 31 rute POST |
| 7 | Rute tanpa daftar peran | Nihil dari 82 rute. Hanya 3 rute publik: `/`, `GET /login`, `POST /login` |
| 8 | Hardcoded secret | Nihil |
| 9 | Sisa statement debug (`var_dump`, `print_r`, `console.log`, `die`) | Nihil |
| 10 | Konfigurasi PDO | `ERRMODE_EXCEPTION` aktif, `EMULATE_PREPARES` dimatikan |

## G. Catatan

- Pengujian dijalankan pada 5 Oktober 2026 memakai data demo dari
  `database/lms.sql` ditambah data uji yang dibuat lewat antarmuka.
- Dua pengujian memerlukan waktu nyata berlalu (F11.4 dan F11.5): kuis dibuat
  dengan `durasi_menit = 1` lalu ditunggu sampai server melaporkan batas waktu
  terlampaui.
- Belum dijalankan: UAT dengan guru dan siswa sungguhan (kuesioner Likert 1-5),
  serta pengujian ulang T01-T05 di server produksi. Keduanya menunggu
  penjadwalan dengan pihak sekolah.
