-- =====================================================================
-- LMS SMA - RESET LENGKAP: Skema + Migrasi Fitur Baru + Data Demo Lengkap
-- PT. Vantage Sejahtera / SMAN 10 Garut
--
-- File ini berisi:
--   1. Skema tabel lengkap (tabel utama & fitur baru)
--   2. Data master (Tahun Ajaran, Kelas, Mapel)
--   3. Data 14 Guru lengkap dengan NIP, mata pelajaran, dan jadwal
--   4. Data 60 Siswa lengkap (10 siswa per kelas di 6 kelas)
--   5. 54 Penugasan Pengampu mata pelajaran
--   6. Presensi / Kehadiran lengkap per kelas yang telah divalidasi guru
--   7. 8 Kuis interaktif dengan 40 butir Soal pilihan ganda
--   8. Riwayat Hasil Kuis dan Jawaban Kuis siswa lengkap dengan skor
--   9. Materi, Tugas, Pengumpulan Tugas dengan nilai & feedback guru
--   10. Modul Perpustakaan Digital, Video Pembelajaran, Catatan, & Kalender
--
-- Password default:
--   Admin : admin12345
--   Guru  : guru12345
--   Siswa : siswa12345
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `lms_sma`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `lms_sma`;

-- Hapus tabel fitur baru dulu (urut dependensi)
DROP TABLE IF EXISTS `catatan_siswa`;
DROP TABLE IF EXISTS `pencapaian`;
DROP TABLE IF EXISTS `kalender_akademik`;
DROP TABLE IF EXISTS `absensi`;
DROP TABLE IF EXISTS `jadwal`;
DROP TABLE IF EXISTS `perpustakaan`;
DROP TABLE IF EXISTS `video_pembelajaran`;

-- Hapus tabel lama
DROP TABLE IF EXISTS `jawaban_kuis`;
DROP TABLE IF EXISTS `hasil_kuis`;
DROP TABLE IF EXISTS `soal`;
DROP TABLE IF EXISTS `kuis`;
DROP TABLE IF EXISTS `pengumpulan`;
DROP TABLE IF EXISTS `tugas`;
DROP TABLE IF EXISTS `materi`;
DROP TABLE IF EXISTS `pengumuman`;
DROP TABLE IF EXISTS `pengampu`;
DROP TABLE IF EXISTS `mapel`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `kelas`;
DROP TABLE IF EXISTS `tahun_ajaran`;

-- =====================================================================
-- TABEL MASTER
-- =====================================================================

CREATE TABLE `tahun_ajaran` (
    `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama`     VARCHAR(20) NOT NULL,
    `semester` ENUM('ganjil','genap') NOT NULL,
    `aktif`    TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_tahun_semester` (`nama`, `semester`),
    KEY `idx_aktif` (`aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kelas` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama`            VARCHAR(30) NOT NULL,
    `tingkat`         ENUM('X','XI','XII') NOT NULL,
    `tahun_ajaran_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_kelas_tahun` (`nama`, `tahun_ajaran_id`),
    KEY `idx_kelas_tahun` (`tahun_ajaran_id`),
    CONSTRAINT `fk_kelas_tahun` FOREIGN KEY (`tahun_ajaran_id`)
        REFERENCES `tahun_ajaran` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama`          VARCHAR(100) NOT NULL,
    `username`      VARCHAR(50) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role`          ENUM('admin','guru','siswa') NOT NULL,
    `kelas_id`      INT UNSIGNED NULL,
    `status`        ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    `foto`          VARCHAR(100) NULL,
    `nomor_induk`   VARCHAR(30) NULL,
    `tempat_lahir`  VARCHAR(100) NULL,
    `tanggal_lahir` DATE NULL,
    `alamat`        TEXT NULL,
    `no_hp`         VARCHAR(20) NULL,
    `no_hp_ortu`    VARCHAR(20) NULL,
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_username` (`username`),
    KEY `idx_user_role` (`role`),
    KEY `idx_user_kelas` (`kelas_id`),
    KEY `idx_user_status` (`status`),
    CONSTRAINT `fk_user_kelas` FOREIGN KEY (`kelas_id`)
        REFERENCES `kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `mapel` (
    `id`   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `kode` VARCHAR(20) NOT NULL,
    `nama` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_mapel_kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pengampu` (
    `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `guru_id`  INT UNSIGNED NOT NULL,
    `kelas_id` INT UNSIGNED NOT NULL,
    `mapel_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_kelas_mapel` (`kelas_id`, `mapel_id`),
    KEY `idx_pengampu_guru` (`guru_id`),
    KEY `idx_pengampu_mapel` (`mapel_id`),
    CONSTRAINT `fk_pengampu_guru` FOREIGN KEY (`guru_id`)
        REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_pengampu_kelas` FOREIGN KEY (`kelas_id`)
        REFERENCES `kelas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_pengampu_mapel` FOREIGN KEY (`mapel_id`)
        REFERENCES `mapel` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- TABEL KONTEN
-- =====================================================================

CREATE TABLE `materi` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pengampu_id` INT UNSIGNED NOT NULL,
    `judul`       VARCHAR(150) NOT NULL,
    `deskripsi`   TEXT NULL,
    `file`        VARCHAR(100) NOT NULL,
    `file_asli`   VARCHAR(255) NOT NULL,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_materi_pengampu` (`pengampu_id`),
    CONSTRAINT `fk_materi_pengampu` FOREIGN KEY (`pengampu_id`)
        REFERENCES `pengampu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tugas` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pengampu_id`   INT UNSIGNED NOT NULL,
    `judul`         VARCHAR(150) NOT NULL,
    `deskripsi`     TEXT NULL,
    `lampiran`      VARCHAR(100) NULL,
    `lampiran_asli` VARCHAR(255) NULL,
    `tenggat`       DATETIME NOT NULL,
    `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_tugas_pengampu` (`pengampu_id`),
    KEY `idx_tugas_tenggat` (`tenggat`),
    CONSTRAINT `fk_tugas_pengampu` FOREIGN KEY (`pengampu_id`)
        REFERENCES `pengampu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pengumpulan` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tugas_id`       INT UNSIGNED NOT NULL,
    `siswa_id`       INT UNSIGNED NOT NULL,
    `file`           VARCHAR(100) NOT NULL,
    `file_asli`      VARCHAR(255) NOT NULL,
    `dikumpulkan_at` DATETIME NOT NULL,
    `status`         ENUM('tepat_waktu','terlambat') NOT NULL,
    `nilai`          DECIMAL(5,2) NULL,
    `komentar`       TEXT NULL,
    `dinilai_at`     DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_tugas_siswa` (`tugas_id`, `siswa_id`),
    KEY `idx_pengumpulan_siswa` (`siswa_id`),
    CONSTRAINT `fk_pengumpulan_tugas` FOREIGN KEY (`tugas_id`)
        REFERENCES `tugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_pengumpulan_siswa` FOREIGN KEY (`siswa_id`)
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kuis` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pengampu_id`  INT UNSIGNED NOT NULL,
    `judul`        VARCHAR(150) NOT NULL,
    `durasi_menit` INT UNSIGNED NOT NULL,
    `mulai`        DATETIME NOT NULL,
    `selesai`      DATETIME NOT NULL,
    `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_kuis_pengampu` (`pengampu_id`),
    KEY `idx_kuis_jendela` (`mulai`, `selesai`),
    CONSTRAINT `fk_kuis_pengampu` FOREIGN KEY (`pengampu_id`)
        REFERENCES `pengampu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `soal` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `kuis_id`    INT UNSIGNED NOT NULL,
    `pertanyaan` TEXT NOT NULL,
    `opsi_a`     VARCHAR(255) NOT NULL,
    `opsi_b`     VARCHAR(255) NOT NULL,
    `opsi_c`     VARCHAR(255) NULL,
    `opsi_d`     VARCHAR(255) NULL,
    `opsi_e`     VARCHAR(255) NULL,
    `kunci`      ENUM('a','b','c','d','e') NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_soal_kuis` (`kuis_id`),
    CONSTRAINT `fk_soal_kuis` FOREIGN KEY (`kuis_id`)
        REFERENCES `kuis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `hasil_kuis` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `kuis_id`    INT UNSIGNED NOT NULL,
    `siswa_id`   INT UNSIGNED NOT NULL,
    `nilai`      DECIMAL(5,2) NULL,
    `mulai_at`   DATETIME NOT NULL,
    `selesai_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_kuis_siswa` (`kuis_id`, `siswa_id`),
    KEY `idx_hasil_siswa` (`siswa_id`),
    CONSTRAINT `fk_hasil_kuis` FOREIGN KEY (`kuis_id`)
        REFERENCES `kuis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_hasil_siswa` FOREIGN KEY (`siswa_id`)
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jawaban_kuis` (
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `hasil_kuis_id` INT UNSIGNED NOT NULL,
    `soal_id`       INT UNSIGNED NOT NULL,
    `jawaban`       ENUM('a','b','c','d','e') NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_hasil_soal` (`hasil_kuis_id`, `soal_id`),
    KEY `idx_jawaban_soal` (`soal_id`),
    CONSTRAINT `fk_jawaban_hasil` FOREIGN KEY (`hasil_kuis_id`)
        REFERENCES `hasil_kuis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_jawaban_soal` FOREIGN KEY (`soal_id`)
        REFERENCES `soal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pengumuman` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pengampu_id` INT UNSIGNED NOT NULL,
    `judul`       VARCHAR(150) NOT NULL,
    `isi`         TEXT NOT NULL,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_pengumuman_pengampu` (`pengampu_id`),
    CONSTRAINT `fk_pengumuman_pengampu` FOREIGN KEY (`pengampu_id`)
        REFERENCES `pengampu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
-- TABEL FITUR BARU
-- =====================================================================

CREATE TABLE `video_pembelajaran` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pengampu_id`  INT UNSIGNED NOT NULL,
    `judul`        VARCHAR(150) NOT NULL,
    `deskripsi`    TEXT NULL,
    `tipe`         ENUM('upload','youtube','gdrive') NOT NULL,
    `file`         VARCHAR(100) NULL,
    `file_asli`    VARCHAR(255) NULL,
    `url_embed`    VARCHAR(500) NULL,
    `durasi`       VARCHAR(10) NULL,
    `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_video_pengampu` (`pengampu_id`),
    CONSTRAINT `fk_video_pengampu` FOREIGN KEY (`pengampu_id`)
        REFERENCES `pengampu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `perpustakaan` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `judul`       VARCHAR(200) NOT NULL,
    `penulis`     VARCHAR(150) NULL,
    `deskripsi`   TEXT NULL,
    `kategori`    ENUM('wajib','pendukung') NOT NULL DEFAULT 'pendukung',
    `mapel_id`    INT UNSIGNED NULL,
    `tingkat`     ENUM('X','XI','XII','semua') NOT NULL DEFAULT 'semua',
    `file`        VARCHAR(100) NOT NULL,
    `file_asli`   VARCHAR(255) NOT NULL,
    `cover`       VARCHAR(100) NULL,
    `uploader_id` INT UNSIGNED NOT NULL,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_perpus_mapel` (`mapel_id`),
    KEY `idx_perpus_tingkat` (`tingkat`),
    CONSTRAINT `fk_perpus_mapel` FOREIGN KEY (`mapel_id`)
        REFERENCES `mapel` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_perpus_uploader` FOREIGN KEY (`uploader_id`)
        REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jadwal` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pengampu_id` INT UNSIGNED NOT NULL,
    `hari`        ENUM('senin','selasa','rabu','kamis','jumat','sabtu') NOT NULL,
    `jam_mulai`   TIME NOT NULL,
    `jam_selesai`  TIME NOT NULL,
    `ruangan`     VARCHAR(50) NULL,
    PRIMARY KEY (`id`),
    KEY `idx_jadwal_pengampu` (`pengampu_id`),
    KEY `idx_jadwal_hari` (`hari`),
    CONSTRAINT `fk_jadwal_pengampu` FOREIGN KEY (`pengampu_id`)
        REFERENCES `pengampu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `absensi` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pengampu_id` INT UNSIGNED NOT NULL,
    `siswa_id`    INT UNSIGNED NOT NULL,
    `tanggal`     DATE NOT NULL,
    `status`      ENUM('hadir','izin','sakit','alpha') NOT NULL,
    `keterangan`  VARCHAR(255) NULL,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_absensi` (`pengampu_id`, `siswa_id`, `tanggal`),
    KEY `idx_absensi_siswa` (`siswa_id`),
    KEY `idx_absensi_tanggal` (`tanggal`),
    CONSTRAINT `fk_absensi_pengampu` FOREIGN KEY (`pengampu_id`)
        REFERENCES `pengampu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_absensi_siswa` FOREIGN KEY (`siswa_id`)
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `catatan_siswa` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `siswa_id`   INT UNSIGNED NOT NULL,
    `mapel_id`   INT UNSIGNED NULL,
    `judul`      VARCHAR(200) NOT NULL,
    `isi`        TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_catatan_siswa` (`siswa_id`),
    CONSTRAINT `fk_catatan_siswa` FOREIGN KEY (`siswa_id`)
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_catatan_mapel` FOREIGN KEY (`mapel_id`)
        REFERENCES `mapel` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pencapaian` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `siswa_id`     INT UNSIGNED NOT NULL,
    `tipe`         VARCHAR(50) NOT NULL,
    `label`        VARCHAR(100) NOT NULL,
    `deskripsi`    VARCHAR(255) NULL,
    `ikon`         VARCHAR(10) NOT NULL DEFAULT '🏆',
    `diperoleh_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_pencapaian_siswa` (`siswa_id`),
    CONSTRAINT `fk_pencapaian_siswa` FOREIGN KEY (`siswa_id`)
        REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kalender_akademik` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `judul`           VARCHAR(200) NOT NULL,
    `deskripsi`       TEXT NULL,
    `tipe`            ENUM('ujian','libur','kegiatan','lainnya') NOT NULL,
    `tanggal_mulai`   DATE NOT NULL,
    `tanggal_selesai` DATE NOT NULL,
    `tahun_ajaran_id` INT UNSIGNED NOT NULL,
    `created_at`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_kalender_tanggal` (`tanggal_mulai`, `tanggal_selesai`),
    KEY `idx_kalender_tahun` (`tahun_ajaran_id`),
    CONSTRAINT `fk_kalender_tahun` FOREIGN KEY (`tahun_ajaran_id`)
        REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- DATA DEMO LENGKAP — SMAN 10 GARUT
-- =====================================================================

-- ========================
-- TAHUN AJARAN
-- ========================
INSERT INTO `tahun_ajaran` (`id`, `nama`, `semester`, `aktif`) VALUES
    (1, '2026/2027', 'ganjil', 1),
    (2, '2025/2026', 'genap',  0);

-- ========================
-- KELAS (6 kelas lengkap)
-- ========================
INSERT INTO `kelas` (`id`, `nama`, `tingkat`, `tahun_ajaran_id`) VALUES
    (1, 'X-1',     'X',   1),
    (2, 'X-2',     'X',   1),
    (3, 'XI-IPA',  'XI',  1),
    (4, 'XI-IPS',  'XI',  1),
    (5, 'XII-IPA', 'XII', 1),
    (6, 'XII-IPS', 'XII', 1);

-- ========================
-- MATA PELAJARAN (10 mapel)
-- ========================
INSERT INTO `mapel` (`id`, `kode`, `nama`) VALUES
    (1,  'MTK',  'Matematika'),
    (2,  'BIND', 'Bahasa Indonesia'),
    (3,  'BING', 'Bahasa Inggris'),
    (4,  'FIS',  'Fisika'),
    (5,  'KIM',  'Kimia'),
    (6,  'BIO',  'Biologi'),
    (7,  'SEJ',  'Sejarah Indonesia'),
    (8,  'INFO', 'Informatika'),
    (9,  'PPKN', 'Pendidikan Pancasila & Kewarganegaraan'),
    (10, 'PJOK', 'Pendidikan Jasmani, Olahraga & Kesehatan');

-- ========================
-- PENGGUNA — Admin Sekolah
-- ========================
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`,
                      `nomor_induk`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`) VALUES
    (1, 'Administrator Sekolah', 'admin',
     '$2y$10$9pT6bXvGn8lGIhGSBvijg.nuQOzHYQxckdqd7AzkRRhHSKfuZlI0W', 'admin', NULL, 'aktif',
     'NIP.198510012010011001', 'Garut', '1985-10-01', 'Jl. Merdeka No. 10, Garut', '081234567890');

-- ========================
-- PENGGUNA — 14 Guru Mata Pelajaran
-- Password: guru12345
-- ========================
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`,
                      `nomor_induk`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`) VALUES
    (2, 'Siti Rahmawati, S.Pd.', 'guru.siti',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.199003152015042002', 'Bandung', '1990-03-15', 'Jl. Merdeka No. 45, Garut', '081298765001'),

    (3, 'Budi Hartono, S.Si., M.Pd.', 'guru.budi',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.198807222014031004', 'Garut', '1988-07-22', 'Kp. Cikembar RT 3/8, Tarogong Kidul', '081298765002'),

    (4, 'Dewi Lestari, M.Pd.', 'guru.dewi',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.199205102018042001', 'Tasikmalaya', '1992-05-10', 'Jl. Pahlawan No. 12, Tarogong Kaler', '081298765003'),

    (5, 'Agus Setiawan, S.Kom., M.T.', 'guru.agus',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.199108302016011003', 'Sumedang', '1991-08-30', 'Perum Cagar Alam Blok B-5, Garut Kota', '081298765004'),

    (6, 'Rina Fitriani, S.Pd., M.Hum.', 'guru.rina',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.199312012019042005', 'Ciamis', '1993-12-01', 'Jl. Raya Samarang No. 78, Garut', '081298765005'),

    (7, 'Hendra Kusuma, S.Pd.', 'guru.hendra',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.198604172012011006', 'Garut', '1986-04-17', 'Kp. Sukamanah RT 1/3, Bayongbong', '081298765006'),

    (8, 'Dra. Hj. Maya Anggraeni, M.Si.', 'guru.maya',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.197906142005012003', 'Bandung', '1979-06-14', 'Jl. Cimanuk No. 112, Garut Kota', '081298765007'),

    (9, 'Asep Saepudin, S.Pd., M.Pkim.', 'guru.asep',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.198402192009021002', 'Garut', '1984-02-19', 'Jl. Bratayuda No. 67, Sukaregang', '081298765008'),

    (10, 'Drs. Bambang Sutrisno, M.Pd.', 'guru.bambang',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.197508212000031001', 'Yogyakarta', '1975-08-21', 'Komplek Gordah Indah No. 24, Garut', '081298765009'),

    (11, 'Dra. Nurul Hidayati, M.Pd.', 'guru.nurul',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.198103112006042004', 'Semarang', '1981-03-11', 'Jl. Patriot No. 15, Tarogong Kidul', '081298765010'),

    (12, 'Fajar Ramadhan, S.Kom., M.T.', 'guru.fajar',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.199404282020121008', 'Garut', '1994-04-28', 'Kp. Babakan Abid No. 4, Tarogong', '081298765011'),

    (13, 'Ratna Sari Dewi, S.Pd.', 'guru.ratna',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.199501142022032009', 'Bandung', '1995-01-14', 'Jl. Terusan Pahlawan No. 89, Garut', '081298765012'),

    (14, 'Drs. Slamet Wahyudi, M.Pd.', 'guru.slamet',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.197811052003121002', 'Cilacap', '1978-11-05', 'Perum Suci Permai Blok C-12, Karangpawitan', '081298765013'),

    (15, 'Dian Permatasari, S.Pd.', 'guru.dian',
     '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif',
     'NIP.199607182023022011', 'Garut', '1996-07-18', 'Kp. Cimuncang RT 2/7, Garut Kota', '081298765014');

-- ========================
-- PENGGUNA — 60 Siswa Lengkap (10 per Kelas di 6 Kelas)
-- Password: siswa12345
-- ========================

-- Kelas 1: X-1 (id 101 - 110)
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`,
                      `nomor_induk`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `no_hp_ortu`) VALUES
    (101, 'Ahmad Fauzi',      'siswa.ahmad',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif', '2610001', 'Garut', '2010-05-12', 'Jl. Otista No. 10, Tarogong', '085712340001', '081390001001'),
    (102, 'Bella Puspita',    'siswa.bella',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif', '2610002', 'Bandung', '2010-08-23', 'Kp. Pasirangin RT 2/5', '085712340002', '081390001002'),
    (103, 'Candra Wijaya',    'siswa.candra',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif', '2610003', 'Garut', '2010-02-14', 'Jl. Ciledug No. 33, Garut Kota', '085712340003', '081390001003'),
    (104, 'Dinda Maharani',   'siswa.dinda',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif', '2610004', 'Tasikmalaya', '2010-11-30', 'Perum Jayaraga Indah C-7', '085712340004', '081390001004'),
    (105, 'Eko Prasetyo',     'siswa.eko',     '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif', '2610005', 'Garut', '2010-07-19', 'Jl. Guntur No. 21, Sukamentri', '085712340005', '081390001005'),
    (106, 'Farah Azalea',     'siswa.farah',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif', '2610006', 'Sumedang', '2010-01-08', 'Kp. Babakan RT 4/6, Tarogong', '085712340006', '081390001006'),
    (107, 'Gilang Ramadhan',  'siswa.gilang',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif', '2610007', 'Garut', '2010-04-25', 'Jl. Pembangunan No. 5, Tarogong Kidul', '085712340007', '081390001007'),
    (108, 'Hana Safira',      'siswa.hana',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif', '2610008', 'Bandung', '2010-09-03', 'Jl. Dalem Kaum No. 8, Garut', '085712340008', '081390001008'),
    (109, 'Ilham Pratama',    'siswa.ilham',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif', '2610009', 'Garut', '2010-03-17', 'Kp. Bojong Larang RT 1/2', '085712340009', '081390001009'),
    (110, 'Jovanka Aurelia',  'siswa.jovanka', '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif', '2610010', 'Garut', '2010-10-10', 'Jl. Bratayuda No. 40, Garut Kota', '085712340010', '081390001010');

-- Kelas 2: X-2 (id 111 - 120)
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`,
                      `nomor_induk`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `no_hp_ortu`) VALUES
    (111, 'Irfan Maulana',    'siswa.irfan',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif', '2620001', 'Garut', '2010-03-16', 'Jl. Suherman No. 12', '085712340020', '081390002001'),
    (112, 'Jasmine Aulia',    'siswa.jasmine', '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif', '2620002', 'Garut', '2010-06-28', 'Kp. Tanjung RT 3/4', '085712340021', '081390002002'),
    (113, 'Kevin Ananta',     'siswa.kevin',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif', '2620003', 'Bandung', '2010-10-05', 'Jl. Sudirman No. 88', '085712340022', '081390002003'),
    (114, 'Lina Permata',     'siswa.lina',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif', '2620004', 'Ciamis', '2010-12-11', 'Kp. Patrol RT 2/1', '085712340023', '081390002004'),
    (115, 'Muhammad Rizki',   'siswa.rizki',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif', '2620005', 'Garut', '2010-08-07', 'Jl. Merdeka No. 15', '085712340024', '081390002005'),
    (116, 'Nabila Salsabila',  'siswa.nabila',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif', '2620006', 'Tasikmalaya', '2010-02-22', 'Perum Puri Gandasari D-3', '085712340025', '081390002006'),
    (117, 'Oscar Pratama',     'siswa.oscar',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif', '2620007', 'Garut', '2010-07-14', 'Jl. Bratayuda No. 18', '085712340026', '081390002007'),
    (118, 'Panji Wicaksono',   'siswa.panji',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif', '2620008', 'Garut', '2010-09-18', 'Kp. Hampor RT 1/5', '085712340027', '081390002008'),
    (119, 'Qonita Luthfiah',   'siswa.qonita',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif', '2620009', 'Bandung', '2010-11-04', 'Jl. Papandayan No. 22', '085712340028', '081390002009'),
    (120, 'Raditya Bagaskara', 'siswa.raditya', '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif', '2620010', 'Garut', '2010-04-12', 'Jl. Cimanuk No. 90', '085712340029', '081390002010');

-- Kelas 3: XI-IPA (id 121 - 130)
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`,
                      `nomor_induk`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `no_hp_ortu`) VALUES
    (121, 'Putri Wulandari',  'siswa.putri',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif', '2630001', 'Garut', '2009-04-20', 'Jl. Pasundan No. 45', '085712340030', '081390003001'),
    (122, 'Qori Ramadhani',  'siswa.qori',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif', '2630002', 'Bandung', '2009-01-15', 'Kp. Ciledug RT 2/3', '085712340031', '081390003002'),
    (123, 'Rendi Saputra',    'siswa.rendi',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif', '2630003', 'Garut', '2009-09-08', 'Jl. Cikuray No. 12', '085712340032', '081390003003'),
    (124, 'Sari Dewi Lestari','siswa.sari',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif', '2630004', 'Cianjur', '2009-06-30', 'Kp. Sukadana RT 4/2', '085712340033', '081390003004'),
    (125, 'Taufik Hidayat',   'siswa.taufik',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif', '2630005', 'Garut', '2009-11-22', 'Jl. Bank No. 9', '085712340034', '081390003005'),
    (126, 'Umi Habibah',      'siswa.umi',     '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif', '2630006', 'Sumedang', '2009-03-11', 'Perum Nusa Indah Blok E-2', '085712340035', '081390003006'),
    (127, 'Vito Adinata',     'siswa.vito',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif', '2630007', 'Garut', '2009-08-04', 'Jl. Veteran No. 34', '085712340036', '081390003007'),
    (128, 'Winda Cahyani',    'siswa.winda',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif', '2630008', 'Bandung', '2009-12-19', 'Kp. Panawuan RT 1/8', '085712340037', '081390003008'),
    (129, 'Yoga Pratama',     'siswa.yoga',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif', '2630009', 'Garut', '2009-05-14', 'Jl. Merdeka No. 70', '085712340038', '081390003009'),
    (130, 'Zalfa Nabilah',    'siswa.zalfa',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif', '2630010', 'Tasikmalaya', '2009-10-27', 'Jl. Siliwangi No. 11', '085712340039', '081390003010');

-- Kelas 4: XI-IPS (id 131 - 140)
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`,
                      `nomor_induk`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `no_hp_ortu`) VALUES
    (131, 'Aditya Nugroho',   'siswa.aditya',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 4, 'aktif', '2640001', 'Garut', '2009-02-18', 'Jl. Ahmad Yani No. 5', '085712340050', '081390004001'),
    (132, 'Bima Sakti',       'siswa.bima',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 4, 'aktif', '2640002', 'Bandung', '2009-07-29', 'Kp. Cipicung RT 3/2', '085712340051', '081390004002'),
    (133, 'Citra Kirana',     'siswa.citra',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 4, 'aktif', '2640003', 'Garut', '2009-11-15', 'Jl. Pahlawan No. 27', '085712340052', '081390004003'),
    (134, 'Danang Sudiro',    'siswa.danang',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 4, 'aktif', '2640004', 'Ciamis', '2009-04-03', 'Kp. Mekarsari RT 2/4', '085712340053', '081390004004'),
    (135, 'Elisa Handayani',  'siswa.elisa',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 4, 'aktif', '2640005', 'Garut', '2009-08-25', 'Jl. Ciledug No. 62', '085712340054', '081390004005'),
    (136, 'Fadli Zonanda',    'siswa.fadli',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 4, 'aktif', '2640006', 'Sumedang', '2009-01-30', 'Kp. Haurpanggung RT 1/3', '085712340055', '081390004006'),
    (137, 'Gita Gutawa',      'siswa.gita',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 4, 'aktif', '2640007', 'Garut', '2009-06-11', 'Perum Graha Indah Blok A-1', '085712340056', '081390004007'),
    (138, 'Haris Munandar',   'siswa.haris',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 4, 'aktif', '2640008', 'Tasikmalaya', '2009-09-09', 'Jl. Pasir Muncang No. 3', '085712340057', '081390004008'),
    (139, 'Intan Permatasari','siswa.intan',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 4, 'aktif', '2640009', 'Garut', '2009-12-05', 'Kp. Sukadana RT 2/7', '085712340058', '081390004009'),
    (140, 'Joko Susanto',     'siswa.joko',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 4, 'aktif', '2640010', 'Bandung', '2009-03-22', 'Jl. Bratayuda No. 80', '085712340059', '081390004010');

-- Kelas 5: XII-IPA (id 141 - 150)
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`,
                      `nomor_induk`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `no_hp_ortu`) VALUES
    (141, 'Xena Olivia',     'siswa.xena',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 5, 'aktif', '2650001', 'Garut', '2008-05-18', 'Jl. Karacak No. 14', '085712340040', '081390005001'),
    (142, 'Yuda Firmansyah', 'siswa.yuda',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 5, 'aktif', '2650002', 'Tasikmalaya', '2008-02-26', 'Kp. Babakan RT 3/1', '085712340041', '081390005002'),
    (143, 'Zahra Amira',     'siswa.zahra',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 5, 'aktif', '2650003', 'Garut', '2008-10-09', 'Jl. Merdeka No. 89', '085712340042', '081390005003'),
    (144, 'Aldi Nugraha',    'siswa.aldi',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 5, 'aktif', '2650004', 'Bandung', '2008-07-03', 'Kp. Cimuncang RT 1/4', '085712340043', '081390005004'),
    (145, 'Bunga Lestari',   'siswa.bunga',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 5, 'aktif', '2650005', 'Garut', '2008-01-14', 'Jl. Pramuka No. 19', '085712340044', '081390005005'),
    (146, 'Cipta Mandala',   'siswa.cipta',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 5, 'aktif', '2650006', 'Ciamis', '2008-11-27', 'Kp. Gordah RT 2/8', '085712340045', '081390005006'),
    (147, 'Diana Purnama',   'siswa.diana',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 5, 'aktif', '2650007', 'Garut', '2008-06-21', 'Jl. Suherman No. 44', '085712340046', '081390005007'),
    (148, 'Erlangga Saputra','siswa.erlangga','$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 5, 'aktif', '2650008', 'Bandung', '2008-03-31', 'Perum Nusa Indah C-5', '085712340047', '081390005008'),
    (149, 'Fani Anggraini',  'siswa.fani',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 5, 'aktif', '2650009', 'Garut', '2008-09-12', 'Jl. Cimanuk No. 120', '085712340048', '081390005009'),
    (150, 'Gilang Perkasa',  'siswa.gilangp', '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 5, 'aktif', '2650010', 'Tasikmalaya', '2008-12-01', 'Kp. Tanjung RT 4/1', '085712340049', '081390005010');

-- Kelas 6: XII-IPS (id 151 - 160)
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`,
                      `nomor_induk`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `no_hp`, `no_hp_ortu`) VALUES
    (151, 'Hendri Gunawan',  'siswa.hendri',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 6, 'aktif', '2660001', 'Garut', '2008-04-14', 'Jl. Terusan Merdeka No. 3', '085712340060', '081390006001'),
    (152, 'Indah Puspitasari','siswa.indah',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 6, 'aktif', '2660002', 'Bandung', '2008-08-19', 'Kp. Bojong RT 1/6', '085712340061', '081390006002'),
    (153, 'Jamaludin',       'siswa.jamal',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 6, 'aktif', '2660003', 'Garut', '2008-01-22', 'Jl. Patriot No. 12', '085712340062', '081390006003'),
    (154, 'Kurnia Sari',     'siswa.kurnia',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 6, 'aktif', '2660004', 'Ciamis', '2008-11-08', 'Kp. Pasar RT 2/2', '085712340063', '081390006004'),
    (155, 'Lutfi Hakim',     'siswa.lutfi',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 6, 'aktif', '2660005', 'Garut', '2008-06-30', 'Jl. Suci No. 51', '085712340064', '081390006005'),
    (156, 'Mega Utami',      'siswa.mega',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 6, 'aktif', '2660006', 'Sumedang', '2008-09-17', 'Kp. Ciledug Hilir RT 3/5', '085712340065', '081390006006'),
    (157, 'Naufal Dzaky',    'siswa.naufal',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 6, 'aktif', '2660007', 'Garut', '2008-03-25', 'Jl. Otista No. 110', '085712340066', '081390006007'),
    (158, 'Oktaviani Nur',   'siswa.okta',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 6, 'aktif', '2660008', 'Tasikmalaya', '2008-10-15', 'Perum Intan Asri B-7', '085712340067', '081390006008'),
    (159, 'Pramudya Ananta', 'siswa.pramudya','$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 6, 'aktif', '2660009', 'Garut', '2008-07-07', 'Jl. Bratayuda No. 99', '085712340068', '081390006009'),
    (160, 'Ranti Julia',     'siswa.ranti',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 6, 'aktif', '2660010', 'Bandung', '2008-12-29', 'Kp. Gordah Tonggoh RT 1/3', '085712340069', '081390006010');

-- ========================
-- GURU PENGAMPU (54 Penugasan Mata Pelajaran)
-- Setiap kombinasi (kelas_id, mapel_id) unik
-- ========================
INSERT INTO `pengampu` (`id`, `guru_id`, `kelas_id`, `mapel_id`) VALUES
    -- KELAS 1: X-1 (id 1 - 10)
    (1,  2, 1, 1),   -- Siti     -> MTK X-1
    (2,  4, 1, 2),   -- Dewi     -> BIND X-1
    (3,  6, 1, 3),   -- Rina     -> BING X-1
    (4,  3, 1, 4),   -- Budi     -> FIS X-1
    (5,  8, 1, 5),   -- Maya     -> KIM X-1
    (6,  9, 1, 6),   -- Asep     -> BIO X-1
    (7,  10, 1, 7),  -- Bambang  -> SEJ X-1
    (8,  5, 1, 8),   -- Agus     -> INFO X-1
    (9,  11, 1, 9),  -- Nurul    -> PPKN X-1
    (10, 7, 1, 10),  -- Hendra   -> PJOK X-1

    -- KELAS 2: X-2 (id 11 - 20)
    (11, 2, 2, 1),   -- Siti     -> MTK X-2
    (12, 15, 2, 2),  -- Dian     -> BIND X-2
    (13, 13, 2, 3),  -- Ratna    -> BING X-2
    (14, 3, 2, 4),   -- Budi     -> FIS X-2
    (15, 8, 2, 5),   -- Maya     -> KIM X-2
    (16, 9, 2, 6),   -- Asep     -> BIO X-2
    (17, 10, 2, 7),  -- Bambang  -> SEJ X-2
    (18, 5, 2, 8),   -- Agus     -> INFO X-2
    (19, 11, 2, 9),  -- Nurul    -> PPKN X-2
    (20, 7, 2, 10),  -- Hendra   -> PJOK X-2

    -- KELAS 3: XI-IPA (id 21 - 30)
    (21, 14, 3, 1),  -- Slamet   -> MTK XI-IPA
    (22, 4, 3, 2),   -- Dewi     -> BIND XI-IPA
    (23, 6, 3, 3),   -- Rina     -> BING XI-IPA
    (24, 3, 3, 4),   -- Budi     -> FIS XI-IPA
    (25, 8, 3, 5),   -- Maya     -> KIM XI-IPA
    (26, 9, 3, 6),   -- Asep     -> BIO XI-IPA
    (27, 10, 3, 7),  -- Bambang  -> SEJ XI-IPA
    (28, 12, 3, 8),  -- Fajar    -> INFO XI-IPA
    (29, 11, 3, 9),  -- Nurul    -> PPKN XI-IPA
    (30, 7, 3, 10),  -- Hendra   -> PJOK XI-IPA

    -- KELAS 4: XI-IPS (id 31 - 37)
    (31, 14, 4, 1),  -- Slamet   -> MTK XI-IPS
    (32, 15, 4, 2),  -- Dian     -> BIND XI-IPS
    (33, 13, 4, 3),  -- Ratna    -> BING XI-IPS
    (34, 10, 4, 7),  -- Bambang  -> SEJ XI-IPS
    (35, 12, 4, 8),  -- Fajar    -> INFO XI-IPS
    (36, 11, 4, 9),  -- Nurul    -> PPKN XI-IPS
    (37, 7, 4, 10),  -- Hendra   -> PJOK XI-IPS

    -- KELAS 5: XII-IPA (id 38 - 47)
    (38, 2, 5, 1),   -- Siti     -> MTK XII-IPA
    (39, 4, 5, 2),   -- Dewi     -> BIND XII-IPA
    (40, 6, 5, 3),   -- Rina     -> BING XII-IPA
    (41, 3, 5, 4),   -- Budi     -> FIS XII-IPA
    (42, 8, 5, 5),   -- Maya     -> KIM XII-IPA
    (43, 9, 5, 6),   -- Asep     -> BIO XII-IPA
    (44, 10, 5, 7),  -- Bambang  -> SEJ XII-IPA
    (45, 5, 5, 8),   -- Agus     -> INFO XII-IPA
    (46, 11, 5, 9),  -- Nurul    -> PPKN XII-IPA
    (47, 7, 5, 10),  -- Hendra   -> PJOK XII-IPA

    -- KELAS 6: XII-IPS (id 48 - 54)
    (48, 14, 6, 1),  -- Slamet   -> MTK XII-IPS
    (49, 15, 6, 2),  -- Dian     -> BIND XII-IPS
    (50, 13, 6, 3),  -- Ratna    -> BING XII-IPS
    (51, 10, 6, 7),  -- Bambang  -> SEJ XII-IPS
    (52, 12, 6, 8),  -- Fajar    -> INFO XII-IPS
    (53, 11, 6, 9),  -- Nurul    -> PPKN XII-IPS
    (54, 7, 6, 10);  -- Hendra   -> PJOK XII-IPS

-- ========================
-- JADWAL PELAJARAN MINGGUAN
-- ========================
INSERT INTO `jadwal` (`pengampu_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`) VALUES
    -- SENIN
    (1,  'senin', '07:00', '08:30', 'R. Teori X-1'),
    (2,  'senin', '08:30', '10:00', 'R. Teori X-1'),
    (3,  'senin', '10:15', '11:45', 'R. Teori X-1'),
    (11, 'senin', '07:00', '08:30', 'R. Teori X-2'),
    (12, 'senin', '08:30', '10:00', 'R. Teori X-2'),
    (21, 'senin', '07:00', '08:30', 'R. Teori XI-IPA'),
    (22, 'senin', '08:30', '10:00', 'R. Teori XI-IPA'),
    (31, 'senin', '10:15', '11:45', 'R. Teori XI-IPS'),
    (38, 'senin', '07:00', '08:30', 'R. Teori XII-IPA'),
    (41, 'senin', '08:30', '10:00', 'Lab Fisika'),

    -- SELASA
    (4,  'selasa', '07:00', '08:30', 'Lab Fisika'),
    (8,  'selasa', '08:30', '10:00', 'Lab Komputer 1'),
    (14, 'selasa', '07:00', '08:30', 'Lab Fisika 2'),
    (18, 'selasa', '10:15', '11:45', 'Lab Komputer 1'),
    (24, 'selasa', '07:00', '08:30', 'Lab Fisika'),
    (28, 'selasa', '08:30', '10:00', 'Lab Komputer 2'),
    (42, 'selasa', '07:00', '08:30', 'Lab Kimia'),
    (45, 'selasa', '08:30', '10:00', 'Lab Komputer 1'),

    -- RABU
    (5,  'rabu', '07:00', '08:30', 'Lab Kimia'),
    (6,  'rabu', '08:30', '10:00', 'Lab Biologi'),
    (10, 'rabu', '10:15', '11:45', 'Lapangan Olahraga'),
    (15, 'rabu', '07:00', '08:30', 'Lab Kimia'),
    (25, 'rabu', '08:30', '10:00', 'Lab Kimia'),
    (26, 'rabu', '10:15', '11:45', 'Lab Biologi'),
    (39, 'rabu', '07:00', '08:30', 'R. Teori XII-IPA'),
    (40, 'rabu', '08:30', '10:00', 'R. Teori XII-IPA'),

    -- KAMIS
    (7,  'kamis', '07:00', '08:30', 'R. Teori X-1'),
    (9,  'kamis', '08:30', '10:00', 'R. Teori X-1'),
    (17, 'kamis', '07:00', '08:30', 'R. Teori X-2'),
    (27, 'kamis', '07:00', '08:30', 'R. Teori XI-IPA'),
    (29, 'kamis', '08:30', '10:00', 'R. Teori XI-IPA'),
    (34, 'kamis', '07:00', '08:30', 'R. Teori XI-IPS'),
    (44, 'kamis', '08:30', '10:00', 'R. Teori XII-IPA'),
    (51, 'kamis', '10:15', '11:45', 'R. Teori XII-IPS'),

    -- JUMAT
    (1,  'jumat', '07:00', '08:30', 'R. Teori X-1'),
    (11, 'jumat', '07:00', '08:30', 'R. Teori X-2'),
    (23, 'jumat', '07:00', '08:30', 'R. Teori XI-IPA'),
    (30, 'jumat', '07:00', '08:30', 'Lapangan Olahraga'),
    (47, 'jumat', '07:00', '08:30', 'Lapangan Olahraga'),
    (54, 'jumat', '08:30', '10:00', 'Lapangan Olahraga');

-- =====================================================================
-- PRESENSI / ABSENSI LENGKAP SISWA (Divalidasi oleh Guru)
-- Siswa tiap kelas dicatat secara berkala pada jadwal pertemuan
-- Status: hadir, izin, sakit, alpha beserta catatan keterangan validasi
-- =====================================================================

-- -------------------------------------------------------------
-- PENGAMPU 1: MTK X-1 (Guru: Siti Rahmawati) — Siswa 101 - 110
-- -------------------------------------------------------------
-- Pertemuan 1: 2026-09-08
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (1, 101, '2026-09-08', 'hadir', NULL), (1, 102, '2026-09-08', 'hadir', NULL),
    (1, 103, '2026-09-08', 'hadir', NULL), (1, 104, '2026-09-08', 'hadir', NULL),
    (1, 105, '2026-09-08', 'hadir', NULL), (1, 106, '2026-09-08', 'hadir', NULL),
    (1, 107, '2026-09-08', 'hadir', NULL), (1, 108, '2026-09-08', 'hadir', NULL),
    (1, 109, '2026-09-08', 'hadir', NULL), (1, 110, '2026-09-08', 'hadir', NULL);

-- Pertemuan 2: 2026-09-15
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (1, 101, '2026-09-15', 'hadir', NULL), (1, 102, '2026-09-15', 'hadir', NULL),
    (1, 103, '2026-09-15', 'sakit', 'Demam tinggi, surat dokter terlampir'),
    (1, 104, '2026-09-15', 'hadir', NULL), (1, 105, '2026-09-15', 'hadir', NULL),
    (1, 106, '2026-09-15', 'hadir', NULL), (1, 107, '2026-09-15', 'hadir', NULL),
    (1, 108, '2026-09-15', 'izin',  'Mengikuti seleksi olimpiade sains kab. Garut'),
    (1, 109, '2026-09-15', 'hadir', NULL), (1, 110, '2026-09-15', 'hadir', NULL);

-- Pertemuan 3: 2026-09-22
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (1, 101, '2026-09-22', 'hadir', NULL), (1, 102, '2026-09-22', 'hadir', NULL),
    (1, 103, '2026-09-22', 'hadir', NULL), (1, 104, '2026-09-22', 'hadir', NULL),
    (1, 105, '2026-09-22', 'hadir', NULL), (1, 106, '2026-09-22', 'hadir', NULL),
    (1, 107, '2026-09-22', 'alpha', 'Tanpa keterangan konfirmasi orang tua'),
    (1, 108, '2026-09-22', 'hadir', NULL), (1, 109, '2026-09-22', 'hadir', NULL),
    (1, 110, '2026-09-22', 'hadir', NULL);

-- Pertemuan 4: 2026-10-06
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (1, 101, '2026-10-06', 'hadir', NULL), (1, 102, '2026-10-06', 'hadir', NULL),
    (1, 103, '2026-10-06', 'hadir', NULL), (1, 104, '2026-10-06', 'sakit', 'Flu & istirahat di rumah'),
    (1, 105, '2026-10-06', 'hadir', NULL), (1, 106, '2026-10-06', 'hadir', NULL),
    (1, 107, '2026-10-06', 'hadir', NULL), (1, 108, '2026-10-06', 'hadir', NULL),
    (1, 109, '2026-10-06', 'hadir', NULL), (1, 110, '2026-10-06', 'izin',  'Acara keluarga keluar kota');

-- Pertemuan 5: 2026-10-13
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (1, 101, '2026-10-13', 'hadir', NULL), (1, 102, '2026-10-13', 'hadir', NULL),
    (1, 103, '2026-10-13', 'hadir', NULL), (1, 104, '2026-10-13', 'hadir', NULL),
    (1, 105, '2026-10-13', 'hadir', NULL), (1, 106, '2026-10-13', 'hadir', NULL),
    (1, 107, '2026-10-13', 'hadir', NULL), (1, 108, '2026-10-13', 'hadir', NULL),
    (1, 109, '2026-10-13', 'hadir', NULL), (1, 110, '2026-10-13', 'hadir', NULL);

-- Pertemuan 6: 2026-10-20
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (1, 101, '2026-10-20', 'hadir', NULL), (1, 102, '2026-10-20', 'hadir', NULL),
    (1, 103, '2026-10-20', 'hadir', NULL), (1, 104, '2026-10-20', 'hadir', NULL),
    (1, 105, '2026-10-20', 'hadir', NULL), (1, 106, '2026-10-20', 'sakit', 'Pusing dan demam'),
    (1, 107, '2026-10-20', 'hadir', NULL), (1, 108, '2026-10-20', 'hadir', NULL),
    (1, 109, '2026-10-20', 'izin',  'Pengurusan berkas KTP'),
    (1, 110, '2026-10-20', 'hadir', NULL);

-- -------------------------------------------------------------
-- PENGAMPU 4: FISIKA X-1 (Guru: Budi Hartono) — Siswa 101 - 110
-- -------------------------------------------------------------
-- Pertemuan 1: 2026-09-09
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (4, 101, '2026-09-09', 'hadir', NULL), (4, 102, '2026-09-09', 'hadir', NULL),
    (4, 103, '2026-09-09', 'hadir', NULL), (4, 104, '2026-09-09', 'hadir', NULL),
    (4, 105, '2026-09-09', 'hadir', NULL), (4, 106, '2026-09-09', 'hadir', NULL),
    (4, 107, '2026-09-09', 'hadir', NULL), (4, 108, '2026-09-09', 'hadir', NULL),
    (4, 109, '2026-09-09', 'hadir', NULL), (4, 110, '2026-09-09', 'hadir', NULL);

-- Pertemuan 2: 2026-09-16
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (4, 101, '2026-09-16', 'hadir', NULL), (4, 102, '2026-09-16', 'hadir', NULL),
    (4, 103, '2026-09-16', 'hadir', NULL), (4, 104, '2026-09-16', 'sakit', 'Surat dokter'),
    (4, 105, '2026-09-16', 'hadir', NULL), (4, 106, '2026-09-16', 'hadir', NULL),
    (4, 107, '2026-09-16', 'hadir', NULL), (4, 108, '2026-09-16', 'hadir', NULL),
    (4, 109, '2026-09-16', 'hadir', NULL), (4, 110, '2026-09-16', 'hadir', NULL);

-- Pertemuan 3: 2026-10-07
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (4, 101, '2026-10-07', 'hadir', NULL), (4, 102, '2026-10-07', 'hadir', NULL),
    (4, 103, '2026-10-07', 'hadir', NULL), (4, 104, '2026-10-07', 'hadir', NULL),
    (4, 105, '2026-10-07', 'hadir', NULL), (4, 106, '2026-10-07', 'hadir', NULL),
    (4, 107, '2026-10-07', 'izin',  'Dispensasi lomba PMR'),
    (4, 108, '2026-10-07', 'hadir', NULL), (4, 109, '2026-10-07', 'hadir', NULL),
    (4, 110, '2026-10-07', 'hadir', NULL);

-- Pertemuan 4: 2026-10-14
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (4, 101, '2026-10-14', 'hadir', NULL), (4, 102, '2026-10-14', 'hadir', NULL),
    (4, 103, '2026-10-14', 'hadir', NULL), (4, 104, '2026-10-14', 'hadir', NULL),
    (4, 105, '2026-10-14', 'hadir', NULL), (4, 106, '2026-10-14', 'hadir', NULL),
    (4, 107, '2026-10-14', 'hadir', NULL), (4, 108, '2026-10-14', 'hadir', NULL),
    (4, 109, '2026-10-14', 'sakit', 'Flu ringan'),
    (4, 110, '2026-10-14', 'hadir', NULL);

-- Pertemuan 5: 2026-10-21
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (4, 101, '2026-10-21', 'hadir', NULL), (4, 102, '2026-10-21', 'hadir', NULL),
    (4, 103, '2026-10-21', 'hadir', NULL), (4, 104, '2026-10-21', 'hadir', NULL),
    (4, 105, '2026-10-21', 'hadir', NULL), (4, 106, '2026-10-21', 'hadir', NULL),
    (4, 107, '2026-10-21', 'hadir', NULL), (4, 108, '2026-10-21', 'hadir', NULL),
    (4, 109, '2026-10-21', 'hadir', NULL), (4, 110, '2026-10-21', 'hadir', NULL);

-- -------------------------------------------------------------
-- PENGAMPU 2: B. INDONESIA X-1 (Guru: Dewi Lestari) — Siswa 101 - 110
-- -------------------------------------------------------------
-- Pertemuan 1: 2026-09-07
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (2, 101, '2026-09-07', 'hadir', NULL), (2, 102, '2026-09-07', 'hadir', NULL),
    (2, 103, '2026-09-07', 'hadir', NULL), (2, 104, '2026-09-07', 'hadir', NULL),
    (2, 105, '2026-09-07', 'hadir', NULL), (2, 106, '2026-09-07', 'hadir', NULL),
    (2, 107, '2026-09-07', 'hadir', NULL), (2, 108, '2026-09-07', 'hadir', NULL),
    (2, 109, '2026-09-07', 'hadir', NULL), (2, 110, '2026-09-07', 'hadir', NULL);

-- Pertemuan 2: 2026-09-14
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (2, 101, '2026-09-14', 'hadir', NULL), (2, 102, '2026-09-14', 'hadir', NULL),
    (2, 103, '2026-09-14', 'hadir', NULL), (2, 104, '2026-09-14', 'hadir', NULL),
    (2, 105, '2026-09-14', 'izin',  'Izin urusan paspor'),
    (2, 106, '2026-09-14', 'hadir', NULL), (2, 107, '2026-09-14', 'hadir', NULL),
    (2, 108, '2026-09-14', 'hadir', NULL), (2, 109, '2026-09-14', 'hadir', NULL),
    (2, 110, '2026-09-14', 'hadir', NULL);

-- Pertemuan 3: 2026-10-05
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (2, 101, '2026-10-05', 'hadir', NULL), (2, 102, '2026-10-05', 'hadir', NULL),
    (2, 103, '2026-10-05', 'hadir', NULL), (2, 104, '2026-10-05', 'hadir', NULL),
    (2, 105, '2026-10-05', 'hadir', NULL), (2, 106, '2026-10-05', 'hadir', NULL),
    (2, 107, '2026-10-05', 'hadir', NULL), (2, 108, '2026-10-05', 'hadir', NULL),
    (2, 109, '2026-10-05', 'hadir', NULL), (2, 110, '2026-10-05', 'hadir', NULL);

-- Pertemuan 4: 2026-10-19
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (2, 101, '2026-10-19', 'hadir', NULL), (2, 102, '2026-10-19', 'hadir', NULL),
    (2, 103, '2026-10-19', 'hadir', NULL), (2, 104, '2026-10-19', 'hadir', NULL),
    (2, 105, '2026-10-19', 'hadir', NULL), (2, 106, '2026-10-19', 'hadir', NULL),
    (2, 107, '2026-10-19', 'sakit', 'Demam'),
    (2, 108, '2026-10-19', 'hadir', NULL), (2, 109, '2026-10-19', 'hadir', NULL),
    (2, 110, '2026-10-19', 'hadir', NULL);

-- -------------------------------------------------------------
-- PENGAMPU 8: INFORMATIKA X-1 (Guru: Agus Setiawan) — Siswa 101 - 110
-- -------------------------------------------------------------
-- Pertemuan 1: 2026-09-11
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (8, 101, '2026-09-11', 'hadir', NULL), (8, 102, '2026-09-11', 'hadir', NULL),
    (8, 103, '2026-09-11', 'hadir', NULL), (8, 104, '2026-09-11', 'hadir', NULL),
    (8, 105, '2026-09-11', 'hadir', NULL), (8, 106, '2026-09-11', 'hadir', NULL),
    (8, 107, '2026-09-11', 'hadir', NULL), (8, 108, '2026-09-11', 'hadir', NULL),
    (8, 109, '2026-09-11', 'hadir', NULL), (8, 110, '2026-09-11', 'hadir', NULL);

-- Pertemuan 2: 2026-09-18
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (8, 101, '2026-09-18', 'hadir', NULL), (8, 102, '2026-09-18', 'hadir', NULL),
    (8, 103, '2026-09-18', 'hadir', NULL), (8, 104, '2026-09-18', 'hadir', NULL),
    (8, 105, '2026-09-18', 'hadir', NULL), (8, 106, '2026-09-18', 'hadir', NULL),
    (8, 107, '2026-09-18', 'hadir', NULL), (8, 108, '2026-09-18', 'hadir', NULL),
    (8, 109, '2026-09-18', 'hadir', NULL), (8, 110, '2026-09-18', 'hadir', NULL);

-- Pertemuan 3: 2026-10-09
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (8, 101, '2026-10-09', 'hadir', NULL), (8, 102, '2026-10-09', 'hadir', NULL),
    (8, 103, '2026-10-09', 'hadir', NULL), (8, 104, '2026-10-09', 'hadir', NULL),
    (8, 105, '2026-10-09', 'hadir', NULL), (8, 106, '2026-10-09', 'hadir', NULL),
    (8, 107, '2026-10-09', 'hadir', NULL), (8, 108, '2026-10-09', 'hadir', NULL),
    (8, 109, '2026-10-09', 'hadir', NULL), (8, 110, '2026-10-09', 'hadir', NULL);

-- Pertemuan 4: 2026-10-16
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (8, 101, '2026-10-16', 'hadir', NULL), (8, 102, '2026-10-16', 'hadir', NULL),
    (8, 103, '2026-10-16', 'hadir', NULL), (8, 104, '2026-10-16', 'hadir', NULL),
    (8, 105, '2026-10-16', 'hadir', NULL), (8, 106, '2026-10-16', 'hadir', NULL),
    (8, 107, '2026-10-16', 'hadir', NULL), (8, 108, '2026-10-16', 'hadir', NULL),
    (8, 109, '2026-10-16', 'hadir', NULL), (8, 110, '2026-10-16', 'hadir', NULL);

-- -------------------------------------------------------------
-- PENGAMPU 11: MTK X-2 (Guru: Siti Rahmawati) — Siswa 111 - 120
-- -------------------------------------------------------------
-- Pertemuan 1: 2026-09-08
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (11, 111, '2026-09-08', 'hadir', NULL), (11, 112, '2026-09-08', 'hadir', NULL),
    (11, 113, '2026-09-08', 'hadir', NULL), (11, 114, '2026-09-08', 'hadir', NULL),
    (11, 115, '2026-09-08', 'hadir', NULL), (11, 116, '2026-09-08', 'hadir', NULL),
    (11, 117, '2026-09-08', 'hadir', NULL), (11, 118, '2026-09-08', 'hadir', NULL),
    (11, 119, '2026-09-08', 'hadir', NULL), (11, 120, '2026-09-08', 'hadir', NULL);

-- Pertemuan 2: 2026-09-15
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (11, 111, '2026-09-15', 'hadir', NULL), (11, 112, '2026-09-15', 'hadir', NULL),
    (11, 113, '2026-09-15', 'sakit', 'Sakit flu'),
    (11, 114, '2026-09-15', 'hadir', NULL), (11, 115, '2026-09-15', 'hadir', NULL),
    (11, 116, '2026-09-15', 'hadir', NULL), (11, 117, '2026-09-15', 'hadir', NULL),
    (11, 118, '2026-09-15', 'izin',  'Acara keluarga'),
    (11, 119, '2026-09-15', 'hadir', NULL), (11, 120, '2026-09-15', 'hadir', NULL);

-- Pertemuan 3: 2026-10-06
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (11, 111, '2026-10-06', 'hadir', NULL), (11, 112, '2026-10-06', 'hadir', NULL),
    (11, 113, '2026-10-06', 'hadir', NULL), (11, 114, '2026-10-06', 'hadir', NULL),
    (11, 115, '2026-10-06', 'hadir', NULL), (11, 116, '2026-10-06', 'hadir', NULL),
    (11, 117, '2026-10-06', 'hadir', NULL), (11, 118, '2026-10-06', 'hadir', NULL),
    (11, 119, '2026-10-06', 'hadir', NULL), (11, 120, '2026-10-06', 'hadir', NULL);

-- Pertemuan 4: 2026-10-20
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (11, 111, '2026-10-20', 'hadir', NULL), (11, 112, '2026-10-20', 'hadir', NULL),
    (11, 113, '2026-10-20', 'hadir', NULL), (11, 114, '2026-10-20', 'hadir', NULL),
    (11, 115, '2026-10-20', 'sakit', 'Demam'),
    (11, 116, '2026-10-20', 'hadir', NULL), (11, 117, '2026-10-20', 'hadir', NULL),
    (11, 118, '2026-10-20', 'hadir', NULL), (11, 119, '2026-10-20', 'hadir', NULL),
    (11, 120, '2026-10-20', 'hadir', NULL);

-- -------------------------------------------------------------
-- PENGAMPU 21: MTK XI-IPA (Guru: Slamet Wahyudi) — Siswa 121 - 130
-- -------------------------------------------------------------
-- Pertemuan 1: 2026-09-08
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (21, 121, '2026-09-08', 'hadir', NULL), (21, 122, '2026-09-08', 'hadir', NULL),
    (21, 123, '2026-09-08', 'hadir', NULL), (21, 124, '2026-09-08', 'hadir', NULL),
    (21, 125, '2026-09-08', 'hadir', NULL), (21, 126, '2026-09-08', 'hadir', NULL),
    (21, 127, '2026-09-08', 'hadir', NULL), (21, 128, '2026-09-08', 'hadir', NULL),
    (21, 129, '2026-09-08', 'hadir', NULL), (21, 130, '2026-09-08', 'hadir', NULL);

-- Pertemuan 2: 2026-09-15
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (21, 121, '2026-09-15', 'hadir', NULL), (21, 122, '2026-09-15', 'hadir', NULL),
    (21, 123, '2026-09-15', 'hadir', NULL), (21, 124, '2026-09-15', 'sakit', 'Demam'),
    (21, 125, '2026-09-15', 'hadir', NULL), (21, 126, '2026-09-15', 'hadir', NULL),
    (21, 127, '2026-09-15', 'izin',  'Lomba debat bahasa Inggris'),
    (21, 128, '2026-09-15', 'hadir', NULL), (21, 129, '2026-09-15', 'hadir', NULL),
    (21, 130, '2026-09-15', 'hadir', NULL);

-- Pertemuan 3: 2026-10-06
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (21, 121, '2026-10-06', 'hadir', NULL), (21, 122, '2026-10-06', 'hadir', NULL),
    (21, 123, '2026-10-06', 'hadir', NULL), (21, 124, '2026-10-06', 'hadir', NULL),
    (21, 125, '2026-10-06', 'hadir', NULL), (21, 126, '2026-10-06', 'hadir', NULL),
    (21, 127, '2026-10-06', 'hadir', NULL), (21, 128, '2026-10-06', 'hadir', NULL),
    (21, 129, '2026-10-06', 'hadir', NULL), (21, 130, '2026-10-06', 'hadir', NULL);

-- Pertemuan 4: 2026-10-20
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (21, 121, '2026-10-20', 'hadir', NULL), (21, 122, '2026-10-20', 'hadir', NULL),
    (21, 123, '2026-10-20', 'hadir', NULL), (21, 124, '2026-10-20', 'hadir', NULL),
    (21, 125, '2026-10-20', 'hadir', NULL), (21, 126, '2026-10-20', 'sakit', 'Sakit gigi'),
    (21, 127, '2026-10-20', 'hadir', NULL), (21, 128, '2026-10-20', 'hadir', NULL),
    (21, 129, '2026-10-20', 'hadir', NULL), (21, 130, '2026-10-20', 'hadir', NULL);

-- -------------------------------------------------------------
-- PENGAMPU 31: MTK XI-IPS (Guru: Slamet Wahyudi) — Siswa 131 - 140
-- -------------------------------------------------------------
-- Pertemuan 1: 2026-09-08
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (31, 131, '2026-09-08', 'hadir', NULL), (31, 132, '2026-09-08', 'hadir', NULL),
    (31, 133, '2026-09-08', 'hadir', NULL), (31, 134, '2026-09-08', 'hadir', NULL),
    (31, 135, '2026-09-08', 'hadir', NULL), (31, 136, '2026-09-08', 'hadir', NULL),
    (31, 137, '2026-09-08', 'hadir', NULL), (31, 138, '2026-09-08', 'hadir', NULL),
    (31, 139, '2026-09-08', 'hadir', NULL), (31, 140, '2026-09-08', 'hadir', NULL);

-- Pertemuan 2: 2026-10-06
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (31, 131, '2026-10-06', 'hadir', NULL), (31, 132, '2026-10-06', 'hadir', NULL),
    (31, 133, '2026-10-06', 'hadir', NULL), (31, 134, '2026-10-06', 'sakit', 'Demam'),
    (31, 135, '2026-10-06', 'hadir', NULL), (31, 136, '2026-10-06', 'hadir', NULL),
    (31, 137, '2026-10-06', 'hadir', NULL), (31, 138, '2026-10-06', 'hadir', NULL),
    (31, 139, '2026-10-06', 'hadir', NULL), (31, 140, '2026-10-06', 'hadir', NULL);

-- -------------------------------------------------------------
-- PENGAMPU 38: MTK XII-IPA (Guru: Siti Rahmawati) — Siswa 141 - 150
-- -------------------------------------------------------------
-- Pertemuan 1: 2026-09-08
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (38, 141, '2026-09-08', 'hadir', NULL), (38, 142, '2026-09-08', 'hadir', NULL),
    (38, 143, '2026-09-08', 'hadir', NULL), (38, 144, '2026-09-08', 'hadir', NULL),
    (38, 145, '2026-09-08', 'hadir', NULL), (38, 146, '2026-09-08', 'hadir', NULL),
    (38, 147, '2026-09-08', 'hadir', NULL), (38, 148, '2026-09-08', 'hadir', NULL),
    (38, 149, '2026-09-08', 'hadir', NULL), (38, 150, '2026-09-08', 'hadir', NULL);

-- Pertemuan 2: 2026-10-06
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (38, 141, '2026-10-06', 'hadir', NULL), (38, 142, '2026-10-06', 'hadir', NULL),
    (38, 143, '2026-10-06', 'hadir', NULL), (38, 144, '2026-10-06', 'hadir', NULL),
    (38, 145, '2026-10-06', 'izin',  'Bimbingan belajar UTBK'),
    (38, 146, '2026-10-06', 'hadir', NULL), (38, 147, '2026-10-06', 'hadir', NULL),
    (38, 148, '2026-10-06', 'hadir', NULL), (38, 149, '2026-10-06', 'hadir', NULL),
    (38, 150, '2026-10-06', 'hadir', NULL);

-- -------------------------------------------------------------
-- PENGAMPU 48: MTK XII-IPS (Guru: Slamet Wahyudi) — Siswa 151 - 160
-- -------------------------------------------------------------
-- Pertemuan 1: 2026-09-08
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (48, 151, '2026-09-08', 'hadir', NULL), (48, 152, '2026-09-08', 'hadir', NULL),
    (48, 153, '2026-09-08', 'hadir', NULL), (48, 154, '2026-09-08', 'hadir', NULL),
    (48, 155, '2026-09-08', 'hadir', NULL), (48, 156, '2026-09-08', 'hadir', NULL),
    (48, 157, '2026-09-08', 'hadir', NULL), (48, 158, '2026-09-08', 'hadir', NULL),
    (48, 159, '2026-09-08', 'hadir', NULL), (48, 160, '2026-09-08', 'hadir', NULL);

-- Pertemuan 2: 2026-10-06
INSERT INTO `absensi` (`pengampu_id`, `siswa_id`, `tanggal`, `status`, `keterangan`) VALUES
    (48, 151, '2026-10-06', 'hadir', NULL), (48, 152, '2026-10-06', 'hadir', NULL),
    (48, 153, '2026-10-06', 'hadir', NULL), (48, 154, '2026-10-06', 'sakit', 'Demam berdarah'),
    (48, 155, '2026-10-06', 'hadir', NULL), (48, 156, '2026-10-06', 'hadir', NULL),
    (48, 157, '2026-10-06', 'hadir', NULL), (48, 158, '2026-10-06', 'hadir', NULL),
    (48, 159, '2026-10-06', 'hadir', NULL), (48, 160, '2026-10-06', 'hadir', NULL);

-- =====================================================================
-- KUIS + SOAL + HASIL KUIS + JAWABAN KUIS
-- 8 Kuis interaktif lintas kelas dan mata pelajaran
-- =====================================================================

INSERT INTO `kuis` (`id`, `pengampu_id`, `judul`, `durasi_menit`, `mulai`, `selesai`, `created_at`) VALUES
    -- Kuis 1: MTK X-1 (Pengampu 1)
    (1, 1, 'Kuis Bab 1: Eksponen dan Bentuk Akar', 30, '2026-10-01 07:00:00', '2026-12-31 23:59:59', '2026-09-28 08:00:00'),
    -- Kuis 2: FIS X-1 (Pengampu 4)
    (2, 4, 'Kuis Bab 2: Hukum Newton & Dinamika Gerak', 25, '2026-10-05 07:00:00', '2026-12-31 23:59:59', '2026-10-03 08:00:00'),
    -- Kuis 3: INFO X-1 (Pengampu 8)
    (3, 8, 'Kuis Bab 1: Dasar Pemrograman & Algoritma', 30, '2026-10-08 07:00:00', '2026-12-31 23:59:59', '2026-10-06 09:00:00'),
    -- Kuis 4: BIND X-1 (Pengampu 2)
    (4, 2, 'Kuis Bab 1: Teks Laporan Hasil Observasi', 20, '2026-10-10 07:00:00', '2026-12-31 23:59:59', '2026-10-08 08:30:00'),
    -- Kuis 5: INFO X-2 (Pengampu 18)
    (5, 18, 'Kuis Logika Komputasional & Flowchart', 30, '2026-10-08 07:00:00', '2026-12-31 23:59:59', '2026-10-06 09:00:00'),
    -- Kuis 6: MTK XI-IPA (Pengampu 21)
    (6, 21, 'Kuis Bab 1: Trigonometri Analitik & Identitas', 35, '2026-10-02 07:00:00', '2026-12-31 23:59:59', '2026-09-30 08:00:00'),
    -- Kuis 7: FIS XI-IPA (Pengampu 24)
    (7, 24, 'Kuis Bab 2: Dinamika Rotasi & Kesetimbangan Benda Tegar', 30, '2026-10-09 07:00:00', '2026-12-31 23:59:59', '2026-10-07 08:00:00'),
    -- Kuis 8: FIS XII-IPA (Pengampu 41)
    (8, 41, 'Kuis Gelombang Elektromagnetik & Relativitas Khusus', 40, '2026-10-11 07:00:00', '2026-12-31 23:59:59', '2026-10-09 08:00:00');

-- -------------------------------------------------------------
-- BUTIR SOAL PILIHAN GANDA (40 Soal = 5 Soal per Kuis)
-- -------------------------------------------------------------
INSERT INTO `soal` (`id`, `kuis_id`, `pertanyaan`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `opsi_e`, `kunci`) VALUES
    -- Soal Kuis 1 (MTK X-1)
    (1, 1, 'Hasil penyederhanaan dari operasi bilangan berpangkat 2³ × 2⁴ adalah ...', '128', '64', '256', '32', '512', 'a'),
    (2, 1, 'Berapakah nilai pasti dari 7⁰ + 3⁰ - 2⁰ ?', '0', '1', '2', '3', '-1', 'b'),
    (3, 1, 'Bentuk sederhana dari bentuk akar √72 adalah ...', '6√2', '2√6', '3√8', '12√2', '4√3', 'a'),
    (4, 1, 'Hasil dari pemangkatan berulang (3²)³ adalah ...', '729', '27', '243', '81', '2187', 'a'),
    (5, 1, 'Bentuk rasional dari pecahan 6 / √3 adalah ...', '2√3', '3√2', '6√3', '√18', '2√6', 'a'),

    -- Soal Kuis 2 (FIS X-1)
    (6, 2, 'Hukum Newton I dikenal juga dengan sebutan hukum kelembaman atau ...', 'Inersia', 'Gravitasi', 'Aksi-Reaksi', 'Kekekalan Energi', 'Momentum', 'a'),
    (7, 2, 'Persamaan matematis fundamental dari Hukum Newton II adalah ...', 'F = m × a', 'F = m × v', 'E = m × c²', 'W = F × s', 'P = F / A', 'a'),
    (8, 2, 'Sebuah balok bermassa 5 kg ditarik dengan gaya tetap 25 N di atas lantai licin. Percepatan balok adalah ...', '5 m/s²', '125 m/s²', '20 m/s²', '0.2 m/s²', '15 m/s²', 'a'),
    (9, 2, 'Fenomena meluncurnya roket ke angkasa karena dorongan semburan gas ke bawah merupakan bukti penerapan Hukum ...', 'Newton III', 'Newton I', 'Newton II', 'Pascal', 'Archimedes', 'a'),
    (10,2, 'Satuan Standar Internasional (SI) untuk besaran gaya adalah ...', 'Newton (N)', 'Joule (J)', 'Watt (W)', 'Pascal (Pa)', 'Kilogram (kg)', 'a'),

    -- Soal Kuis 3 (INFO X-1)
    (11, 3, 'Urutan langkah-langkah logis dan terstruktur untuk menyelesaikan suatu permasalahan pemrograman disebut ...', 'Algoritma', 'Variabel', 'Kompilator', 'Sintaksis', 'Pseudocode', 'a'),
    (12, 3, 'Tag HTML yang digunakan khusus untuk membuat teks tebal secara semantik penting adalah ...', '<strong>', '<bold>', '<bb>', '<thick>', '<i>', 'a'),
    (13, 3, 'Dalam Cascading Style Sheets (CSS), properti yang mengatur jarak ruang di dalam batas elemen (inner space) adalah ...', 'padding', 'margin', 'border', 'spacing', 'gap', 'a'),
    (14, 3, 'Simbol flowchart yang digunakan untuk menyatakan proses pengambilan keputusan (Decision/Percabangan) berbentuk ...', 'Belah Ketupat', 'Persegi Panjang', 'Lingkaran', 'Jajar Genjang', 'Oval', 'a'),
    (15, 3, 'Tipe data pemrograman yang hanya bernilai benar (true) atau salah (false) adalah ...', 'Boolean', 'Integer', 'Float', 'String', 'Array', 'a'),

    -- Soal Kuis 4 (BIND X-1)
    (16, 4, 'Teks Laporan Hasil Observasi (LHO) harus disusun berdasarkan ...', 'Fakta nyata hasil pengamatan', 'Opini pribadi penulis', 'Imajinasi dan cerita fiksi', 'Desas-desus masyarakat', 'Perasaan subjektif', 'a'),
    (17, 4, 'Struktur pertama pada teks laporan hasil observasi adalah ...', 'Pernyataan umum / Definisi umum', 'Deskripsi bagian', 'Deskripsi manfaat', 'Simpulan', 'Koda', 'a'),
    (18, 4, 'Kalimat yang menggunakan verba relasional untuk menjelaskan suatu objek disebut kalimat ...', 'Definisi', 'Deskripsi', 'Imperatif', 'Persuasif', 'Tanya', 'a'),
    (19, 4, 'Ciri kebahasaan teks laporan hasil observasi adalah menggunakan istilah teknis atau ...', 'Kata ilmiah (biologi/sains)', 'Bahasa gaul', 'Majas hiperbola', 'Rima puisi', 'Ungkapan pepatah', 'a'),
    (20, 4, 'Bagian yang menjelaskan kegunaan atau fungsi dari objek yang diobservasi adalah ...', 'Deskripsi manfaat', 'Pernyataan umum', 'Deskripsi bagian', 'Orientasi', 'Reaksi', 'a'),

    -- Soal Kuis 5 (INFO X-2)
    (21, 5, 'Pondasi Berpikir Komputasional terdiri atas 4 pilar, salah satunya adalah pemecahan masalah besar menjadi kecil, yaitu ...', 'Dekomposisi', 'Abstraksi', 'Pengenalan Pola', 'Algoritma', 'Enkapsulasi', 'a'),
    (22, 5, 'Menghilangkan detail informasi yang tidak penting untuk fokus pada inti permasalahan disebut ...', 'Abstraksi', 'Dekomposisi', 'Generalisasi', 'Iterasi', 'Debugging', 'a'),
    (23, 5, 'Struktur data linier yang menerapkan prinsip LIFO (Last In First Out) adalah ...', 'Stack (Tumpukan)', 'Queue (Antrean)', 'Tree (Pohon)', 'Graph', 'Linked List', 'a'),
    (24, 5, 'Struktur data yang menerapkan prinsip FIFO (First In First Out) seperti pada antrean kasir adalah ...', 'Queue (Antrean)', 'Stack', 'Array', 'Binary Tree', 'Matrix', 'a'),
    (25, 5, 'Kombinasi gerbang logika yang menghasilkan output TRUE hanya jika kedua input bernilai TRUE adalah gerbang ...', 'AND', 'OR', 'NOT', 'XOR', 'NAND', 'a'),

    -- Soal Kuis 6 (MTK XI-IPA)
    (26, 6, 'Nilai perbandingan trigonometri dari sin 30° adalah ...', '1/2', '1/2 √3', '1/2 √2', '1', '0', 'a'),
    (27, 6, 'Identitas trigonometri dasar yang berlaku untuk setiap sudut x adalah ...', 'sin²x + cos²x = 1', 'sin²x - cos²x = 1', 'tan²x + 1 = sin²x', 'cos 2x = 2 cos x', 'sin 2x = sin x cos x', 'a'),
    (28, 6, 'Nilai dari cos 60° sama besarnya dengan nilai ...', 'sin 30°', 'sin 60°', 'cos 30°', 'tan 45°', 'cos 90°', 'a'),
    (29, 6, 'Rumus untuk sin(A + B) adalah ...', 'sin A cos B + cos A sin B', 'sin A sin B + cos A cos B', 'cos A cos B - sin A sin B', 'tan A + tan B', 'sin A - sin B', 'a'),
    (30, 6, 'Jika tan x = 1 dan x berada pada kuadran I, maka besar sudut x adalah ...', '45°', '30°', '60°', '90°', '120°', 'a'),

    -- Soal Kuis 7 (FIS XI-IPA)
    (31, 7, 'Besaran yang menyebabkan benda berotasi pada porosnya disebut ...', 'Momen Gaya (Torsi)', 'Gaya Gesek', 'Massa Jenis', 'Momentum Sudut', 'Percepatan Linier', 'a'),
    (32, 7, 'Rumus besarnya torsi (τ) jika gaya F tegak lurus terhadap lengan r adalah ...', 'τ = F × r', 'τ = F / r', 'τ = m × a', 'τ = 1/2 m v²', 'τ = I × ω²', 'a'),
    (33, 7, 'Ukuran kelembaman benda yang berotasi pada sumbu putarnya disebut ...', 'Momen Inersia', 'Momentum', 'Kecepatan Sudut', 'Tegangan Permukaan', 'Gaya Sentripetal', 'a'),
    (34, 7, 'Satuan SI dari momen inersia (I) adalah ...', 'kg·m²', 'N·m', 'kg·m/s', 'Joule', 'Watt·s', 'a'),
    (35, 7, 'Syarat kesetimbangan statis benda tegar secara lengkap adalah ...', 'ΣF = 0 dan Στ = 0', 'Hanya ΣF = 0', 'Hanya Στ = 0', 'ΣF > 0', 'a = g', 'a'),

    -- Soal Kuis 8 (FIS XII-IPA)
    (36, 8, 'Urutan spektrum gelombang elektromagnetik dari frekuensi terendah ke tertinggi adalah ...', 'Radio, Mikro, Inframerah, Tampak, UV, Sinar-X, Gamma', 'Gamma, Sinar-X, UV, Tampak, Inframerah, Radio', 'Tampak, UV, Sinar-X, Gamma, Mikro, Radio', 'Inframerah, Tampak, Radio, Sinar-X', 'Mikro, Radio, Gamma, Sinar-X', 'a'),
    (37, 8, 'Cahaya tampak dengan panjang gelombang terbesar adalah spektrum warna ...', 'Merah', 'Ungu', 'Hijau', 'Kuning', 'Biru', 'a'),
    (38, 8, 'Teori relativitas khusus dirumuskan pertama kali pada tahun 1905 oleh fisikawan terkenal ...', 'Albert Einstein', 'Isaac Newton', 'Max Planck', 'Niels Bohr', 'James Clerk Maxwell', 'a'),
    (39, 8, 'Persamaan kesetaraan massa dan energi yang sangat terkenal adalah ...', 'E = m × c²', 'E = h × f', 'E = 1/2 m v²', 'F = G m1 m2 / r²', 'λ = h / p', 'a'),
    (40, 8, 'Fenomena penambahan massa atau pemuluran waktu pada benda yang bergerak mendekati kecepatan cahaya disebut ...', 'Dilatasi Waktu', 'Kontraksi Massa', 'Efek Doppler', 'Dispersi Cahaya', 'Refraksi', 'a');

-- -------------------------------------------------------------
-- HASIL KUIS SISWA (Rekap Nilai Siswa)
-- -------------------------------------------------------------
INSERT INTO `hasil_kuis` (`id`, `kuis_id`, `siswa_id`, `nilai`, `mulai_at`, `selesai_at`) VALUES
    -- Kuis 1 (MTK X-1): 10 siswa X-1 lengkap
    (1,  1, 101, 100.00, '2026-10-02 07:05:00', '2026-10-02 07:22:00'),
    (2,  1, 102,  80.00, '2026-10-02 07:10:00', '2026-10-02 07:29:00'),
    (3,  1, 103,  60.00, '2026-10-02 07:08:00', '2026-10-02 07:31:00'),
    (4,  1, 104, 100.00, '2026-10-02 07:04:00', '2026-10-02 07:20:00'),
    (5,  1, 105, 100.00, '2026-10-02 07:02:00', '2026-10-02 07:19:00'),
    (6,  1, 106,  80.00, '2026-10-02 07:12:00', '2026-10-02 07:33:00'),
    (7,  1, 107,  80.00, '2026-10-02 07:15:00', '2026-10-02 07:36:00'),
    (8,  1, 108, 100.00, '2026-10-02 07:03:00', '2026-10-02 07:18:00'),
    (9,  1, 109,  60.00, '2026-10-02 07:20:00', '2026-10-02 07:44:00'),
    (10, 1, 110,  80.00, '2026-10-02 07:14:00', '2026-10-02 07:35:00'),

    -- Kuis 2 (FIS X-1): 8 siswa
    (11, 2, 101,  80.00, '2026-10-06 08:05:00', '2026-10-06 08:24:00'),
    (12, 2, 102,  80.00, '2026-10-06 08:10:00', '2026-10-06 08:28:00'),
    (13, 2, 104, 100.00, '2026-10-06 08:03:00', '2026-10-06 08:21:00'),
    (14, 2, 105,  80.00, '2026-10-06 08:06:00', '2026-10-06 08:25:00'),
    (15, 2, 106, 100.00, '2026-10-06 08:04:00', '2026-10-06 08:20:00'),
    (16, 2, 108,  80.00, '2026-10-06 08:12:00', '2026-10-06 08:31:00'),
    (17, 2, 109,  60.00, '2026-10-06 08:15:00', '2026-10-06 08:35:00'),
    (18, 2, 110,  80.00, '2026-10-06 08:09:00', '2026-10-06 08:27:00'),

    -- Kuis 3 (INFO X-1): 8 siswa
    (19, 3, 101, 100.00, '2026-10-09 09:05:00', '2026-10-09 09:22:00'),
    (20, 3, 102, 100.00, '2026-10-09 09:07:00', '2026-10-09 09:24:00'),
    (21, 3, 103,  80.00, '2026-10-09 09:10:00', '2026-10-09 09:32:00'),
    (22, 3, 104,  80.00, '2026-10-09 09:04:00', '2026-10-09 09:25:00'),
    (23, 3, 105, 100.00, '2026-10-09 09:02:00', '2026-10-09 09:18:00'),
    (24, 3, 106,  80.00, '2026-10-09 09:12:00', '2026-10-09 09:30:00'),
    (25, 3, 107,  60.00, '2026-10-09 09:15:00', '2026-10-09 09:37:00'),
    (26, 3, 108, 100.00, '2026-10-09 09:03:00', '2026-10-09 09:20:00'),

    -- Kuis 5 (INFO X-2): 10 siswa X-2 lengkap
    (27, 5, 111, 100.00, '2026-10-09 10:05:00', '2026-10-09 10:22:00'),
    (28, 5, 112,  80.00, '2026-10-09 10:10:00', '2026-10-09 10:31:00'),
    (29, 5, 113,  60.00, '2026-10-09 10:15:00', '2026-10-09 10:39:00'),
    (30, 5, 114,  80.00, '2026-10-09 10:08:00', '2026-10-09 10:26:00'),
    (31, 5, 115, 100.00, '2026-10-09 10:02:00', '2026-10-09 10:19:00'),
    (32, 5, 116, 100.00, '2026-10-09 10:04:00', '2026-10-09 10:21:00'),
    (33, 5, 117,  80.00, '2026-10-09 10:12:00', '2026-10-09 10:33:00'),
    (34, 5, 118,  80.00, '2026-10-09 10:14:00', '2026-10-09 10:32:00'),
    (35, 5, 119, 100.00, '2026-10-09 10:06:00', '2026-10-09 10:24:00'),
    (36, 5, 120,  60.00, '2026-10-09 10:18:00', '2026-10-09 10:42:00'),

    -- Kuis 6 (MTK XI-IPA): 10 siswa XI-IPA lengkap
    (37, 6, 121, 100.00, '2026-10-04 07:05:00', '2026-10-04 07:26:00'),
    (38, 6, 122,  80.00, '2026-10-04 07:10:00', '2026-10-04 07:33:00'),
    (39, 6, 123,  80.00, '2026-10-04 07:12:00', '2026-10-04 07:34:00'),
    (40, 6, 124, 100.00, '2026-10-04 07:02:00', '2026-10-04 07:22:00'),
    (41, 6, 125,  60.00, '2026-10-04 07:15:00', '2026-10-04 07:41:00'),
    (42, 6, 126,  80.00, '2026-10-04 07:09:00', '2026-10-04 07:30:00'),
    (43, 6, 127, 100.00, '2026-10-04 07:03:00', '2026-10-04 07:24:00'),
    (44, 6, 128,  80.00, '2026-10-04 07:14:00', '2026-10-04 07:36:00'),
    (45, 6, 129,  80.00, '2026-10-04 07:16:00', '2026-10-04 07:38:00'),
    (46, 6, 130, 100.00, '2026-10-04 07:06:00', '2026-10-04 07:27:00'),

    -- Kuis 8 (FIS XII-IPA): 10 siswa XII-IPA lengkap
    (47, 8, 141, 100.00, '2026-10-12 08:05:00', '2026-10-12 08:32:00'),
    (48, 8, 142,  80.00, '2026-10-12 08:10:00', '2026-10-12 08:38:00'),
    (49, 8, 143, 100.00, '2026-10-12 08:02:00', '2026-10-12 08:29:00'),
    (50, 8, 144,  80.00, '2026-10-12 08:12:00', '2026-10-12 08:42:00'),
    (51, 8, 145,  80.00, '2026-10-12 08:08:00', '2026-10-12 08:35:00'),
    (52, 8, 146,  60.00, '2026-10-12 08:15:00', '2026-10-12 08:46:00'),
    (53, 8, 147, 100.00, '2026-10-12 08:04:00', '2026-10-12 08:31:00'),
    (54, 8, 148,  80.00, '2026-10-12 08:14:00', '2026-10-12 08:41:00'),
    (55, 8, 149,  80.00, '2026-10-12 08:11:00', '2026-10-12 08:39:00'),
    (56, 8, 150,  60.00, '2026-10-12 08:18:00', '2026-10-12 08:50:00');

-- -------------------------------------------------------------
-- JAWABAN KUIS MASING-MASING SISWA
-- Menyimpan detail opsi yang dipilih siswa untuk tiap butir soal
-- -------------------------------------------------------------
INSERT INTO `jawaban_kuis` (`hasil_kuis_id`, `soal_id`, `jawaban`) VALUES
    -- Hasil 1 (Ahmad Fauzi - Kuis 1: Nilai 100)
    (1, 1, 'a'), (1, 2, 'b'), (1, 3, 'a'), (1, 4, 'a'), (1, 5, 'a'),
    -- Hasil 2 (Bella Puspita - Kuis 1: Nilai 80)
    (2, 1, 'a'), (2, 2, 'b'), (2, 3, 'a'), (2, 4, 'a'), (2, 5, 'b'),
    -- Hasil 3 (Candra Wijaya - Kuis 1: Nilai 60)
    (3, 1, 'a'), (3, 2, 'b'), (3, 3, 'b'), (3, 4, 'b'), (3, 5, 'a'),
    -- Hasil 4 (Dinda Maharani - Kuis 1: Nilai 100)
    (4, 1, 'a'), (4, 2, 'b'), (4, 3, 'a'), (4, 4, 'a'), (4, 5, 'a'),
    -- Hasil 5 (Eko Prasetyo - Kuis 1: Nilai 100)
    (5, 1, 'a'), (5, 2, 'b'), (5, 3, 'a'), (5, 4, 'a'), (5, 5, 'a'),
    -- Hasil 6 (Farah Azalea - Kuis 1: Nilai 80)
    (6, 1, 'a'), (6, 2, 'b'), (6, 3, 'a'), (6, 4, 'c'), (6, 5, 'a'),
    -- Hasil 7 (Gilang Ramadhan - Kuis 1: Nilai 80)
    (7, 1, 'a'), (7, 2, 'b'), (7, 3, 'b'), (7, 4, 'a'), (7, 5, 'a'),
    -- Hasil 8 (Hana Safira - Kuis 1: Nilai 100)
    (8, 1, 'a'), (8, 2, 'b'), (8, 3, 'a'), (8, 4, 'a'), (8, 5, 'a'),
    -- Hasil 9 (Ilham Pratama - Kuis 1: Nilai 60)
    (9, 1, 'a'), (9, 2, 'c'), (9, 3, 'a'), (9, 4, 'b'), (9, 5, 'a'),
    -- Hasil 10 (Jovanka Aurelia - Kuis 1: Nilai 80)
    (10, 1, 'a'), (10, 2, 'b'), (10, 3, 'a'), (10, 4, 'a'), (10, 5, 'c'),

    -- Hasil 11 (Ahmad Fauzi - Kuis 2: Nilai 80)
    (11, 6, 'a'), (11, 7, 'a'), (11, 8, 'a'), (11, 9, 'a'), (11, 10, 'b'),
    -- Hasil 12 (Bella Puspita - Kuis 2: Nilai 80)
    (12, 6, 'a'), (12, 7, 'a'), (12, 8, 'a'), (12, 9, 'b'), (12, 10, 'a'),
    -- Hasil 13 (Dinda Maharani - Kuis 2: Nilai 100)
    (13, 6, 'a'), (13, 7, 'a'), (13, 8, 'a'), (13, 9, 'a'), (13, 10, 'a'),
    -- Hasil 14 (Eko Prasetyo - Kuis 2: Nilai 80)
    (14, 6, 'a'), (14, 7, 'a'), (14, 8, 'b'), (14, 9, 'a'), (14, 10, 'a'),
    -- Hasil 15 (Farah Azalea - Kuis 2: Nilai 100)
    (15, 6, 'a'), (15, 7, 'a'), (15, 8, 'a'), (15, 9, 'a'), (15, 10, 'a'),
    -- Hasil 16 (Hana Safira - Kuis 2: Nilai 80)
    (16, 6, 'a'), (16, 7, 'a'), (16, 8, 'a'), (16, 9, 'c'), (16, 10, 'a'),
    -- Hasil 17 (Ilham Pratama - Kuis 2: Nilai 60)
    (17, 6, 'a'), (17, 7, 'b'), (17, 8, 'a'), (17, 9, 'd'), (17, 10, 'a'),
    -- Hasil 18 (Jovanka Aurelia - Kuis 2: Nilai 80)
    (18, 6, 'a'), (18, 7, 'a'), (18, 8, 'c'), (18, 9, 'a'), (18, 10, 'a'),

    -- Hasil 19-26 (Kuis 3 INFO X-1)
    (19, 11, 'a'), (19, 12, 'a'), (19, 13, 'a'), (19, 14, 'a'), (19, 15, 'a'),
    (20, 11, 'a'), (20, 12, 'a'), (20, 13, 'a'), (20, 14, 'a'), (20, 15, 'a'),
    (21, 11, 'a'), (21, 12, 'a'), (21, 13, 'b'), (21, 14, 'a'), (21, 15, 'a'),
    (22, 11, 'a'), (22, 12, 'b'), (22, 13, 'a'), (22, 14, 'a'), (22, 15, 'a'),
    (23, 11, 'a'), (23, 12, 'a'), (23, 13, 'a'), (23, 14, 'a'), (23, 15, 'a'),
    (24, 11, 'a'), (24, 12, 'a'), (24, 13, 'a'), (24, 14, 'c'), (24, 15, 'a'),
    (25, 11, 'a'), (25, 12, 'b'), (25, 13, 'b'), (25, 14, 'a'), (25, 15, 'a'),
    (26, 11, 'a'), (26, 12, 'a'), (26, 13, 'a'), (26, 14, 'a'), (26, 15, 'a'),

    -- Hasil 27-36 (Kuis 5 INFO X-2: Siswa 111 - 120)
    (27, 21, 'a'), (27, 22, 'a'), (27, 23, 'a'), (27, 24, 'a'), (27, 25, 'a'),
    (28, 21, 'a'), (28, 22, 'a'), (28, 23, 'a'), (28, 24, 'b'), (28, 25, 'a'),
    (29, 21, 'a'), (29, 22, 'b'), (29, 23, 'b'), (29, 24, 'a'), (29, 25, 'a'),
    (30, 21, 'a'), (30, 22, 'a'), (30, 23, 'c'), (30, 24, 'a'), (30, 25, 'a'),
    (31, 21, 'a'), (31, 22, 'a'), (31, 23, 'a'), (31, 24, 'a'), (31, 25, 'a'),
    (32, 21, 'a'), (32, 22, 'a'), (32, 23, 'a'), (32, 24, 'a'), (32, 25, 'a'),
    (33, 21, 'a'), (33, 22, 'a'), (33, 23, 'a'), (33, 24, 'c'), (33, 25, 'a'),
    (34, 21, 'a'), (34, 22, 'b'), (34, 23, 'a'), (34, 24, 'a'), (34, 25, 'a'),
    (35, 21, 'a'), (35, 22, 'a'), (35, 23, 'a'), (35, 24, 'a'), (35, 25, 'a'),
    (36, 21, 'a'), (36, 22, 'c'), (36, 23, 'b'), (36, 24, 'a'), (36, 25, 'a'),

    -- Hasil 37-46 (Kuis 6 MTK XI-IPA: Siswa 121 - 130)
    (37, 26, 'a'), (37, 27, 'a'), (37, 28, 'a'), (37, 29, 'a'), (37, 30, 'a'),
    (38, 26, 'a'), (38, 27, 'a'), (38, 28, 'a'), (38, 29, 'b'), (38, 30, 'a'),
    (39, 26, 'a'), (39, 27, 'a'), (39, 28, 'b'), (39, 29, 'a'), (39, 30, 'a'),
    (40, 26, 'a'), (40, 27, 'a'), (40, 28, 'a'), (40, 29, 'a'), (40, 30, 'a'),
    (41, 26, 'b'), (41, 27, 'a'), (41, 28, 'c'), (41, 29, 'a'), (41, 30, 'a'),
    (42, 26, 'a'), (42, 27, 'a'), (42, 28, 'a'), (42, 29, 'd'), (42, 30, 'a'),
    (43, 26, 'a'), (43, 27, 'a'), (43, 28, 'a'), (43, 29, 'a'), (43, 30, 'a'),
    (44, 26, 'a'), (44, 27, 'b'), (44, 28, 'a'), (44, 29, 'a'), (44, 30, 'a'),
    (45, 26, 'a'), (45, 27, 'a'), (45, 28, 'a'), (45, 29, 'c'), (45, 30, 'a'),
    (46, 26, 'a'), (46, 27, 'a'), (46, 28, 'a'), (46, 29, 'a'), (46, 30, 'a'),

    -- Hasil 47-56 (Kuis 8 FIS XII-IPA: Siswa 141 - 150)
    (47, 36, 'a'), (47, 37, 'a'), (47, 38, 'a'), (47, 39, 'a'), (47, 40, 'a'),
    (48, 36, 'a'), (48, 37, 'a'), (48, 38, 'a'), (48, 39, 'b'), (48, 40, 'a'),
    (49, 36, 'a'), (49, 37, 'a'), (49, 38, 'a'), (49, 39, 'a'), (49, 40, 'a'),
    (50, 36, 'a'), (50, 37, 'a'), (50, 38, 'c'), (50, 39, 'a'), (50, 40, 'a'),
    (51, 36, 'a'), (51, 37, 'b'), (51, 38, 'a'), (51, 39, 'a'), (51, 40, 'a'),
    (52, 36, 'b'), (52, 37, 'a'), (52, 38, 'd'), (52, 39, 'a'), (52, 40, 'a'),
    (53, 36, 'a'), (53, 37, 'a'), (53, 38, 'a'), (53, 39, 'a'), (53, 40, 'a'),
    (54, 36, 'a'), (54, 37, 'a'), (54, 38, 'a'), (54, 39, 'c'), (54, 40, 'a'),
    (55, 36, 'a'), (55, 37, 'c'), (55, 38, 'a'), (55, 39, 'a'), (55, 40, 'a'),
    (56, 36, 'b'), (56, 37, 'a'), (56, 38, 'b'), (56, 39, 'a'), (56, 40, 'a');

-- =====================================================================
-- MATERI PEMBELAJARAN (10 Modul Pembelajaran Berformat PDF)
-- =====================================================================
INSERT INTO `materi` (`id`, `pengampu_id`, `judul`, `deskripsi`, `file`, `file_asli`, `created_at`) VALUES
    (1, 1, 'Modul 1: Bilangan Berpangkat dan Bentuk Akar',
     'Modul pembelajaran lengkap mengenai konsep eksponen, sifat-sifat pangkat bulat positif dan negatif, serta operasi bentuk akar beserta latihan soal terstruktur.',
     '36ad90198debe9d5e3148f4b782a718a.pdf', 'Modul_1_Bilangan_Berpangkat.pdf', '2026-08-01 08:00:00'),

    (2, 1, 'Modul 2: Persamaan dan Pertidaksamaan Linear',
     'Pembahasan konsep penyelesaian persamaan linear satu variabel dan pertidaksamaan nilai mutlak linear satu variabel.',
     'a7aaef0125ceff01034c613a0a2da1ad.pdf', 'Modul_2_Persamaan_Linear.pdf', '2026-08-15 08:30:00'),

    (3, 4, 'Handout: Pengukuran dan Besaran Fisika',
     'Materi dasar pengukuran, angka penting, notasi ilmiah, dan ketidakpastian dalam pengukuran laboratorium fisika.',
     'e10adc3949ba59abbe56e057f20f883e.pdf', 'Handout_Besaran_dan_Satuan.pdf', '2026-08-05 09:00:00'),

    (4, 4, 'Modul Praktikum: Hukum Newton tentang Gerak',
     'Panduan praktikum laboratorium fisika untuk menguji Hukum I, II, dan III Newton menggunakan ticker timer dan kereta dinamika.',
     'c4ca4238a0b923820dcc509a6f75849b.pdf', 'Panduan_Praktikum_Hukum_Newton.pdf', '2026-08-25 08:00:00'),

    (5, 2, 'Bahan Ajar: Menulis Teks Laporan Hasil Observasi',
     'Pedoman menyusun teks LHO dengan kaidah kebahasaan yang baik, objektif, dan faktual.',
     'c81e728d9d4c2f636f067f89cc14862c.pdf', 'Bahan_Ajar_Teks_LHO.pdf', '2026-08-10 10:00:00'),

    (6, 2, 'Panduan Analisis Struktur Teks Anekdot',
     'Materi apresiasi dan analisis struktur teks anekdot: abstraksi, orientasi, krisis, reaksi, dan koda.',
     '36ad90198debe9d5e3148f4b782a718a.pdf', 'Analisis_Teks_Anekdot.pdf', '2026-09-02 09:15:00'),

    (7, 8, 'Slide: Dasar-Dasar Pemrograman Web (HTML5 & CSS3)',
     'Materi pengantar teknologi web modern, tag semantik HTML5, selektor CSS, flexbox, dan styling dasar.',
     'a7aaef0125ceff01034c613a0a2da1ad.pdf', 'Slide_Pemrograman_Web_Dasar.pdf', '2026-08-18 07:45:00'),

    (8, 8, 'Buku Saku: Logika dan Algoritma Komputer',
     'Rangkuman logika pemecahan masalah (problem solving), diagram alir (flowchart), dan pseudocode.',
     'e10adc3949ba59abbe56e057f20f883e.pdf', 'Buku_Saku_Algoritma.pdf', '2026-09-01 11:00:00'),

    (9, 3, 'Grammar Guide: Present Tenses in Daily Life',
     'Comprehensive grammar guide with practice exercises covering Simple Present, Present Continuous, and Present Perfect.',
     'c4ca4238a0b923820dcc509a6f75849b.pdf', 'English_Grammar_Guide_X.pdf', '2026-08-20 08:30:00'),

    (10, 21, 'Bahan Belajar: Rumus Cepat Trigonometri SMA',
     'Trik mengingat perbandingan trigonometri, sudut istimewa, dan rumus jumlah selisih dua sudut untuk kelas XI.',
     'c81e728d9d4c2f636f067f89cc14862c.pdf', 'Rumus_Cepat_Trigonometri.pdf', '2026-09-05 10:30:00');

-- =====================================================================
-- TUGAS / ASSIGNMENTS
-- =====================================================================
INSERT INTO `tugas` (`id`, `pengampu_id`, `judul`, `deskripsi`, `lampiran`, `lampiran_asli`, `tenggat`, `created_at`) VALUES
    (1, 1, 'Latihan Mandiri Bab 1: Eksponen & Bentuk Akar',
     'Kerjakan 10 soal uraian di lembar kertas atau ketik rapi, kemudian simpan dalam format PDF. Pastikan langkah pengerjaan ditulis secara runtut dan jelas.',
     '36ad90198debe9d5e3148f4b782a718a.pdf', 'Lembar_Soal_Latihan_Bab1_MTK.pdf',
     '2026-11-15 23:59:00', '2026-10-01 08:00:00'),

    (2, 4, 'Laporan Praktikum: Verifikasi Hukum II Newton',
     'Susun laporan lengkap hasil praktikum dengan format: Judul, Tujuan, Alat & Bahan, Prosedur, Tabel Data, Grafik Percepatan vs Gaya, Analisis, dan Kesimpulan.',
     'a7aaef0125ceff01034c613a0a2da1ad.pdf', 'Format_Laporan_Praktikum_Fisika.pdf',
     '2026-11-10 23:59:00', '2026-10-05 09:30:00'),

    (3, 2, 'Menulis Teks Laporan Hasil Observasi Lingkungan Sekolah',
     'Lakukan observasi salah satu fasilitas sekolah (taman, lab, perpustakaan, atau kantin). Tulis laporan hasil observasi dengan struktur yang tepat minimal 400 kata.',
     'e10adc3949ba59abbe56e057f20f883e.pdf', 'Panduan_Teks_LHO.pdf',
     '2026-11-20 23:59:00', '2026-10-10 10:00:00'),

    (4, 8, 'Proyek Mini: Website Biodata Diri dengan HTML & CSS',
     'Buat 1 halaman web berisi profil diri yang memuat: header, foto profil, deskripsi tentang saya, tabel riwayat pendidikan, dan link sosial media.',
     'c4ca4238a0b923820dcc509a6f75849b.pdf', 'Petunjuk_Proyek_Web_Biodata.pdf',
     '2026-11-25 23:59:00', '2026-10-08 13:00:00'),

    (5, 3, 'Writing Assignment: Descriptive Paragraph of Your Hero',
     'Write a descriptive paragraph (150-250 words) about a person you admire most. Use descriptive adjectives and sensory details effectively.',
     NULL, NULL,
     '2026-11-18 23:59:00', '2026-10-12 08:00:00'),

    (6, 21, 'Latihan Soal Trigonometri Analitik Kelas XI',
     'Selesaikan 5 soal pembuktian identitas trigonometri pada lembar kerja terlampir.',
     'c81e728d9d4c2f636f067f89cc14862c.pdf', 'Lembar_Kerja_Trigonometri_XI.pdf',
     '2026-11-22 23:59:00', '2026-10-14 09:00:00');

-- =====================================================================
-- PENGUMPULAN TUGAS (Dengan Penilaian & Komentar Feedback Guru)
-- =====================================================================
INSERT INTO `pengumpulan` (`id`, `tugas_id`, `siswa_id`, `file`, `file_asli`, `dikumpulkan_at`, `status`, `nilai`, `komentar`, `dinilai_at`) VALUES
    (1, 1, 101, '36ad90198debe9d5e3148f4b782a718a.pdf', 'Tugas1_MTK_Ahmad_Fauzi.pdf',
     '2026-10-04 14:20:00', 'tepat_waktu', 95.00, 'Penyelesaian sangat sistematis dan rapi. Perhatikan sedikit di penyederhanaan nomor 7.', '2026-10-06 09:00:00'),

    (2, 1, 102, 'a7aaef0125ceff01034c613a0a2da1ad.pdf', 'Tugas1_MTK_Bella_Puspita.pdf',
     '2026-10-05 10:15:00', 'tepat_waktu', 88.00, 'Bagus sekali Bella! Terus pertahankan ketelitian saat merasionalkan penyebut.', '2026-10-06 09:15:00'),

    (3, 1, 103, 'e10adc3949ba59abbe56e057f20f883e.pdf', 'Tugas1_MTK_Candra.pdf',
     '2026-10-05 16:45:00', 'tepat_waktu', 78.00, 'Sudah cukup baik, namun rumus pangkat pada soal nomor 4 dan 8 masih perlu diperbaiki.', '2026-10-06 09:30:00'),

    (4, 1, 104, 'c4ca4238a0b923820dcc509a6f75849b.pdf', 'Tugas1_MTK_Dinda_Maharani.pdf',
     '2026-10-06 08:10:00', 'tepat_waktu', 92.00, 'Langkah-langkah sangat jelas dan benar. Sangat memuaskan!', '2026-10-06 09:40:00'),

    (5, 1, 105, 'c81e728d9d4c2f636f067f89cc14862c.pdf', 'Tugas1_MTK_Eko_Prasetyo.pdf',
     '2026-10-05 20:30:00', 'tepat_waktu', 100.00, 'Sempurna! Semua nomor terjawab dengan tepat, rapi, dan sistematis.', '2026-10-06 09:50:00'),

    (6, 2, 101, '36ad90198debe9d5e3148f4b782a718a.pdf', 'Laporan_Fisika_Ahmad_Fauzi.pdf',
     '2026-10-15 15:00:00', 'tepat_waktu', 90.00, 'Grafik percepatan digambar dengan baik. Analisis data cukup mendalam.', '2026-10-17 11:00:00'),

    (7, 2, 102, 'a7aaef0125ceff01034c613a0a2da1ad.pdf', 'Laporan_Fisika_Bella.pdf',
     '2026-10-16 11:20:00', 'tepat_waktu', 85.00, 'Laporan lengkap, kesimpulan sudah selaras dengan hipotesis praktikum.', '2026-10-17 11:15:00'),

    (8, 2, 106, 'e10adc3949ba59abbe56e057f20f883e.pdf', 'Laporan_Fisika_Farah.pdf',
     '2026-10-16 19:10:00', 'tepat_waktu', 87.00, 'Tata tulis rapi, data pengamatan disajikan dalam tabel yang sangat jelas.', '2026-10-17 11:30:00'),

    (9, 4, 101, 'c4ca4238a0b923820dcc509a6f75849b.pdf', 'Proyek_Web_Ahmad_Fauzi.pdf',
     '2026-10-18 16:30:00', 'tepat_waktu', 95.00, 'Desain website sangat kreatif, struktur HTML semantik sudah tepat!', '2026-10-20 10:00:00'),

    (10, 4, 105, 'c81e728d9d4c2f636f067f89cc14862c.pdf', 'Proyek_Web_Eko.pdf',
     '2026-10-18 18:00:00', 'tepat_waktu', 98.00, 'Hebat! Tampilan responsif dan CSS styling sangat modern.', '2026-10-20 10:15:00');

-- =====================================================================
-- PERPUSTAKAAN DIGITAL / E-BOOK
-- =====================================================================
INSERT INTO `perpustakaan` (`id`, `judul`, `penulis`, `deskripsi`, `kategori`, `mapel_id`, `tingkat`, `file`, `file_asli`, `cover`, `uploader_id`, `created_at`) VALUES
    (1, 'Matematika SMA/MA/SMK/MAK Kelas X (Kurikulum Merdeka)',
     'Dicky Susanto, dkk. (Pusat Kurikulum Kemendikbudristek)',
     'Buku teks utama siswa mata pelajaran Matematika kelas X jenjang SMA/MA. Membahas Eksponen, Logaritma, Barisan dan Deret, Vektor, Trigonometri, dan Statistika.',
     'wajib', 1, 'X',
     '36ad90198debe9d5e3148f4b782a718a.pdf', 'Buku_Siswa_Matematika_Kelas_X.pdf',
     NULL, 1, '2026-07-10 08:00:00'),

    (2, 'Fisika untuk SMA/MA Kelas X',
     'Lia Laela Sarah, Puspa Pratama R. (Kemendikbudristek)',
     'Buku panduan pembelajaran Fisika kelas X berbasis inkuiri sains. Mengulas besaran dan satuan, metode ilmiah, energi terbarukan, dan pemanasan global.',
     'wajib', 4, 'X',
     'a7aaef0125ceff01034c613a0a2da1ad.pdf', 'Buku_Siswa_Fisika_Kelas_X.pdf',
     NULL, 1, '2026-07-10 08:30:00'),

    (3, 'Cerdas Cergas Berbahasa dan Bersastra Indonesia Kelas X',
     'Fadillah Tri Aulia, Sefi Indra Gumilar',
     'Buku teks utama Bahasa Indonesia Kurikulum Merdeka kelas X. Mengembangkan kemampuan menyimak, membaca, memirsa, berbicara, dan menulis teks fiksi serta nonfiksi.',
     'wajib', 2, 'X',
     'e10adc3949ba59abbe56e057f20f883e.pdf', 'Bahasa_Indonesia_Kelas_X.pdf',
     NULL, 1, '2026-07-10 09:00:00'),

    (4, 'Informatika untuk SMA Kelas X',
     'Mushthofa, dkk. (Pusat Kurikulum dan Perbukuan)',
     'Buku materi Informatika kelas X. Mencakup Berpikir Komputasional, Teknologi Informasi dan Komunikasi, Sistem Komputer, Jaringan Komputer, Analisis Data, dan Algoritma Pemrograman.',
     'wajib', 8, 'X',
     'c4ca4238a0b923820dcc509a6f75849b.pdf', 'Buku_Informatika_Kelas_X.pdf',
     NULL, 1, '2026-07-10 09:30:00'),

    (5, 'Matematika Tingkat Lanjut SMA/MA Kelas XI',
     'Al Azhary Masta, dkk.',
     'Buku siswa Matematika peminatan/tingkat lanjut kelas XI: Polinomial, Matriks, Transformasi Geometri, dan Fungsi Trigonometri Analitik.',
     'wajib', 1, 'XI',
     'c81e728d9d4c2f636f067f89cc14862c.pdf', 'Matematika_Tingkat_Lanjut_XI.pdf',
     NULL, 1, '2026-07-11 10:00:00'),

    (6, 'Biologi: Sel, Metabolisme, dan Genetika untuk SMA Kelas XI',
     'Rini Solihat, dkk. (Kemendikbudristek)',
     'Buku teks Biologi mendalam yang membahas struktur seluler, transportasi membran, respirasi aerob/anaerob, fotosintesis, dan pembelahan sel.',
     'wajib', 6, 'XI',
     '36ad90198debe9d5e3148f4b782a718a.pdf', 'Buku_Biologi_Kelas_XI.pdf',
     NULL, 1, '2026-07-11 10:30:00'),

    (7, 'Ensiklopedia Sejarah Perjuangan Kemerdekaan Indonesia',
     'Tim Sejarawan Nasional',
     'Buku referensi pengayaan sejarah perjuangan bangsa dari masa pergerakan nasional 1908 hingga diplomasi kemerdekaan 1949.',
     'pendukung', 7, 'semua',
     'a7aaef0125ceff01034c613a0a2da1ad.pdf', 'Sejarah_Perjuangan_Indonesia.pdf',
     NULL, 2, '2026-08-01 11:00:00'),

    (8, 'English for Change: Senior High School Grade XI',
     'Basuki, dkk. (Kemendikbudristek)',
     'Buku Bahasa Inggris kelas XI berbasis communicative approach dan isu-isu global kontemporer.',
     'wajib', 3, 'XI',
     'e10adc3949ba59abbe56e057f20f883e.pdf', 'English_Grade_XI.pdf',
     NULL, 1, '2026-07-11 11:00:00'),

    (9, 'Kamus Istilah Kimia & Tabel Periodik Interaktif',
     'Dr. Ir. Hendra Gunawan',
     'Buku saku referensi istilah-istilah kimia SMA, konfigurasi elektron, sifat keperiodikan, dan hukum dasar kimia.',
     'pendukung', 5, 'semua',
     'c4ca4238a0b923820dcc509a6f75849b.pdf', 'Kamus_Istilah_Kimia_SMA.pdf',
     NULL, 8, '2026-08-05 14:00:00'),

    (10, 'Panduan Kebugaran Jasmani dan Kesehatan Remaja',
     'Drs. Hendra Kusuma, S.Pd.',
     'Buku panduan olahraga mandiri, gizi seimbang, dan tips menjaga kesehatan jasmani bagi siswa sekolah menengah atas.',
     'pendukung', 10, 'semua',
     'c81e728d9d4c2f636f067f89cc14862c.pdf', 'Panduan_Kebugaran_Jasmani.pdf',
     NULL, 7, '2026-08-10 15:30:00');

-- =====================================================================
-- VIDEO PEMBELAJARAN
-- =====================================================================
INSERT INTO `video_pembelajaran` (`pengampu_id`, `judul`, `deskripsi`, `tipe`, `url_embed`, `durasi`, `created_at`) VALUES
    (1,  'Konsep Bilangan Berpangkat & Bentuk Akar', 'Video penjelasan bilangan berpangkat, sifat-sifat pangkat, dan operasi bentuk akar untuk siswa kelas X.', 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '18:45', '2026-08-15 08:00:00'),
    (1,  'Persamaan dan Pertidaksamaan Linear Satu Variabel', 'Pembahasan lengkap PLSV dan PtLSV disertai contoh soal dan pembahasan.', 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '22:30', '2026-08-22 08:00:00'),
    (4,  'Hukum Newton I, II, dan III Beserta Penerapannya', 'Penjelasan tiga hukum Newton tentang gerak, gaya, dan interaksi benda.', 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '25:10', '2026-09-01 08:00:00'),
    (4,  'Gerak Lurus Beraturan (GLB) dan Berubah Beraturan (GLBB)', 'Konsep GLB & GLBB lengkap dengan grafik dan rumus percepatan.', 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '20:00', '2026-09-10 08:00:00'),
    (2,  'Teks Deskripsi: Struktur dan Kebahasaan', 'Memahami struktur teks deskripsi dan ciri kebahasaannya.', 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '15:30', '2026-08-20 08:00:00'),
    (8,  'Pengantar Algoritma dan Pemrograman Dasar', 'Pengenalan logika algoritma, flowchart, dan pseudocode untuk pemula.', 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '30:00', '2026-09-05 08:00:00'),
    (8,  'HTML & CSS: Membuat Halaman Web Pertamamu', 'Tutorial praktis membuat halaman web sederhana dengan HTML dan CSS.', 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '35:20', '2026-09-15 08:00:00'),
    (3,  'Introduction to English Tenses: Simple Present & Present Continuous', 'Learn the differences between Simple Present and Present Continuous tense with examples.', 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '16:45', '2026-08-25 08:00:00'),
    (21, 'Trigonometri: Perbandingan Trigonometri Segitiga Siku-Siku', 'Konsep sin, cos, tan dan penerapannya pada segitiga siku-siku kelas XI.', 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '28:15', '2026-09-12 08:00:00'),
    (41, 'Gelombang Elektromagnetik dan Spektrumnya', 'Penjelasan sifat gelombang EM, spektrum, dan penerapannya dalam kehidupan sehari-hari.', 'youtube', 'https://www.youtube.com/embed/dQw4w9WgXcQ', '24:30', '2026-09-20 08:00:00');

-- =====================================================================
-- PENGUMUMAN
-- =====================================================================
INSERT INTO `pengumuman` (`pengampu_id`, `judul`, `isi`, `created_at`) VALUES
    (1, 'Jadwal Ulangan Harian Bab 1 Matematika',
     'Assalamu''alaikum anak-anak X-1. Ulangan Harian Bab 1 (Bilangan Berpangkat & Bentuk Akar) akan dilaksanakan pada hari Senin, 20 Oktober 2026. Materi yang diujikan meliputi:\n\n1. Sifat-sifat bilangan berpangkat\n2. Operasi bentuk akar\n3. Merasionalkan penyebut\n\nSilakan belajar dari materi dan video yang sudah saya bagikan. Selamat belajar!',
     '2026-10-10 07:30:00'),

    (4, 'Pengumpulan Laporan Praktikum Fisika Bab 2',
     'Kepada seluruh siswa X-1, laporan praktikum Fisika Bab 2 (Hukum Newton) wajib dikumpulkan paling lambat tanggal 25 Oktober 2026. Format laporan:\n\n- Halaman judul\n- Tujuan percobaan\n- Alat dan bahan\n- Langkah kerja\n- Data pengamatan\n- Analisis dan pembahasan\n- Kesimpulan\n\nKumpulkan dalam format PDF melalui menu Tugas.',
     '2026-10-12 09:00:00'),

    (8, 'Tugas Proyek Informatika: Website Biodata Pribadi',
     'Hai kelas X-1! Tugas proyek membuat website biodata pribadi menggunakan HTML dan CSS sudah dibuka. Kriteria penilaian:\n\n- Struktur HTML5 semantik (30%)\n- Desain CSS rapi dan estetik (30%)\n- Konten kreatif (20%)\n- Tampilan responsif di layar (20%)\n\nSemangat ngoding!',
     '2026-10-08 10:00:00'),

    (2, 'Tugas Menulis Teks Laporan Hasil Observasi',
     'Siswa X-1 diminta menyusun laporan observasi lingkungan sekolah minimal 400 kata. Kumpulkan via menu Tugas sebelum tanggal 28 Oktober 2026.',
     '2026-10-14 08:30:00'),

    (22, 'Kegiatan Literasi Bahasa Indonesia Kelas XI-IPA',
     'Dalam rangka Bulan Bahasa, seluruh siswa XI-IPA diminta membaca dan mereview satu buku sastra Indonesia. Review ditulis minimal 500 kata dan dikumpulkan paling lambat 5 November 2026.',
     '2026-10-15 07:00:00');

-- =====================================================================
-- KALENDER AKADEMIK
-- =====================================================================
INSERT INTO `kalender_akademik` (`judul`, `deskripsi`, `tipe`, `tanggal_mulai`, `tanggal_selesai`, `tahun_ajaran_id`) VALUES
    ('Masa Pengenalan Lingkungan Sekolah (MPLS)', 'Kegiatan orientasi siswa baru kelas X', 'kegiatan', '2026-07-13', '2026-07-15', 1),
    ('Upacara Hari Kemerdekaan RI ke-81', 'Upacara bendera dan lomba 17-an', 'kegiatan', '2026-08-17', '2026-08-17', 1),
    ('Penilaian Tengah Semester (PTS) Ganjil', 'Ujian tengah semester untuk seluruh kelas', 'ujian', '2026-09-29', '2026-10-10', 1),
    ('Libur Maulid Nabi Muhammad SAW', 'Hari libur nasional', 'libur', '2026-10-08', '2026-10-08', 1),
    ('Peringatan Hari Sumpah Pemuda', 'Upacara dan lomba debat antar kelas', 'kegiatan', '2026-10-28', '2026-10-28', 1),
    ('Hari Guru Nasional', 'Upacara dan kegiatan apresiasi guru', 'kegiatan', '2026-11-25', '2026-11-25', 1),
    ('Penilaian Akhir Semester (PAS) Ganjil', 'Ujian akhir semester ganjil untuk seluruh kelas', 'ujian', '2026-11-30', '2026-12-11', 1),
    ('Class Meeting & Porseni', 'Pertandingan olahraga dan seni antar kelas', 'kegiatan', '2026-12-14', '2026-12-17', 1),
    ('Pembagian Rapor Semester Ganjil', 'Pengambilan rapor oleh orang tua/wali', 'kegiatan', '2026-12-18', '2026-12-18', 1),
    ('Libur Semester Ganjil', 'Libur akhir semester ganjil dan libur Tahun Baru', 'libur', '2026-12-21', '2027-01-04', 1),
    ('Libur Isra Miraj Nabi Muhammad SAW', 'Hari libur nasional', 'libur', '2027-01-27', '2027-01-27', 1),
    ('Ujian Praktik Kelas XII', 'Ujian praktik semua mata pelajaran kelas XII', 'ujian', '2027-02-15', '2027-02-28', 1);

-- =====================================================================
-- CATATAN SISWA
-- =====================================================================
INSERT INTO `catatan_siswa` (`siswa_id`, `mapel_id`, `judul`, `isi`, `created_at`, `updated_at`) VALUES
    (101, 1, 'Rangkuman Rumus Pangkat & Eksponen', 'Sifat-sifat bilangan berpangkat:\n1. a^m × a^n = a^(m+n)\n2. a^m ÷ a^n = a^(m-n)\n3. (a^m)^n = a^(m×n)\n4. (ab)^n = a^n × b^n\n5. a^0 = 1 (a ≠ 0)\n6. a^(-n) = 1/a^n\n\nContoh:\n2^3 × 2^4 = 2^7 = 128', '2026-10-01 19:00:00', '2026-10-05 20:30:00'),
    (101, 4, 'Catatan Tiga Hukum Newton', '- Hukum I (Inersia): Benda diam tetap diam, benda bergerak tetap bergerak lurus beraturan jika resultan gaya = 0\n- Hukum II: F = m × a (Gaya = massa × percepatan)\n- Hukum III: Aksi = Reaksi, gaya selalu berpasangan', '2026-10-07 20:00:00', '2026-10-07 20:00:00'),
    (102, 2, 'Struktur Teks Laporan Hasil Observasi', 'Teks LHO:\n1. Pernyataan Umum / Definisi: pengenalan objek secara umum\n2. Deskripsi Bagian: penjabaran ciri-ciri bagian secara detail\n3. Deskripsi Manfaat: manfaat objek dalam kehidupan', '2026-10-06 18:00:00', '2026-10-06 18:00:00'),
    (105, 8, 'Cheat Sheet Dasar HTML & CSS', 'Tag penting HTML:\n<html>, <head>, <title>, <body>\n<h1> - <h6>: Heading\n<p>: Paragraf\n<a href="">: Hyperlink\n<img src="">: Gambar\n<ul>, <ol>, <li>: List\n<div>, <span>: Kontainer\n\nCSS Properties:\ncolor, background, font-family, margin, padding, border, display: flex', '2026-10-10 21:00:00', '2026-10-10 21:00:00'),
    (121, 1, 'Rumus Identitas Trigonometri', 'sin θ = depan / miring\ncos θ = samping / miring\ntan θ = depan / samping\n\nIdentitas Utama:\nsin²θ + cos²θ = 1\n1 + tan²θ = sec²θ\n1 + cot²θ = csc²θ', '2026-10-13 20:30:00', '2026-10-15 19:00:00');

-- =====================================================================
-- PENCAPAIAN / BADGE PRESTASI SISWA
-- =====================================================================
INSERT INTO `pencapaian` (`siswa_id`, `tipe`, `label`, `deskripsi`, `ikon`, `diperoleh_at`) VALUES
    (101, 'nilai_sempurna', 'Nilai Sempurna', 'Mendapatkan nilai 100 pada kuis Matematika & Informatika', '💯', '2026-10-02 07:25:00'),
    (101, 'tepat_waktu',   'Pengumpul Tepat Waktu', 'Mengumpulkan seluruh tugas belajar tepat waktu', '⏰', '2026-10-15 09:00:00'),
    (102, 'tepat_waktu',   'Pengumpul Tepat Waktu', 'Mengumpulkan tugas belajar tepat waktu', '⏰', '2026-10-16 14:00:00'),
    (104, 'nilai_sempurna', 'Nilai Sempurna', 'Mendapatkan nilai 100 pada kuis Fisika', '💯', '2026-10-06 08:25:00'),
    (105, 'nilai_sempurna', 'Nilai Sempurna', 'Mendapatkan nilai 100 pada kuis Matematika dan Tugas', '💯', '2026-10-02 07:22:00'),
    (105, 'rajin_kuis',    'Rajin Latihan', 'Telah menyelesaikan semua kuis yang tersedia', '📝', '2026-10-10 10:00:00'),
    (111, 'nilai_sempurna', 'Nilai Sempurna', 'Mendapatkan nilai 100 pada kuis Informatika X-2', '💯', '2026-10-09 10:25:00'),
    (115, 'rajin_kuis',    'Rajin Latihan', 'Mengerjakan kuis tepat waktu dengan nilai maksimal', '📝', '2026-10-09 10:22:00'),
    (121, 'nilai_sempurna', 'Nilai Sempurna', 'Mendapatkan nilai 100 pada kuis Trigonometri XI-IPA', '💯', '2026-10-04 07:30:00'),
    (127, 'rajin_kuis',    'Rajin Latihan', 'Telah menyelesaikan 5 kuis dengan nilai memuaskan', '📝', '2026-10-20 11:00:00'),
    (141, 'tepat_waktu',   'Pengumpul Tepat Waktu', 'Mengumpulkan seluruh laporan praktikum tepat waktu', '⏰', '2026-10-17 09:15:00'),
    (141, 'nilai_sempurna', 'Nilai Sempurna', 'Mendapatkan nilai 100 pada kuis Fisika Modern XII-IPA', '💯', '2026-10-12 08:35:00'),
    (143, 'rajin_kuis',    'Rajin Latihan', 'Telah mengerjakan seluruh kuis persiapan ujian', '📝', '2026-10-21 10:00:00');

-- =====================================================================
-- Selesai. Seluruh data skema, guru, siswa, presensi, dan kuis telah dimuat.
-- =====================================================================
