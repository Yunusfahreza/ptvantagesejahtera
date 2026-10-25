-- =====================================================================
-- LMS SMA - Skema + Seed
-- PT. Vantage Sejahtera / SMAN 10 Garut
--
-- Impor:
--   mysql -u root -p < database/lms.sql
-- atau lewat phpMyAdmin (menu Import).
--
-- PERINGATAN: skrip ini membuang lalu membuat ulang seluruh tabel di
-- database lms_sma. Jangan jalankan di atas data produksi.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `lms_sma`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `lms_sma`;

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

-- ---------------------------------------------------------------------
-- Master
-- ---------------------------------------------------------------------

CREATE TABLE `tahun_ajaran` (
    `id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama`     VARCHAR(20) NOT NULL COMMENT 'contoh: 2026/2027',
    `semester` ENUM('ganjil','genap') NOT NULL,
    `aktif`    TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_tahun_semester` (`nama`, `semester`),
    KEY `idx_aktif` (`aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `kelas` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama`            VARCHAR(30) NOT NULL COMMENT 'contoh: X-1',
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
    `kelas_id`      INT UNSIGNED NULL COMMENT 'wajib untuk siswa, NULL untuk admin/guru',
    `status`        ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
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

-- Guru pengampu: pasangan (guru, kelas, mapel). Satu mapel di satu kelas
-- hanya boleh punya satu guru pengampu (PRD F04).
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

-- ---------------------------------------------------------------------
-- Konten
-- ---------------------------------------------------------------------

CREATE TABLE `materi` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `pengampu_id` INT UNSIGNED NOT NULL,
    `judul`       VARCHAR(150) NOT NULL,
    `deskripsi`   TEXT NULL,
    `file`        VARCHAR(100) NOT NULL COMMENT 'nama acak di folder uploads',
    `file_asli`   VARCHAR(255) NOT NULL COMMENT 'nama asli untuk Content-Disposition',
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

-- ---------------------------------------------------------------------
-- Kuis
-- ---------------------------------------------------------------------

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
    `nilai`      DECIMAL(5,2) NULL COMMENT 'NULL = belum difinalisasi',
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

-- ---------------------------------------------------------------------
-- Pengumuman
-- ---------------------------------------------------------------------

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

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- SEED DEMO
-- Password demo: admin12345 / guru12345 / siswa12345
-- GANTI SEMUA PASSWORD INI SEBELUM DIPAKAI DI PRODUKSI.
-- =====================================================================

INSERT INTO `tahun_ajaran` (`id`, `nama`, `semester`, `aktif`) VALUES
    (1, '2026/2027', 'ganjil', 1),
    (2, '2025/2026', 'genap',  0);

INSERT INTO `kelas` (`id`, `nama`, `tingkat`, `tahun_ajaran_id`) VALUES
    (1, 'X-1',   'X',   1),
    (2, 'X-2',   'X',   1),
    (3, 'XI-1',  'XI',  1),
    (4, 'XII-1', 'XII', 1);

INSERT INTO `mapel` (`id`, `kode`, `nama`) VALUES
    (1, 'MTK',  'Matematika'),
    (2, 'BIND', 'Bahasa Indonesia'),
    (3, 'BING', 'Bahasa Inggris'),
    (4, 'FIS',  'Fisika'),
    (5, 'KIM',  'Kimia'),
    (6, 'BIO',  'Biologi'),
    (7, 'SEJ',  'Sejarah'),
    (8, 'INFO', 'Informatika');

-- Admin
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`) VALUES
    (1, 'Administrator Sekolah', 'admin', '$2y$10$9pT6bXvGn8lGIhGSBvijg.nuQOzHYQxckdqd7AzkRRhHSKfuZlI0W', 'admin', NULL, 'aktif');

-- Guru. guru.agus sengaja nonaktif untuk menguji penolakan login (F01).
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`) VALUES
    (2, 'Siti Rahmawati, S.Pd.', 'guru.siti', '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif'),
    (3, 'Budi Hartono, S.Si.',   'guru.budi', '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif'),
    (4, 'Dewi Lestari, M.Pd.',   'guru.dewi', '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'aktif'),
    (5, 'Agus Setiawan, S.Kom.', 'guru.agus', '$2y$10$m152JVQLLsQe4uaICzxxGO1NUtEA32Km9ZWdp7zXgm3FS2onoT3Z6', 'guru', NULL, 'nonaktif');

-- Siswa kelas X-1
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`) VALUES
    (10, 'Ahmad Fauzi',    'siswa.ahmad',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif'),
    (11, 'Bella Puspita',  'siswa.bella',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif'),
    (12, 'Candra Wijaya',  'siswa.candra', '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif'),
    (13, 'Dinda Maharani', 'siswa.dinda',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif'),
    (14, 'Eko Prasetyo',   'siswa.eko',    '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 1, 'aktif');

-- Siswa kelas X-2. Dipakai menguji isolasi antar kelas (skenario T06).
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`) VALUES
    (20, 'Fitri Handayani', 'siswa.fitri', '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif'),
    (21, 'Galih Nugroho',   'siswa.galih', '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif'),
    (22, 'Hana Safira',     'siswa.hana',  '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 2, 'aktif');

-- Siswa kelas XI-1
INSERT INTO `users` (`id`, `nama`, `username`, `password_hash`, `role`, `kelas_id`, `status`) VALUES
    (30, 'Irfan Maulana', 'siswa.irfan',   '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif'),
    (31, 'Jasmine Aulia', 'siswa.jasmine', '$2y$10$oZaBY5JXQeQEC76XKfXk.uA/Pfytr9S0UhR6jSZAx2c7Eu.WVIoHu', 'siswa', 3, 'aktif');

-- Pengampu: pasangan (kelas, mapel) wajib unik.
INSERT INTO `pengampu` (`id`, `guru_id`, `kelas_id`, `mapel_id`) VALUES
    (1, 2, 1, 1),   -- Siti - Matematika       - X-1
    (2, 2, 2, 1),   -- Siti - Matematika       - X-2
    (3, 3, 1, 4),   -- Budi - Fisika           - X-1
    (4, 4, 1, 2),   -- Dewi - Bahasa Indonesia - X-1
    (5, 4, 3, 2),   -- Dewi - Bahasa Indonesia - XI-1
    (6, 3, 3, 1);   -- Budi - Matematika       - XI-1
