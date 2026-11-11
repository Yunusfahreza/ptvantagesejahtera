<?php

/**
 * Migrasi & Seeder Fitur Google Meet (Pertemuan Tatap Muka Daring).
 * Idempotent: aman dijalankan berkali-kali pada basis data lms_sma.
 */

declare(strict_types=1);

$config = require __DIR__ . '/../config/config.php';

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    $config['db']['host'],
    $config['db']['port'],
    $config['db']['name'],
    $config['db']['charset']
);

$pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// 1. Buat tabel google_meet bila belum ada
$pdo->exec("
    CREATE TABLE IF NOT EXISTS `google_meet` (
        `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `pengampu_id`  INT UNSIGNED NOT NULL,
        `judul`        VARCHAR(150) NOT NULL,
        `deskripsi`    TEXT NULL,
        `link_meet`    VARCHAR(255) NOT NULL,
        `tanggal`      DATE NOT NULL,
        `jam_mulai`    TIME NOT NULL,
        `jam_selesai`  TIME NOT NULL,
        `status`       ENUM('terjadwal','berlangsung','selesai') NOT NULL DEFAULT 'terjadwal',
        `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_meet_pengampu` (`pengampu_id`),
        KEY `idx_meet_tanggal` (`tanggal`, `jam_mulai`),
        CONSTRAINT `fk_meet_pengampu` FOREIGN KEY (`pengampu_id`)
            REFERENCES `pengampu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

echo "[OK] Tabel google_meet tersedia.\n";

// 2. Seed data demo bila masih kosong
$jumlah = (int) $pdo->query('SELECT COUNT(*) FROM google_meet')->fetchColumn();

if ($jumlah > 0) {
    echo "[SKIP] Sudah terdapat {$jumlah} sesi Google Meet di basis data.\n";
    exit(0);
}

$cariPengampu = $pdo->prepare(
    'SELECT p.id
       FROM pengampu p
       JOIN kelas k ON k.id = p.kelas_id
       JOIN mapel m ON m.id = p.mapel_id
      WHERE k.nama = ? AND m.kode = ?
      LIMIT 1'
);

$hariIni = date('Y-m-d');
$besok   = date('Y-m-d', strtotime('+1 day'));
$lusa    = date('Y-m-d', strtotime('+2 days'));
$kemarin = date('Y-m-d', strtotime('-2 days'));

$sampel = [
    [
        'kelas'       => 'XII-IPA',
        'mapel'       => 'MTK',
        'judul'       => 'Klinik Soal Persiapan Ujian Akhir: Integral & Limit',
        'deskripsi'   => 'Pembahasan soal HOTS limit fungsi trigonometri dan integral tentu bersama Bu Siti. Siapkan buku latihan.',
        'link_meet'   => 'https://meet.google.com/sman-xipa-mtk',
        'tanggal'     => $hariIni,
        'jam_mulai'   => '08:00:00',
        'jam_selesai' => '09:30:00',
        'status'      => 'berlangsung',
    ],
    [
        'kelas'       => 'XII-IPA',
        'mapel'       => 'BING',
        'judul'       => 'Academic Speaking Practice: Analytical Exposition Debate',
        'deskripsi'   => 'Simulasi argumen tim pro & kontra serta review tata bahasa sebelum penilaian lisan pekan depan.',
        'link_meet'   => 'https://meet.google.com/bing-xipa-deb',
        'tanggal'     => $besok,
        'jam_mulai'   => '10:00:00',
        'jam_selesai' => '11:30:00',
        'status'      => 'terjadwal',
    ],
    [
        'kelas'       => 'XII-IPA',
        'mapel'       => 'FIS',
        'judul'       => 'Bedah Materi Gelombang Elektromagnetik & Induksi Faraday',
        'deskripsi'   => 'Sesi tanya jawab konsep gaya gerak listrik induksi beserta simulasi PhET virtual.',
        'link_meet'   => 'https://meet.google.com/fis-xipa-gel',
        'tanggal'     => $kemarin,
        'jam_mulai'   => '07:30:00',
        'jam_selesai' => '09:00:00',
        'status'      => 'selesai',
    ],
    [
        'kelas'       => 'XI-IPA',
        'mapel'       => 'INFO',
        'judul'       => 'Workshop Basis Data Relasional & Normalisasi Tabel MySQL',
        'deskripsi'   => 'Praktik langsung merancang ERD hingga 3NF menggunakan studi kasus sistem akademik sekolah.',
        'link_meet'   => 'https://meet.google.com/info-xiipa-db',
        'tanggal'     => $hariIni,
        'jam_mulai'   => '09:00:00',
        'jam_selesai' => '10:30:00',
        'status'      => 'berlangsung',
    ],
    [
        'kelas'       => 'XI-IPA',
        'mapel'       => 'BIO',
        'judul'       => 'Diskusi Interaktif Mekanisme Transpor Membran Sel',
        'deskripsi'   => 'Review hasil pengamatan difusi-osmosis dan kuis interaktif singkat via Google Meet.',
        'link_meet'   => 'https://meet.google.com/bio-xiipa-sel',
        'tanggal'     => $lusa,
        'jam_mulai'   => '13:00:00',
        'jam_selesai' => '14:15:00',
        'status'      => 'terjadwal',
    ],
    [
        'kelas'       => 'X-1',
        'mapel'       => 'MTK',
        'judul'       => 'Pendalaman Konsep Bilangan Berpangkat & Bentuk Akar',
        'deskripsi'   => 'Penjelasan sifat eksponen pecahan dan latihan merasionalkan penyebut secara langsung.',
        'link_meet'   => 'https://meet.google.com/mtk-xone-exp',
        'tanggal'     => $hariIni,
        'jam_mulai'   => '10:30:00',
        'jam_selesai' => '12:00:00',
        'status'      => 'berlangsung',
    ],
    [
        'kelas'       => 'X-1',
        'mapel'       => 'FIS',
        'judul'       => 'Pembahasan Soal Gerak Lurus Berubah Beraturan (GLBB)',
        'deskripsi'   => 'Analisis grafik v-t dan penerapan hukum Newton pada bidang miring.',
        'link_meet'   => 'https://meet.google.com/fis-xone-glb',
        'tanggal'     => $besok,
        'jam_mulai'   => '08:30:00',
        'jam_selesai' => '10:00:00',
        'status'      => 'terjadwal',
    ],
];

$ins = $pdo->prepare(
    'INSERT INTO google_meet (pengampu_id, judul, deskripsi, link_meet, tanggal, jam_mulai, jam_selesai, status)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
);

$tersimpan = 0;
foreach ($sampel as $item) {
    $cariPengampu->execute([$item['kelas'], $item['mapel']]);
    $pengampuId = $cariPengampu->fetchColumn();

    if ($pengampuId) {
        $ins->execute([
            (int) $pengampuId,
            $item['judul'],
            $item['deskripsi'],
            $item['link_meet'],
            $item['tanggal'],
            $item['jam_mulai'],
            $item['jam_selesai'],
            $item['status'],
        ]);
        $tersimpan++;
        echo "[SEED] {$item['kelas']} ({$item['mapel']}): {$item['judul']} [{$item['status']}]\n";
    }
}

echo "[DONE] {$tersimpan} sesi Google Meet berhasil disisipkan.\n";
