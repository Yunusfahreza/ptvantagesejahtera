# LMS SMA

Learning Management System ringan untuk satu sekolah SMA: materi, tugas, kuis
pilihan ganda, rekap nilai, dan pengumuman dalam satu platform.

Dibangun untuk SMAN 10 Garut oleh PT. Vantage Sejahtera
(Proposal Penawaran No. 001/VS-PNW/IX/2026).

- Spesifikasi: [PRD.md](PRD.md)
- Urutan kerja: [PLAN.md](PLAN.md)
- Aturan untuk Claude Code: [CLAUDE.md](CLAUDE.md)
- Hasil pengujian: [tests/black-box.md](tests/black-box.md)

## Teknologi

| Bagian | Teknologi |
|---|---|
| Bahasa | PHP 8.2 native, OOP, pola MVC (tanpa framework) |
| Basis data | MySQL / MariaDB lewat PDO prepared statement |
| Antarmuka | Bootstrap 5 (CDN) + JavaScript vanilla, tanpa build step |
| Dependensi | Tidak ada. Composer hanya opsional untuk PhpSpreadsheet |

Ekstensi PHP yang dibutuhkan: `pdo_mysql`, `fileinfo`, `mbstring` (ketiganya
aktif secara bawaan di XAMPP).

## Setup di XAMPP dari nol

1. **Salin proyek** ke `C:\xampp\htdocs\ptvantagesejahtera`.

2. **Nyalakan Apache dan MySQL** dari XAMPP Control Panel.

3. **Impor basis data.** Lewat phpMyAdmin (menu Import, pilih
   `database/lms.sql`), atau lewat terminal:

   ```bash
   C:/xampp/mysql/bin/mysql.exe -u root < database/lms.sql
   ```

   Skrip ini membuat database `lms_sma`, seluruh tabel, dan data demo.
   Peringatan: skrip membuang tabel yang sudah ada, jadi jangan dijalankan di
   atas data produksi.

4. **Siapkan konfigurasi.**

   ```bash
   cp config/config.example.php config/config.php
   ```

   Sesuaikan `db.user` dan `db.pass` bila MySQL Anda memakai password.
   `base_url` boleh dibiarkan kosong saat pengembangan: aplikasi akan
   mendeteksinya dari request.

5. **Arahkan document root ke `public/`.** Ini cara yang dianjurkan karena
   folder `app/`, `config/`, `uploads/`, dan `database/` jadi berada di luar
   jangkauan web. Tambahkan di `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

   ```apache
   <VirtualHost *:80>
       ServerName lms.test
       DocumentRoot "C:/xampp/htdocs/ptvantagesejahtera/public"
       <Directory "C:/xampp/htdocs/ptvantagesejahtera/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

   Lalu tambahkan `127.0.0.1 lms.test` ke
   `C:\Windows\System32\drivers\etc\hosts` dan restart Apache.

   **Tanpa virtual host** aplikasi tetap jalan di
   `http://localhost/ptvantagesejahtera/public`. `.htaccess` di root sudah
   memblok akses ke folder sensitif, tapi arahkan document root ke `public/`
   bila memungkinkan.

6. **Buka aplikasi** dan login dengan akun demo di bawah.

## Menjalankan tanpa Apache

Untuk pengembangan atau pengujian cepat, server bawaan PHP sudah cukup:

```bash
php -S 127.0.0.1:8099 -t public public/index.php
```

Aplikasi lalu bisa dibuka di `http://127.0.0.1:8099`.

## Akun demo

Password di bawah hanya untuk demo. **Ganti semuanya sebelum dipakai nyata.**

| Peran | Username | Password | Keterangan |
|---|---|---|---|
| Admin | `admin` | `admin12345` | |
| Guru | `guru.siti` | `guru12345` | Matematika X-1 dan X-2 |
| Guru | `guru.budi` | `guru12345` | Fisika X-1, Matematika XI-1 |
| Guru | `guru.dewi` | `guru12345` | Bahasa Indonesia X-1 dan XI-1 |
| Guru | `guru.agus` | `guru12345` | Sengaja **nonaktif**, untuk uji penolakan login |
| Siswa | `siswa.ahmad` | `siswa12345` | Kelas X-1 |
| Siswa | `siswa.fitri` | `siswa12345` | Kelas X-2, untuk uji isolasi antar kelas |

Data demo lain: 10 siswa di 3 kelas, 8 mata pelajaran, 6 penugasan pengampu,
2 tahun ajaran (`2026/2027 ganjil` yang aktif).

## Alur penyiapan sekolah baru

Urutannya tidak bisa dibalik karena setiap langkah bergantung pada langkah
sebelumnya:

1. **Tahun ajaran** - buat dan aktifkan satu tahun ajaran.
2. **Kelas** - buat kelas pada tahun ajaran aktif.
3. **Mata pelajaran** - daftarkan mapel beserta kodenya.
4. **Pengguna** - tambahkan guru dan siswa (siswa bisa diimpor dari CSV).
5. **Guru pengampu** - pasangkan guru dengan kelas dan mapel.

Setelah langkah 5, guru bisa langsung mengunggah materi, membuat tugas dan
kuis, serta menulis pengumuman.

### Format impor CSV siswa

Baris pertama wajib header. Pemisah koma maupun titik koma keduanya diterima.

```csv
nama,username,password,kelas
Ahmad Fauzi,siswa.ahmad2,rahasia123,X-1
Bella Puspita,siswa.bella2,rahasia123,X-1
```

Kolom `kelas` diisi nama kelas yang sudah ada di tahun ajaran aktif. Baris yang
gagal dilaporkan dengan nomor baris dan alasannya; baris yang valid tetap
tersimpan. Contoh berkas bisa diunduh dari halaman impor.

## Struktur proyek

```
ptvantagesejahtera/
├── app/
│   ├── controllers/   # satu controller per modul
│   ├── models/        # satu-satunya lapisan yang menulis SQL
│   ├── views/         # admin/, guru/, siswa/, layouts/, errors/
│   ├── core/          # Router, Database, Auth, Csrf, FileUploader, ...
│   └── routes.php     # daftar rute + peran yang berhak
├── public/
│   ├── index.php      # satu-satunya pintu masuk
│   └── assets/        # css, js
├── uploads/           # berkas materi dan tugas (diblok dari web)
├── config/            # config.example.php (config.php tidak di-commit)
├── database/lms.sql   # skema + seed
└── tests/             # dokumen dan skrip pengujian
```

## Catatan keamanan

- Password disimpan dengan `password_hash()` (bcrypt), diverifikasi dengan
  `password_verify()`.
- Semua query memakai PDO prepared statement dengan
  `ATTR_EMULATE_PREPARES = false`.
- Semua output dinamis di-escape lewat helper `e()`.
- Semua request POST (termasuk AJAX) memverifikasi token CSRF.
- Unggahan divalidasi tiga lapis: ekstensi, tipe MIME dari isi berkas
  (`finfo`), dan ukuran maksimal 10 MB. Berkas disimpan dengan nama acak dan
  hanya bisa diunduh lewat controller yang memeriksa hak akses.
- Hak akses diperiksa dua kali: peran di router, kepemilikan data di
  controller. Menyembunyikan menu bukan mekanisme keamanan.
- Waktu, status pengumpulan, dan nilai kuis dihitung di server. Mengubah jam
  komputer atau mematikan JavaScript tidak memberi keuntungan.

## Menyiapkan untuk produksi

Sebelum dipakai nyata:

1. **Ganti semua password seed**, terutama akun `admin`.
2. **Isi `base_url` secara eksplisit** di `config/config.php`, misalnya
   `https://lms.sman10garut.sch.id`.
3. **Matikan debug**: `app.debug => false`. Error akan masuk ke log server,
   bukan ke layar pengguna.
4. **Aktifkan HTTPS lalu set `app.https => true`** supaya cookie sesi dikirim
   dengan flag `Secure`.
5. **Buat pengguna MySQL khusus** dengan hak hanya pada database `lms_sma`,
   jangan memakai `root`.
6. **Pastikan document root menunjuk ke `public/`.**
7. **Pastikan folder `uploads/` bisa ditulis** oleh proses web server, tapi
   tidak bisa dieksekusi sebagai PHP. `uploads/.htaccess` sudah mengaturnya di
   Apache; di Nginx tambahkan aturan setara (lihat contoh di bawah).
8. **Siapkan backup** basis data dan folder `uploads/` secara berkala.

### Contoh konfigurasi Nginx

```nginx
server {
    listen 443 ssl;
    server_name lms.sman10garut.sch.id;
    root /var/www/lms/public;
    index index.php;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ [.]php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    # Lapisan tambahan: folder di luar public tidak perlu disajikan.
    location ~ ^/(app|config|database|tests|uploads)/ {
        deny all;
    }
}
```

## Pengujian

`tests/black-box.md` memuat seluruh skenario beserta hasil aktualnya.
`tests/uji.sh` berisi fungsi bantu untuk menjalankan skenario lewat HTTP:

```bash
source tests/uji.sh
login admin admin admin12345
status admin /admin/pengguna
login siswa siswa.ahmad siswa12345
status siswa /admin
```

Dua perintah `status` di atas berturut-turut menghasilkan `200` (admin boleh)
dan `403` (siswa ditolak di server, bukan hanya disembunyikan menunya).
