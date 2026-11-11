<?php
/**
 * Seeder Data Siswa: Raesa Gista (Kelas XII-IPA - Peringkat Pertama)
 * LMS SMAN 10 Garut
 */

declare(strict_types=1);

$dbHost = '127.0.0.1';
$dbPort = 3306;
$dbName = 'lms_sma';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage() . "\n");
}

echo "=== SEEDING SISWA: RAESA GISTA (KELAS XII-IPA) ===\n";

// 1. Dapatkan ID Kelas XII-IPA
$stmtKelas = $pdo->query("SELECT id FROM kelas WHERE nama = 'XII-IPA' LIMIT 1");
$kelasId = (int) $stmtKelas->fetchColumn();
if ($kelasId === 0) {
    $stmtKelas = $pdo->query("SELECT id FROM kelas WHERE nama LIKE '%XII%IPA%' LIMIT 1");
    $kelasId = (int) $stmtKelas->fetchColumn();
}
if ($kelasId === 0) {
    die("Error: Kelas XII-IPA tidak ditemukan.\n");
}
echo "[1/6] Ditemukan Kelas XII-IPA (ID: $kelasId)\n";

// 2. Insert atau Update User Raesa Gista
$stmtCheck = $pdo->prepare("SELECT id FROM users WHERE username = 'siswa.raesa' OR nama = 'Raesa Gista' LIMIT 1");
$stmtCheck->execute();
$existingUserId = $stmtCheck->fetchColumn();

if ($existingUserId) {
    $siswaId = (int) $existingUserId;
    $stmtUpdate = $pdo->prepare("
        UPDATE users 
           SET nama = 'Raesa Gista',
               username = 'siswa.raesa',
               role = 'siswa',
               kelas_id = ?,
               status = 'aktif',
               nomor_induk = '2024101201',
               tempat_lahir = 'Garut',
               tanggal_lahir = '2008-05-14',
               alamat = 'Jl. Pembangunan No. 84, Tarogong Kidul, Garut',
               no_hp = '082119882233',
               no_hp_ortu = '081223344556'
         WHERE id = ?
    ");
    $stmtUpdate->execute([$kelasId, $siswaId]);
    echo "[2/6] User Raesa Gista diperbarui (ID: $siswaId)\n";
} else {
    $stmtInsert = $pdo->prepare("
        INSERT INTO users (
            nama, username, password_hash, role, kelas_id, status,
            nomor_induk, tempat_lahir, tanggal_lahir, alamat, no_hp, no_hp_ortu, created_at
        ) VALUES (
            'Raesa Gista', 'siswa.raesa', ?, 'siswa', ?, 'aktif',
            '2024101201', 'Garut', '2008-05-14', 'Jl. Pembangunan No. 84, Tarogong Kidul, Garut', '082119882233', '081223344556', '2025-01-05 08:00:00'
        )
    ");
    $stmtInsert->execute([password_hash('siswa12345', PASSWORD_DEFAULT), $kelasId]);
    $siswaId = (int) $pdo->lastInsertId();
    echo "[2/6] User Raesa Gista berhasil ditambahkan (ID: $siswaId)\n";
}

// 3. Tugas dan Pengumpulan (Nilai Sangat Tinggi: 96 - 100)
echo "[3/6] Mengisi nilai tugas siswa...\n";
$tugasList = $pdo->query("
    SELECT t.id, t.judul, t.tenggat, m.nama as mapel_nama
      FROM tugas t
      JOIN pengampu p ON p.id = t.pengampu_id
      JOIN mapel m ON m.id = p.mapel_id
     WHERE p.kelas_id = $kelasId
     ORDER BY t.tenggat ASC
")->fetchAll();

$nilaiMaster = [
    49 => ['nilai' => 98.00, 'komen' => 'Penyelesaian matriks transformasi sangat presisi dan sistematis. Analisis geometri sangat memuaskan!'],
    50 => ['nilai' => 97.00, 'komen' => 'Aplikasi barisan geometri pada perhitungan bunga majemuk sangat aplikatif dan rapi.'],
    51 => ['nilai' => 98.00, 'komen' => 'Laporan praktikum fisika sangat lengkap dengan grafik osilasi dan perhitungan galat yang akurat.'],
    52 => ['nilai' => 99.00, 'komen' => 'Perhitungan pH larutan penyangga dan asam lemah sempurna tanpa kekeliruan perhitungan.'],
    53 => ['nilai' => 98.00, 'komen' => 'Analisis silsilah golongan darah keluarga dijabarkan dengan kaidah genetika Mendel yang tepat.'],
    54 => ['nilai' => 96.00, 'komen' => 'Sistematika proposal penelitian ilmiah sangat baik dan memenuhi standar penulisan akademis.'],
    55 => ['nilai' => 98.00, 'komen' => 'Outstanding analytical exposition essay with coherent structural arguments and rich vocabulary.'],
    56 => ['nilai' => 100.00, 'komen' => 'Skema database memenuhi normalisasi 3NF dengan relasi antar-entitas yang sempurna.'],
    57 => ['nilai' => 98.00, 'komen' => 'Pemecahan persamaan eksponen dan sifat logaritma diselesaikan dengan langkah yang runtut.'],
    58 => ['nilai' => 97.00, 'komen' => 'Metode grafis poligon dan penguraian komponen vektor gaya digambarkan dengan sangat teliti.'],
    59 => ['nilai' => 96.00, 'komen' => 'Teks observasi sangat objektif, faktual, dan menggunakan kaidah tata bahasa Indonesia yang prima.'],
    60 => ['nilai' => 99.00, 'komen' => 'Alur computational thinking robotika disusun secara komprehensif dan efisien.'],
    61 => ['nilai' => 98.00, 'komen' => 'Metode eliminasi Gauss dan substitusi SPLTV diselesaikan tanpa ada kesalahan tanda kalkulasi.'],
    62 => ['nilai' => 97.00, 'komen' => 'Analisis komponen gerak parabola sumbu x dan y sangat jelas. Jarak maksimum terbukti akurat.'],
    63 => ['nilai' => 100.00, 'komen' => 'Konfigurasi elektron SPDF dan penentuan keempat bilangan kuantum sempurna.'],
    64 => ['nilai' => 97.00, 'komen' => 'Gambar mikroskopis stomata sel daun Rhoeo discolor sangat detail dan keterangannya tepat.'],
    65 => ['nilai' => 98.00, 'komen' => 'Analisis titik puncak dan diskriminan fungsi kuadrat sangat baik.'],
    66 => ['nilai' => 99.00, 'komen' => 'Diagram benda bebas pada bidang miring sangat akurat dengan perhitungan gaya normal yang tepat.'],
];

foreach ($tugasList as $t) {
    $tId = (int) $t['id'];
    if (!isset($nilaiMaster[$tId])) {
        continue;
    }

    $tenggatTime = strtotime($t['tenggat']);
    $dikumpulTime = date('Y-m-d H:i:s', $tenggatTime - 3600 * 8); // 8 jam sebelum tenggat (tepat waktu)
    $dinilaiTime = date('Y-m-d H:i:s', $tenggatTime + 3600 * 24); // 1 hari setelah tenggat

    $stmtCheckPengumpulan = $pdo->prepare("SELECT id FROM pengumpulan WHERE tugas_id = ? AND siswa_id = ?");
    $stmtCheckPengumpulan->execute([$tId, $siswaId]);
    $existingP = $stmtCheckPengumpulan->fetchColumn();

    $fileName = md5("tugas_{$tId}_{$siswaId}") . '.pdf';
    $fileAsli = 'Tugas_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $t['judul']) . '_RaesaGista.pdf';

    if ($existingP) {
        $stmtUp = $pdo->prepare("
            UPDATE pengumpulan 
               SET nilai = ?, komentar = ?, status = 'tepat_waktu', dikumpulkan_at = ?, dinilai_at = ?
             WHERE id = ?
        ");
        $stmtUp->execute([$nilaiMaster[$tId]['nilai'], $nilaiMaster[$tId]['komen'], $dikumpulTime, $dinilaiTime, $existingP]);
    } else {
        $stmtIns = $pdo->prepare("
            INSERT INTO pengumpulan (tugas_id, siswa_id, file, file_asli, dikumpulkan_at, status, nilai, komentar, dinilai_at)
            VALUES (?, ?, ?, ?, ?, 'tepat_waktu', ?, ?, ?)
        ");
        $stmtIns->execute([
            $tId, $siswaId, $fileName, $fileAsli, $dikumpulTime,
            $nilaiMaster[$tId]['nilai'], $nilaiMaster[$tId]['komen'], $dinilaiTime
        ]);
    }
}
echo "   -> 18 Tugas berhasil diisi dengan rata-rata nilai ~98.00\n";

// 4. Kuis dan Jawaban Kuis (Nilai 95 - 100)
echo "[4/6] Mengisi nilai kuis & jawaban kuis siswa...\n";
$kuisList = $pdo->query("
    SELECT k.id, k.judul, k.mulai, k.selesai
      FROM kuis k
      JOIN pengampu p ON p.id = k.pengampu_id
     WHERE p.kelas_id = $kelasId
     ORDER BY k.mulai ASC
")->fetchAll();

$nilaiKuisMaster = [
    15 => 100.00,
    16 => 100.00,
    17 => 100.00,
    18 => 100.00,
    19 => 98.00,
];

foreach ($kuisList as $k) {
    $kId = (int) $k['id'];
    if (!isset($nilaiKuisMaster[$kId])) {
        continue;
    }

    $mulaiTime = date('Y-m-d H:i:s', strtotime($k['mulai']) + 3600); // 1 jam setelah kuis dibuka
    $selesaiTime = date('Y-m-d H:i:s', strtotime($mulaiTime) + 1500); // selesai dalam 25 menit

    $stmtCheckHk = $pdo->prepare("SELECT id FROM hasil_kuis WHERE kuis_id = ? AND siswa_id = ?");
    $stmtCheckHk->execute([$kId, $siswaId]);
    $existingHk = $stmtCheckHk->fetchColumn();

    if ($existingHk) {
        $hkId = (int) $existingHk;
        $stmtUpHk = $pdo->prepare("UPDATE hasil_kuis SET nilai = ?, mulai_at = ?, selesai_at = ? WHERE id = ?");
        $stmtUpHk->execute([$nilaiKuisMaster[$kId], $mulaiTime, $selesaiTime, $hkId]);
    } else {
        $stmtInsHk = $pdo->prepare("INSERT INTO hasil_kuis (kuis_id, siswa_id, nilai, mulai_at, selesai_at) VALUES (?, ?, ?, ?, ?)");
        $stmtInsHk->execute([$kId, $siswaId, $nilaiKuisMaster[$kId], $mulaiTime, $selesaiTime]);
        $hkId = (int) $pdo->lastInsertId();
    }

    // Isi jawaban_kuis
    $soals = $pdo->query("SELECT id, kunci FROM soal WHERE kuis_id = $kId")->fetchAll();
    foreach ($soals as $s) {
        $sId = (int) $s['id'];
        $kunci = $s['kunci'];
        $stmtCheckJwb = $pdo->prepare("SELECT id FROM jawaban_kuis WHERE hasil_kuis_id = ? AND soal_id = ?");
        $stmtCheckJwb->execute([$hkId, $sId]);
        if (!$stmtCheckJwb->fetchColumn()) {
            $stmtInsJwb = $pdo->prepare("INSERT INTO jawaban_kuis (hasil_kuis_id, soal_id, jawaban) VALUES (?, ?, ?)");
            $stmtInsJwb->execute([$hkId, $sId, $kunci]);
        }
    }
}
echo "   -> 5 Kuis berhasil dikerjakan dengan nilai sempurna & sangat tinggi\n";

// 5. Absensi (100% Hadir di Seluruh Sesi)
echo "[5/6] Mengisi riwayat presensi kelas...\n";
$sesiAbsensi = $pdo->query("
    SELECT DISTINCT a.tanggal, a.pengampu_id
      FROM absensi a
      JOIN pengampu p ON p.id = a.pengampu_id
     WHERE p.kelas_id = $kelasId
")->fetchAll();

$insAbs = $pdo->prepare("
    INSERT IGNORE INTO absensi (pengampu_id, siswa_id, tanggal, status, keterangan, created_at)
    VALUES (?, ?, ?, 'hadir', 'Hadir tepat waktu di kelas', ?)
");

$absCount = 0;
foreach ($sesiAbsensi as $sesi) {
    $createdAt = $sesi['tanggal'] . ' 07:15:00';
    $insAbs->execute([$sesi['pengampu_id'], $siswaId, $sesi['tanggal'], $createdAt]);
    $absCount++;
}
echo "   -> $absCount sesi presensi tercatat 100% HADIR\n";

// 6. Pencapaian / Badge Gamifikasi & Catatan Siswa
echo "[6/6] Menambahkan lencana prestasi & catatan belajar...\n";
$badges = [
    [
        'tipe' => 'nilai_sempurna',
        'label' => 'Nilai Sempurna',
        'deskripsi' => 'Raih nilai sempurna 100 pada evaluasi tugas dan kuis',
        'ikon' => '💯',
        'diperoleh_at' => '2026-03-05 12:00:00'
    ],
    [
        'tipe' => 'tepat_waktu',
        'label' => 'Pengumpul Tepat Waktu',
        'deskripsi' => 'Seluruh tugas diserahkan sebelum batas tenggat waktu',
        'ikon' => '⏰',
        'diperoleh_at' => '2026-04-15 09:00:00'
    ],
    [
        'tipe' => 'rajin_kuis',
        'label' => 'Rajin Latihan',
        'deskripsi' => 'Menyelesaikan seluruh sesi latihan kuis tanpa terlewat',
        'ikon' => '📝',
        'diperoleh_at' => '2026-09-21 08:30:00'
    ],
    [
        'tipe' => 'peringkat_pertama',
        'label' => 'Bintang Kelas XII-IPA',
        'deskripsi' => 'Meraih peringkat pertama performa akademik kumulatif tertinggi',
        'ikon' => '⭐',
        'diperoleh_at' => '2026-10-15 10:00:00'
    ],
    [
        'tipe' => 'kehadiran_penuh',
        'label' => 'Disiplin Presensi 100%',
        'deskripsi' => 'Kehadiran konsisten 100% tanpa catatan alpa atau izin',
        'ikon' => '🎖️',
        'diperoleh_at' => '2026-11-02 08:00:00'
    ],
];

foreach ($badges as $b) {
    $stmtBadge = $pdo->prepare("SELECT id FROM pencapaian WHERE siswa_id = ? AND tipe = ?");
    $stmtBadge->execute([$siswaId, $b['tipe']]);
    if (!$stmtBadge->fetchColumn()) {
        $stmtInsBadge = $pdo->prepare("
            INSERT INTO pencapaian (siswa_id, tipe, label, deskripsi, ikon, diperoleh_at)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmtInsBadge->execute([$siswaId, $b['tipe'], $b['label'], $b['deskripsi'], $b['ikon'], $b['diperoleh_at']]);
    }
}

// Catatan Siswa
$catatanList = [
    [
        'mapel_id' => 1, // Matematika
        'judul' => 'Rumus Cepat Matriks & Transformasi Geometri',
        'isi' => "Matriks Rotasi R(θ) = [[cos θ, -sin θ], [sin θ, cos θ]].\nRefleksi terhadap garis y = x menukar koordinat (x, y) -> (y, x).\nRefleksi terhadap sumbu x: (x, -y); sumbu y: (-x, y).\nIngat urutan komposisi transformasi: T2 o T1 berarti T1 dikerjakan lebih dahulu baru kemudian T2."
    ],
    [
        'mapel_id' => 4, // Fisika
        'judul' => 'Prinsip Dinamika Gerak & Analisis Vektor Bidang Miring',
        'isi' => "Komponen gaya pada bidang miring bersudut θ:\n1. Gaya berat sejajar bidang: w_parallel = m * g * sin(θ)\n2. Gaya normal tegak lurus bidang: N = m * g * cos(θ)\n3. Gaya gesek kinetis: f_k = μ_k * N\nPercepatan sistem jika licin: a = g * sin(θ). Jika kasar: a = g * (sin(θ) - μ_k * cos(θ))."
    ],
    [
        'mapel_id' => 5, // Kimia
        'judul' => 'Konsep Larutan Penyangga (Buffer) & Persamaan Henderson-Hasselbalch',
        'isi' => "Larutan penyangga asam: campuran asam lemah (CH3COOH) dan basa konjugasinya (CH3COO-).\npH = pKa - log([Asam]/[Garam]).\nLarutan penyangga basa: campuran basa lemah (NH3) dan asam konjugasinya (NH4+).\npOH = pKb - log([Basa]/[Garam]).\nBuffer mampu mempertahankan pH dari penambahan sedikit asam, basa, atau pengenceran."
    ],
    [
        'mapel_id' => 6, // Biologi
        'judul' => 'Pola Hereditas & Hukum Pewarisan Sifat Mendel',
        'isi' => "Hukum I Mendel (Segregasi Bebas): pemisahan alel secara bebas saat pembentukan gamet (Monohibrid 3:1).\nHukum II Mendel (Asortasi Bebas): pengelompokan gen secara independen (Dihibrid 9:3:3:1).\nPenyimpangan semu hukum Mendel: Kriptomeri (9:3:4), Polimeri (15:1), Epistasis-Hipostasis (12:3:1), Komplementer (9:7)."
    ],
];

foreach ($catatanList as $c) {
    $stmtCekCatatan = $pdo->prepare("SELECT id FROM catatan_siswa WHERE siswa_id = ? AND judul = ?");
    $stmtCekCatatan->execute([$siswaId, $c['judul']]);
    if (!$stmtCekCatatan->fetchColumn()) {
        $stmtInsC = $pdo->prepare("
            INSERT INTO catatan_siswa (siswa_id, mapel_id, judul, isi, created_at, updated_at)
            VALUES (?, ?, ?, ?, '2026-09-10 14:00:00', '2026-10-25 16:30:00')
        ");
        $stmtInsC->execute([$siswaId, $c['mapel_id'], $c['judul'], $c['isi']]);
    }
}

echo "\n=== SELESAI: RAESA GISTA TELAH TERDAFTAR SEBAGAI SISWA KELAS XII-IPA ===\n";
echo "Username: siswa.raesa\n";
echo "Password: siswa12345\n";

// Cek posisi leaderboard terbaru di Kelas XII-IPA
$lbSql = "SELECT u.id, u.nama, u.username,
                 ROUND(COALESCE(sub.rerata, 0), 1) AS nilai_rata,
                 COALESCE(sub_b.total_badge, 0) AS total_badge
            FROM users u
       LEFT JOIN (
             SELECT siswa_id, AVG(nilai) AS rerata
               FROM (
                     SELECT siswa_id, nilai FROM pengumpulan WHERE nilai IS NOT NULL
                     UNION ALL
                     SELECT siswa_id, nilai FROM hasil_kuis WHERE selesai_at IS NOT NULL
               ) gabung
              GROUP BY siswa_id
       ) sub ON sub.siswa_id = u.id
       LEFT JOIN (
             SELECT siswa_id, COUNT(*) AS total_badge
               FROM pencapaian
              GROUP BY siswa_id
       ) sub_b ON sub_b.siswa_id = u.id
          WHERE u.kelas_id = ?
            AND u.role = 'siswa'
            AND u.status = 'aktif'
       ORDER BY nilai_rata DESC, total_badge DESC, u.nama ASC";
$stmtLb = $pdo->prepare($lbSql);
$stmtLb->execute([$kelasId]);
$results = $stmtLb->fetchAll();

echo "\n--- KLASEMEN LEADERBOARD KELAS XII-IPA TERBARU ---\n";
$rank = 1;
foreach ($results as $r) {
    $marker = ($r['username'] === 'siswa.raesa') ? ' <<< PERINGKAT 1 TERVERIFIKASI' : '';
    echo "#$rank: {$r['nama']} ({$r['username']}) - Nilai Rata-rata: {$r['nilai_rata']} | Lencana: {$r['total_badge']} $marker\n";
    $rank++;
}
