<?php
/**
 * Seeder Data Pembelajaran LMS SMAN 10 Garut
 * Mensimulasikan pemakaian aktif selama 1,5 semester:
 * - Semester Genap 2025/2026 (5 Jan 2026 - 20 Jun 2026): Penuh, tuntas dinilai.
 * - Semester Ganjil 2026/2027 (13 Jul 2026 - 3 Nov 2026 s.d. Des 2026): Berjalan aktif s.d. hari ini (3 Nov 2026).
 *
 * Sifat: Idempoten (aman dijalankan berulang tanpa menduplikasi data).
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

echo "=== MEMULAI SEEDER 1,5 SEMESTER AKTIF LMS SMAN 10 GARUT ===\n";
echo "Simulasi Waktu Saat Ini: 3 November 2026 (Pertengahan Semester Ganjil)\n\n";

// Helper insert idempoten
function insertIfNotExists(PDO $pdo, string $table, array $uniqueCols, array $data): int {
    $whereParts = [];
    $whereVals = [];
    foreach ($uniqueCols as $col) {
        $whereParts[] = "`$col` = ?";
        $whereVals[] = $data[$col];
    }
    $checkSql = "SELECT id FROM `$table` WHERE " . implode(' AND ', $whereParts) . " LIMIT 1";
    $stmt = $pdo->prepare($checkSql);
    $stmt->execute($whereVals);
    $existing = $stmt->fetchColumn();
    if ($existing) {
        return (int) $existing;
    }

    $cols = array_keys($data);
    $placeholders = array_fill(0, count($cols), '?');
    $insertSql = "INSERT INTO `$table` (`" . implode('`, `', $cols) . "`) VALUES (" . implode(', ', $placeholders) . ")";
    $insStmt = $pdo->prepare($insertSql);
    $insStmt->execute(array_values($data));
    return (int) $pdo->lastInsertId();
}

// -------------------------------------------------------------
// 1. LENGKAPI PENGAMPU (GURU - KELAS - MAPEL)
// -------------------------------------------------------------
echo "[1/10] Melengkapi penugasan Guru Pengampu...\n";

// Daftar Mapel:
// 1: MTK, 2: BIND, 3: BING, 4: FIS, 5: KIM, 6: BIO, 7: SEJ, 8: INFO, 9: PPKN, 10: PJOK
// Guru:
// 2: Siti Rahmawati (MTK, BIO)
// 3: Budi Hartono (FIS, MTK, KIM)
// 4: Dewi Lestari (BIND, SEJ)
// 5: Agus Setiawan (INFO)
// 6: Rina Fitriani (BING)
// 7: Hendra Kusuma (PJOK, PPKN)

$pengampuTarget = [
    // Kelas 1 (X-1)
    ['guru_id' => 3, 'kelas_id' => 1, 'mapel_id' => 5], // Kimia
    ['guru_id' => 2, 'kelas_id' => 1, 'mapel_id' => 6], // Biologi
    ['guru_id' => 4, 'kelas_id' => 1, 'mapel_id' => 7], // Sejarah
    ['guru_id' => 7, 'kelas_id' => 1, 'mapel_id' => 9], // PPKn

    // Kelas 2 (X-2)
    ['guru_id' => 4, 'kelas_id' => 2, 'mapel_id' => 2], // B. Indonesia
    ['guru_id' => 6, 'kelas_id' => 2, 'mapel_id' => 3], // B. Inggris
    ['guru_id' => 3, 'kelas_id' => 2, 'mapel_id' => 4], // Fisika
    ['guru_id' => 3, 'kelas_id' => 2, 'mapel_id' => 5], // Kimia
    ['guru_id' => 2, 'kelas_id' => 2, 'mapel_id' => 6], // Biologi
    ['guru_id' => 4, 'kelas_id' => 2, 'mapel_id' => 7], // Sejarah
    ['guru_id' => 7, 'kelas_id' => 2, 'mapel_id' => 9], // PPKn
    ['guru_id' => 7, 'kelas_id' => 2, 'mapel_id' => 10], // PJOK

    // Kelas 3 (XI-IPA - Kelas Utama Siswa Putri Wulandari)
    ['guru_id' => 3, 'kelas_id' => 3, 'mapel_id' => 4], // Fisika
    ['guru_id' => 3, 'kelas_id' => 3, 'mapel_id' => 5], // Kimia
    ['guru_id' => 2, 'kelas_id' => 3, 'mapel_id' => 6], // Biologi
    ['guru_id' => 4, 'kelas_id' => 3, 'mapel_id' => 7], // Sejarah
    ['guru_id' => 7, 'kelas_id' => 3, 'mapel_id' => 9], // PPKn
    ['guru_id' => 7, 'kelas_id' => 3, 'mapel_id' => 10], // PJOK

    // Kelas 5 (XII-IPA)
    ['guru_id' => 6, 'kelas_id' => 5, 'mapel_id' => 3], // B. Inggris
    ['guru_id' => 5, 'kelas_id' => 5, 'mapel_id' => 8], // Informatika
    ['guru_id' => 3, 'kelas_id' => 5, 'mapel_id' => 5], // Kimia
    ['guru_id' => 2, 'kelas_id' => 5, 'mapel_id' => 6], // Biologi
    ['guru_id' => 4, 'kelas_id' => 5, 'mapel_id' => 7], // Sejarah
    ['guru_id' => 7, 'kelas_id' => 5, 'mapel_id' => 9], // PPKn
    ['guru_id' => 7, 'kelas_id' => 5, 'mapel_id' => 10], // PJOK
];

foreach ($pengampuTarget as $p) {
    insertIfNotExists($pdo, 'pengampu', ['kelas_id', 'mapel_id'], $p);
}

// Buat peta cepat: $pengampuMap[kelas_id][mapel_id] => pengampu_id
$pengampuMap = [];
$pengampuList = $pdo->query("SELECT id, guru_id, kelas_id, mapel_id FROM pengampu")->fetchAll();
foreach ($pengampuList as $row) {
    $pengampuMap[(int)$row['kelas_id']][(int)$row['mapel_id']] = (int)$row['id'];
}
echo "Pengampu siap. Total penugasan: " . count($pengampuList) . "\n";

// -------------------------------------------------------------
// 2. JADWAL PELAJARAN MINGGUAN LENGKAP
// -------------------------------------------------------------
echo "[2/10] Menyusun Jadwal Pelajaran Mingguan Lengkap...\n";

$jadwalData = [
    // XI-IPA (Kelas 3)
    ['kelas_id' => 3, 'mapel_id' => 2, 'hari' => 'senin', 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00', 'ruangan' => 'R. Teori 3'],
    ['kelas_id' => 3, 'mapel_id' => 1, 'hari' => 'senin', 'jam_mulai' => '08:30:00', 'jam_selesai' => '10:00:00', 'ruangan' => 'R. Teori 3'],
    ['kelas_id' => 3, 'mapel_id' => 9, 'hari' => 'senin', 'jam_mulai' => '10:15:00', 'jam_selesai' => '11:45:00', 'ruangan' => 'R. Teori 3'],

    ['kelas_id' => 3, 'mapel_id' => 3, 'hari' => 'selasa', 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00', 'ruangan' => 'R. Teori 3'],
    ['kelas_id' => 3, 'mapel_id' => 4, 'hari' => 'selasa', 'jam_mulai' => '08:30:00', 'jam_selesai' => '10:00:00', 'ruangan' => 'Lab Fisika'],
    ['kelas_id' => 3, 'mapel_id' => 5, 'hari' => 'selasa', 'jam_mulai' => '10:15:00', 'jam_selesai' => '11:45:00', 'ruangan' => 'Lab Kimia'],

    ['kelas_id' => 3, 'mapel_id' => 8, 'hari' => 'rabu', 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00', 'ruangan' => 'Lab Komputer 2'],
    ['kelas_id' => 3, 'mapel_id' => 6, 'hari' => 'rabu', 'jam_mulai' => '08:30:00', 'jam_selesai' => '10:00:00', 'ruangan' => 'Lab Biologi'],
    ['kelas_id' => 3, 'mapel_id' => 7, 'hari' => 'rabu', 'jam_mulai' => '10:15:00', 'jam_selesai' => '11:45:00', 'ruangan' => 'R. Teori 3'],

    ['kelas_id' => 3, 'mapel_id' => 1, 'hari' => 'kamis', 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00', 'ruangan' => 'R. Teori 3'],
    ['kelas_id' => 3, 'mapel_id' => 2, 'hari' => 'kamis', 'jam_mulai' => '08:30:00', 'jam_selesai' => '10:00:00', 'ruangan' => 'R. Teori 3'],
    ['kelas_id' => 3, 'mapel_id' => 4, 'hari' => 'kamis', 'jam_mulai' => '10:15:00', 'jam_selesai' => '11:45:00', 'ruangan' => 'Lab Fisika'],

    ['kelas_id' => 3, 'mapel_id' => 10, 'hari' => 'jumat', 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00', 'ruangan' => 'Lapangan Olahraga'],
    ['kelas_id' => 3, 'mapel_id' => 3, 'hari' => 'jumat', 'jam_mulai' => '08:30:00', 'jam_selesai' => '10:00:00', 'ruangan' => 'R. Teori 3'],

    ['kelas_id' => 3, 'mapel_id' => 5, 'hari' => 'sabtu', 'jam_mulai' => '07:30:00', 'jam_selesai' => '09:00:00', 'ruangan' => 'Lab Kimia'],
    ['kelas_id' => 3, 'mapel_id' => 6, 'hari' => 'sabtu', 'jam_mulai' => '09:15:00', 'jam_selesai' => '10:45:00', 'ruangan' => 'Lab Biologi'],

    // X-1 (Kelas 1) tambahan
    ['kelas_id' => 1, 'mapel_id' => 5, 'hari' => 'selasa', 'jam_mulai' => '10:15:00', 'jam_selesai' => '11:45:00', 'ruangan' => 'Lab Kimia'],
    ['kelas_id' => 1, 'mapel_id' => 6, 'hari' => 'kamis', 'jam_mulai' => '10:15:00', 'jam_selesai' => '11:45:00', 'ruangan' => 'Lab Biologi'],
    ['kelas_id' => 1, 'mapel_id' => 7, 'hari' => 'jumat', 'jam_mulai' => '08:30:00', 'jam_selesai' => '10:00:00', 'ruangan' => 'R. Teori 1'],
    ['kelas_id' => 1, 'mapel_id' => 9, 'hari' => 'sabtu', 'jam_mulai' => '08:30:00', 'jam_selesai' => '10:00:00', 'ruangan' => 'R. Teori 1'],

    // XII-IPA (Kelas 5) tambahan
    ['kelas_id' => 5, 'mapel_id' => 1, 'hari' => 'senin', 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00', 'ruangan' => 'R. Teori 5'],
    ['kelas_id' => 5, 'mapel_id' => 6, 'hari' => 'senin', 'jam_mulai' => '08:30:00', 'jam_selesai' => '10:00:00', 'ruangan' => 'Lab Biologi'],
    ['kelas_id' => 5, 'mapel_id' => 5, 'hari' => 'selasa', 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00', 'ruangan' => 'Lab Kimia'],
    ['kelas_id' => 5, 'mapel_id' => 3, 'hari' => 'rabu', 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00', 'ruangan' => 'R. Teori 5'],
    ['kelas_id' => 5, 'mapel_id' => 8, 'hari' => 'kamis', 'jam_mulai' => '08:30:00', 'jam_selesai' => '10:00:00', 'ruangan' => 'Lab Komputer 1'],
    ['kelas_id' => 5, 'mapel_id' => 10, 'hari' => 'sabtu', 'jam_mulai' => '07:00:00', 'jam_selesai' => '08:30:00', 'ruangan' => 'Lapangan Utama'],
];

foreach ($jadwalData as $j) {
    if (isset($pengampuMap[$j['kelas_id']][$j['mapel_id']])) {
        $pId = $pengampuMap[$j['kelas_id']][$j['mapel_id']];
        insertIfNotExists($pdo, 'jadwal', ['pengampu_id', 'hari', 'jam_mulai'], [
            'pengampu_id' => $pId,
            'hari' => $j['hari'],
            'jam_mulai' => $j['jam_mulai'],
            'jam_selesai' => $j['jam_selesai'],
            'ruangan' => $j['ruangan'],
        ]);
    }
}
echo "Jadwal mingguan diperbarui.\n";

// -------------------------------------------------------------
// 3. MATERI & VIDEO PEMBELAJARAN (1.5 SEMESTER)
// -------------------------------------------------------------
echo "[3/10] Mengisi Materi Pembelajaran & Video (1.5 Semester)...\n";

$samplePdf = '36ad90198debe9d5e3148f4b782a718a.pdf';
$pdfAlternatives = [
    '36ad90198debe9d5e3148f4b782a718a.pdf',
    'a7aaef0125ceff01034c613a0a2da1ad.pdf',
    'c4ca4238a0b923820dcc509a6f75849b.pdf',
    'c81e728d9d4c2f636f067f89cc14862c.pdf',
    'e10adc3949ba59abbe56e057f20f883e.pdf',
];

// Data materi untuk 1.5 semester
$materiDataset = [
    // --- SEMESTER GENAP 2025/2026 (Januari - Juni 2026) ---
    // MTK (Mapel 1)
    [
        'mapel_id' => 1,
        'judul' => 'Modul Semester Genap: Transformasi Geometri (Translasi, Refleksi, Rotasi)',
        'deskripsi' => 'Konsep matriks transformasi, pergeseran translasi, pencerminan refleksi terhadap garis dan titik, rotasi pusat O dan (a,b), serta dilatasi faktor skala k.',
        'file_asli' => 'Modul_MTK_Transformasi_Geometri.pdf',
        'created_at' => '2026-01-18 08:30:00'
    ],
    [
        'mapel_id' => 1,
        'judul' => 'Modul Semester Genap: Barisan dan Deret Geometri Tak Hingga',
        'deskripsi' => 'Pengenalan deret geometri konvergen dan divergen, rumus jumlah tak hingga S_inf = a/(1-r), aplikasi pantulan bola dan pertumbuhan populasi.',
        'file_asli' => 'Bahan_Ajar_Deret_Geometri_TakHingga.pdf',
        'created_at' => '2026-02-22 09:15:00'
    ],
    [
        'mapel_id' => 1,
        'judul' => 'Modul Semester Genap: Kaidah Pencacahan, Permutasi dan Kombinasi',
        'deskripsi' => 'Aturan penjumlahan dan perkalian, permutasi unsur berbeda, permutasi siklis, serta kombinasi pemilihan perwakilan dalam pemecahan masalah kontekstual.',
        'file_asli' => 'Modul_Pencacahan_dan_Kombinasi.pdf',
        'created_at' => '2026-04-12 10:00:00'
    ],

    // FISIKA (Mapel 4)
    [
        'mapel_id' => 4,
        'judul' => 'Modul Semester Genap: Getaran Harmonis Sederhana dan Gelombang Mekanik',
        'deskripsi' => 'Persamaan simpangan, kecepatan, dan percepatan pada bandul dan pegas. Karakteristik gelombang transversal dan longitudinal, frekuensi, periode, dan cepat rambat.',
        'file_asli' => 'Modul_Fisika_Gelombang_Mekanik.pdf',
        'created_at' => '2026-01-25 09:00:00'
    ],
    [
        'mapel_id' => 4,
        'judul' => 'Modul Semester Genap: Gelombang Bunyi dan Efek Doppler',
        'deskripsi' => 'Cepat rambat bunyi pada berbagai medium, intensitas dan taraf intensitas desibel, serta pergeseran frekuensi sumber dan pengamat bergerak (Efek Doppler).',
        'file_asli' => 'Buku_Ajar_Gelombang_Bunyi_Doppler.pdf',
        'created_at' => '2026-03-10 08:45:00'
    ],
    [
        'mapel_id' => 4,
        'judul' => 'Modul Semester Genap: Termodinamika dan Mesin Carnot',
        'deskripsi' => 'Hukum ke-0, I, dan II Termodinamika. Proses isotermal, isobarik, isokhorik, dan adiabatik. Perhitungan efisiensi siklus Carnot dan kerja sistem gas ideal.',
        'file_asli' => 'Termodinamika_Fisika_SMA.pdf',
        'created_at' => '2026-05-08 11:20:00'
    ],

    // KIMIA (Mapel 5)
    [
        'mapel_id' => 5,
        'judul' => 'Modul Semester Genap: Larutan Asam-Basa dan Pengukuran pH',
        'deskripsi' => 'Teori asam-basa Arrhenius, Bronsted-Lowry, dan Lewis. Perhitungan konsentrasi ion H+ dan OH-, derajat disosiasi, serta teknik titrasi asam kuat dan basa lemah.',
        'file_asli' => 'Modul_Kimia_Asam_Basa_pH.pdf',
        'created_at' => '2026-02-10 10:15:00'
    ],
    [
        'mapel_id' => 5,
        'judul' => 'Modul Semester Genap: Larutan Penyangga (Buffer) dan Hidrolisis Garam',
        'deskripsi' => 'Mekanisme kerja buffer fosfat dan karbonat dalam tubuh manusia, perhitungan pH sistem buffer asam/basa konjugasi, dan jenis garam terhidrolisis parsial/total.',
        'file_asli' => 'Bahan_Ajar_Buffer_Hidrolisis.pdf',
        'created_at' => '2026-04-05 08:30:00'
    ],

    // BIOLOGI (Mapel 6)
    [
        'mapel_id' => 6,
        'judul' => 'Modul Semester Genap: Sistem Sirkulasi dan Peredaran Darah Manusia',
        'deskripsi' => 'Struktur anatomi jantung, pembuluh darah arteri dan vena, komponen plasma dan eritrosit, penggolongan darah ABO dan Rhesus, serta kelainan kardiovaskular.',
        'file_asli' => 'Modul_Biologi_Sistem_Sirkulasi.pdf',
        'created_at' => '2026-02-14 09:30:00'
    ],
    [
        'mapel_id' => 6,
        'judul' => 'Modul Semester Genap: Sistem Respirasi dan Pertukaran Gas Alveolus',
        'deskripsi' => 'Mekanisme pernapasan dada dan perut, volume tidal dan kapasitas vital paru-paru, difusi O2 dan CO2 di alveolus, serta gangguan asma dan emfisema.',
        'file_asli' => 'Bahan_Ajar_Sistem_Pernapasan.pdf',
        'created_at' => '2026-03-22 08:15:00'
    ],

    // BAHASA INDONESIA (Mapel 2)
    [
        'mapel_id' => 2,
        'judul' => 'Modul Semester Genap: Sistematika Penulisan Karya Ilmiah dan Proposal',
        'deskripsi' => 'Kaidah kebahasaan karya ilmiah, penyusunan latar belakang masalah, perumusan hipotesis, metodologi penelitian, dan penulisan sitasi gaya APA/IEEE.',
        'file_asli' => 'Panduan_Penulisan_Karya_Ilmiah.pdf',
        'created_at' => '2026-02-05 13:00:00'
    ],
    [
        'mapel_id' => 2,
        'judul' => 'Modul Semester Genap: Menulis Resensi Buku Fiksi dan Non-Fiksi',
        'deskripsi' => 'Unsur intrinsik dan ekstrinsik, identitas buku, ikhtisar isi, keunggulan dan kelemahan buku, serta rekomendasi pembaca sasaran.',
        'file_asli' => 'Teknik_Menulis_Resensi_Buku.pdf',
        'created_at' => '2026-04-18 10:45:00'
    ],

    // BAHASA INGGRIS (Mapel 3)
    [
        'mapel_id' => 3,
        'judul' => 'Modul Semester Genap: Analytical Exposition Text & Argumentation',
        'deskripsi' => 'Thesis statement, arguments and evidence, reiteration, use of causal conjunctions, internal conjunctions, and evaluating persuasive essays.',
        'file_asli' => 'English_Analytical_Exposition.pdf',
        'created_at' => '2026-02-18 11:00:00'
    ],
    [
        'mapel_id' => 3,
        'judul' => 'Modul Semester Genap: Explanation Text (Natural and Social Phenomena)',
        'deskripsi' => 'General statement, sequenced explanation of why or how things occur, passive voice structures, technical terms, and hydrological cycle case studies.',
        'file_asli' => 'English_Explanation_Text_Guide.pdf',
        'created_at' => '2026-04-20 09:30:00'
    ],

    // INFORMATIKA (Mapel 8)
    [
        'mapel_id' => 8,
        'judul' => 'Modul Semester Genap: Desain Basis Data Relasional dan Perintah Dasar SQL',
        'deskripsi' => 'Entity Relationship Diagram (ERD), normalisasi 1NF-3NF, DDL (CREATE, ALTER, DROP), DML (SELECT, INSERT, UPDATE, DELETE), dan operasi JOIN antar tabel.',
        'file_asli' => 'Modul_Informatika_Database_SQL.pdf',
        'created_at' => '2026-03-01 08:00:00'
    ],
    [
        'mapel_id' => 8,
        'judul' => 'Modul Semester Genap: Algoritma Pencarian (Binary Search) dan Pengurutan (Sorting)',
        'deskripsi' => 'Analisis kompleksitas waktu Big-O, Bubble Sort, Merge Sort, Quick Sort, serta implementasi pencarian biner pada larik terurut.',
        'file_asli' => 'Algoritma_Searching_Sorting.pdf',
        'created_at' => '2026-04-28 13:15:00'
    ],

    // SEJARAH (Mapel 7)
    [
        'mapel_id' => 7,
        'judul' => 'Modul Semester Genap: Masa Pergerakan Nasional dan Organisasi Awal',
        'deskripsi' => 'Lahirnya Budi Utomo, Sarekat Islam, Indische Partij, Perhimpunan Indonesia, serta ikrar monumental Sumpah Pemuda 28 Oktober 1928.',
        'file_asli' => 'Modul_Sejarah_Pergerakan_Nasional.pdf',
        'created_at' => '2026-02-28 10:00:00'
    ],

    // PPKN (Mapel 9)
    [
        'mapel_id' => 9,
        'judul' => 'Modul Semester Genap: Wawasan Nusantara dan Ketahanan Nasional',
        'deskripsi' => 'Konsep geopolitik Indonesia, aspek Trigatra dan Pancagatra, ancaman militer dan non-militer, serta peran generasi muda dalam bela negara.',
        'file_asli' => 'Modul_PPKn_Wawasan_Nusantara.pdf',
        'created_at' => '2026-03-15 08:30:00'
    ],

    // PJOK (Mapel 10)
    [
        'mapel_id' => 10,
        'judul' => 'Modul Semester Genap: Kebugaran Jasmani dan Pola Hidup Sehat',
        'deskripsi' => 'Komponen daya tahan kardiovaskular, kekuatan otot, kelenturan, pengukuran VO2Max melalui tes bleep, serta manajemen nutrisi seimbang untuk remaja.',
        'file_asli' => 'Panduan_Kebugaran_Jasmani_SMA.pdf',
        'created_at' => '2026-03-20 07:30:00'
    ],

    // --- SEMESTER GANJIL 2026/2027 (Juli - November 2026, Aktif Berjalan) ---
    // MTK (Mapel 1)
    [
        'mapel_id' => 1,
        'judul' => 'Bab 1: Eksponen, Bentuk Akar, dan Logaritma',
        'deskripsi' => 'Sifat-sifat eksponen bulat dan rasional, operasi aljabar bentuk akar, rasionalisasi penyebut pecahan, definisi logaritma, dan persamaan logaritma sederhana.',
        'file_asli' => 'Modul_Bab1_Eksponen_Logaritma.pdf',
        'created_at' => '2026-07-20 08:00:00'
    ],
    [
        'mapel_id' => 1,
        'judul' => 'Bab 2: Sistem Persamaan Linear Tiga Variabel (SPLTV)',
        'deskripsi' => 'Penyelesaian SPLTV menggunakan metode eliminasi, substitusi, gabungan, serta metode determinan Cramer. Contoh pemodelan masalah belanja dan permodalan.',
        'file_asli' => 'Modul_Bab2_SPLTV_Kontekstual.pdf',
        'created_at' => '2026-08-25 09:30:00'
    ],
    [
        'mapel_id' => 1,
        'judul' => 'Bab 3: Fungsi Kuadrat dan Persamaan Kuadrat Lanjutan',
        'deskripsi' => 'Menentukan titik puncak, sumbu simetri, nilai diskriminan, jenis-jenis akar, serta grafik parabola dalam analisis lintasan proyektil.',
        'file_asli' => 'Modul_Bab3_Fungsi_Kuadrat.pdf',
        'created_at' => '2026-10-05 08:15:00'
    ],

    // FISIKA (Mapel 4)
    [
        'mapel_id' => 4,
        'judul' => 'Bab 1: Vektor dan Pengukuran Besaran Fisis',
        'deskripsi' => 'Penggunaan jangka sorong dan mikrometer sekrup, aturan angka penting, representasi vektor komponen sumbu x dan y, perkalian dot dan cross product.',
        'file_asli' => 'Modul_Bab1_Vektor_Pengukuran.pdf',
        'created_at' => '2026-07-22 09:00:00'
    ],
    [
        'mapel_id' => 4,
        'judul' => 'Bab 2: Kinematika Gerak Lurus (GLB dan GLBB)',
        'deskripsi' => 'Grafik posisi-waktu, kecepatan-waktu, percepatan konstan, gerak jatuh bebas, dan gerak vertikal ke atas dalam gravitasi bumi.',
        'file_asli' => 'Modul_Bab2_Kinematika_GLBB.pdf',
        'created_at' => '2026-08-28 10:15:00'
    ],
    [
        'mapel_id' => 4,
        'judul' => 'Bab 3: Dinamika Partikel dan Hukum-Hukum Gerak Newton',
        'deskripsi' => 'Gaya berat, gaya normal, gaya gesek statis dan kinetis, diagram gaya bebas pada bidang miring, dan tegangan tali pada sistem katrol ganda.',
        'file_asli' => 'Modul_Bab3_Hukum_Newton_Dinamika.pdf',
        'created_at' => '2026-10-12 08:45:00'
    ],

    // KIMIA (Mapel 5)
    [
        'mapel_id' => 5,
        'judul' => 'Bab 1: Struktur Atom dan Konfigurasi Elektron Berdasarkan Bilangan Kuantum',
        'deskripsi' => 'Prinsip Aufbau, aturan Hund, larangan Pauli. Konfigurasi gas mulia, penentuan letak unsur periode dan golongan dalam tabel periodik unsur modern.',
        'file_asli' => 'Modul_Bab1_Struktur_Atom_Kuantum.pdf',
        'created_at' => '2026-07-25 10:00:00'
    ],
    [
        'mapel_id' => 5,
        'judul' => 'Bab 2: Ikatan Kimia (Ion, Kovalen, dan Logam)',
        'deskripsi' => 'Struktur Lewis, kaidah oktet dan duplet, ikatan kovalen polar dan nonpolar, ikatan kovalen koordinasi, serta pengaruh ikatan terhadap titik leleh larutan.',
        'file_asli' => 'Modul_Bab2_Ikatan_Kimia.pdf',
        'created_at' => '2026-09-02 09:15:00'
    ],
    [
        'mapel_id' => 5,
        'judul' => 'Bab 3: Bentuk Molekul Berdasarkan Teori VSEPR dan Domain Elektron',
        'deskripsi' => 'Menentukan tipe molekul AXnEm, pasangan elektron ikatan (PEI) dan pasangan elektron bebas (PEB), serta bentuk molekul linear, segitiga datar, dan tetrahedral.',
        'file_asli' => 'Modul_Bab3_Bentuk_Molekul_VSEPR.pdf',
        'created_at' => '2026-10-15 11:00:00'
    ],

    // BIOLOGI (Mapel 6)
    [
        'mapel_id' => 6,
        'judul' => 'Bab 1: Struktur Sel Prokariotik dan Eukariotik Beserta Organelnya',
        'deskripsi' => 'Fungsi nukleus, retikulum endoplasma kasar dan halus, badan Golgi, mitokondria, kloroplas, lisosom, peroksisom, dan perbedaan sel hewan vs sel tumbuhan.',
        'file_asli' => 'Modul_Bab1_Struktur_Fungsi_Sel.pdf',
        'created_at' => '2026-07-28 08:30:00'
    ],
    [
        'mapel_id' => 6,
        'judul' => 'Bab 2: Transpor Membran (Difusi, Osmosis, dan Transpor Aktif)',
        'deskripsi' => 'Gradien konsentrasi, difusi sederhana dan terfasilitasi, osmosis pada sel tumbuhan (turgid dan plasmolisis) dan sel darah merah (krenasi dan lisis), pompa Na-K.',
        'file_asli' => 'Modul_Bab2_Transpor_Membran.pdf',
        'created_at' => '2026-09-08 08:15:00'
    ],
    [
        'mapel_id' => 6,
        'judul' => 'Bab 3: Enzim dan Reaksi Katabolisme Karbohidrat (Glikolisis & Siklus Krebs)',
        'deskripsi' => 'Sifat katalitik enzim, teori lock and key vs induced fit, faktor pH dan suhu, tahapan respirasi aerob: glikolisis, dekarboksilasi oksidatif, siklus Krebs, dan rantai transpor elektron.',
        'file_asli' => 'Modul_Bab3_Enzim_Katabolisme.pdf',
        'created_at' => '2026-10-18 10:30:00'
    ],

    // INFORMATIKA (Mapel 8)
    [
        'mapel_id' => 8,
        'judul' => 'Bab 1: Berpikir Komputasional dan Logika Dekomposisi Masalah',
        'deskripsi' => 'Pilar computational thinking: dekomposisi, pengenalan pola, abstraksi, dan algoritma. Penerapan pada perancangan sistem antrean cerdas.',
        'file_asli' => 'Modul_Bab1_Berpikir_Komputasional.pdf',
        'created_at' => '2026-07-24 13:00:00'
    ],
    [
        'mapel_id' => 8,
        'judul' => 'Bab 2: Topologi Jaringan Komputer dan Protokol TCP/IP',
        'deskripsi' => 'Model OSI 7 Layer, pengalamatan IPv4 dan subnetting CIDR /24, switch, router, konfigurasi DNS, serta pengamanan jaringan dasar.',
        'file_asli' => 'Modul_Bab2_Jaringan_Komputer.pdf',
        'created_at' => '2026-09-05 14:00:00'
    ],
    [
        'mapel_id' => 8,
        'judul' => 'Bab 3: Analisis Data dengan Spreadsheet dan Visualisasi Dashboard',
        'deskripsi' => 'Penggunaan fungsi VLOOKUP, INDEX-MATCH, Pivot Table, conditional formatting, dan pembuatan grafik tren dinamis untuk analisis data akademik.',
        'file_asli' => 'Modul_Bab3_Analisis_Data_Spreadsheet.pdf',
        'created_at' => '2026-10-22 13:30:00'
    ],
];

// Sebarkan materi ke kelas utama (XI-IPA, X-1, XII-IPA)
$classesToSeed = [3, 1, 5];
$pdfIdx = 0;
foreach ($classesToSeed as $kId) {
    foreach ($materiDataset as $m) {
        if (isset($pengampuMap[$kId][$m['mapel_id']])) {
            $pId = $pengampuMap[$kId][$m['mapel_id']];
            $chosenPdf = $pdfAlternatives[$pdfIdx % count($pdfAlternatives)];
            $pdfIdx++;

            insertIfNotExists($pdo, 'materi', ['pengampu_id', 'judul'], [
                'pengampu_id' => $pId,
                'judul' => $m['judul'],
                'deskripsi' => $m['deskripsi'],
                'file' => $chosenPdf,
                'file_asli' => $m['file_asli'],
                'created_at' => $m['created_at'],
            ]);
        }
    }
}

// VIDEO PEMBELAJARAN
$videoDataset = [
    ['mapel_id' => 1, 'judul' => 'Visualisasi Eksponen & Logaritma dalam Kehidupan Nyata', 'url_embed' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'durasi' => '16:42', 'created_at' => '2026-08-01 09:00:00'],
    ['mapel_id' => 4, 'judul' => 'Eksperimen Praktikum Kinematika GLBB Menggunakan Ticker Timer', 'url_embed' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'durasi' => '20:15', 'created_at' => '2026-08-20 10:30:00'],
    ['mapel_id' => 5, 'judul' => 'Simulasi Interaktif Bentuk Molekul 3D & Sudut Ikatan VSEPR', 'url_embed' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'durasi' => '18:50', 'created_at' => '2026-09-12 11:00:00'],
    ['mapel_id' => 6, 'judul' => 'Animasi Mekanisme Transpor Membran Sel: Difusi & Osmosis', 'url_embed' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'durasi' => '14:28', 'created_at' => '2026-09-15 08:30:00'],
    ['mapel_id' => 8, 'judul' => 'Panduan Desain Basis Data Relasional Normalisasi 1NF ke 3NF', 'url_embed' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'durasi' => '24:10', 'created_at' => '2026-10-02 14:00:00'],
    ['mapel_id' => 3, 'judul' => 'Mastering Analytical Exposition Essay Writing for Academic Debate', 'url_embed' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'durasi' => '19:35', 'created_at' => '2026-09-22 13:15:00'],
];

foreach ($classesToSeed as $kId) {
    foreach ($videoDataset as $v) {
        if (isset($pengampuMap[$kId][$v['mapel_id']])) {
            $pId = $pengampuMap[$kId][$v['mapel_id']];
            insertIfNotExists($pdo, 'video_pembelajaran', ['pengampu_id', 'judul'], [
                'pengampu_id' => $pId,
                'judul' => $v['judul'],
                'deskripsi' => 'Video suplemen pembelajaran interaktif untuk memperkuat pemahaman konsep materi secara visual dan kontekstual.',
                'tipe' => 'youtube',
                'url_embed' => $v['url_embed'],
                'durasi' => $v['durasi'],
                'created_at' => $v['created_at'],
            ]);
        }
    }
}
echo "Materi & Video selesai diisi.\n";

// -------------------------------------------------------------
// 4. TUGAS & PENGUMPULAN SISWA (1.5 SEMESTER)
// -------------------------------------------------------------
echo "[4/10] Menyusun Tugas & Pengumpulan Siswa (1.5 Semester)...\n";

// Siswa per kelas:
$siswaPerKelas = [];
$allSiswa = $pdo->query("SELECT id, nama, username, kelas_id FROM users WHERE role = 'siswa' AND status = 'aktif'")->fetchAll();
foreach ($allSiswa as $s) {
    $siswaPerKelas[(int)$s['kelas_id']][] = $s;
}

// Master daftar Tugas 1.5 Semester:
// Semester Genap: Past tasks (Feb - May 2026), 100% submitted & graded.
// Semester Ganjil:
// - Past 1 (Aug 2026): 100% submitted & graded.
// - Past 2 (Sep/Oct 2026): 100% submitted & graded.
// - Past 3 (Recent: due 1 Nov 2026): Submitted, sebagian belum dinilai (pending review guru).
// - Upcoming (due 10, 18, 25 Nov 2026): Aktif, siswa belum mengumpulkan (Tugas Menunggu).

$tugasDataset = [
    // --- SEMESTER GENAP 2025/2026 ---
    [
        'mapel_id' => 1,
        'judul' => 'Tugas Mandiri Semester Genap: Matriks Transformasi Geometri',
        'deskripsi' => 'Selesaikan 5 soal pemodelan rotasi dan refleksi objek pada koordinat kartesius. Tuliskan matriks penyusun dan tentukan koordinat bayangan akhir.',
        'tenggat' => '2026-02-28 23:59:00',
        'created_at' => '2026-02-14 08:00:00',
        'kategori' => 'genap_tuntas'
    ],
    [
        'mapel_id' => 1,
        'judul' => 'Tugas Proyek Semester Genap: Aplikasi Barisan Geometri pada Bunga Majemuk',
        'deskripsi' => 'Buat analisis komparasi pertumbuhan modal investasi tabungan menggunakan deret geometri dengan variasi suku bunga bank.',
        'tenggat' => '2026-04-15 23:59:00',
        'created_at' => '2026-03-25 09:00:00',
        'kategori' => 'genap_tuntas'
    ],
    [
        'mapel_id' => 4,
        'judul' => 'Laporan Praktikum Fisika Semester Genap: Getaran Bandul Sederhana',
        'deskripsi' => 'Kumpulkan laporan resmi hasil pengukuran percepatan gravitasi bumi g menggunakan variasi panjang tali bandul dan stopwatch.',
        'tenggat' => '2026-03-05 23:59:00',
        'created_at' => '2026-02-18 10:00:00',
        'kategori' => 'genap_tuntas'
    ],
    [
        'mapel_id' => 5,
        'judul' => 'Lembar Kerja Kimia Semester Genap: Perhitungan pH Larutan Asam Lemah',
        'deskripsi' => 'Kerjakan lembar kerja terstruktur perhitungan nilai Ka, derajat ionisasi alfa, dan pH asam asetat dalam larutan cuka dapur.',
        'tenggat' => '2026-03-20 23:59:00',
        'created_at' => '2026-03-02 08:30:00',
        'kategori' => 'genap_tuntas'
    ],
    [
        'mapel_id' => 6,
        'judul' => 'Tugas Biologi Semester Genap: Analisis Pola Golongan Darah Keluarga',
        'deskripsi' => 'Susun silsilah keluarga (pedigree chart) 3 generasi dan analisis kemungkinan genotipe sistem ABO dan rhesus masing-masing anggota keluarga.',
        'tenggat' => '2026-03-28 23:59:00',
        'created_at' => '2026-03-10 11:00:00',
        'kategori' => 'genap_tuntas'
    ],
    [
        'mapel_id' => 2,
        'judul' => 'Tugas Menulis Semester Genap: Proposal Penelitian Ilmiah Sederhana',
        'deskripsi' => 'Susun draf proposal penelitian dengan tema pelestarian lingkungan hidup sekolah. Format penulisan wajib mengikuti kaidah karya ilmiah baku.',
        'tenggat' => '2026-03-15 23:59:00',
        'created_at' => '2026-02-20 09:30:00',
        'kategori' => 'genap_tuntas'
    ],
    [
        'mapel_id' => 3,
        'judul' => 'English Writing Assignment: Analytical Exposition on Renewable Energy',
        'deskripsi' => 'Write an argumentative essay consisting of 300-500 words defending the urgency of solar energy transition in modern Indonesia.',
        'tenggat' => '2026-03-25 23:59:00',
        'created_at' => '2026-03-05 10:15:00',
        'kategori' => 'genap_tuntas'
    ],
    [
        'mapel_id' => 8,
        'judul' => 'Proyek Basis Data Semester Genap: Pembuatan Skema Database Perpustakaan',
        'deskripsi' => 'Rancang ERD dan buat script SQL DDL lengkap dengan tabel anggota, buku, peminjaman, dan foreign key constraints yang tepat.',
        'tenggat' => '2026-04-10 23:59:00',
        'created_at' => '2026-03-18 13:00:00',
        'kategori' => 'genap_tuntas'
    ],

    // --- SEMESTER GANJIL 2026/2027 (Berjalan) ---
    // Tugas Ganjil 1: Agustus 2026 (Tuntas dinilai)
    [
        'mapel_id' => 1,
        'judul' => 'Tugas 1 Ganjil: Pemecahan Masalah Eksponen dan Logaritma',
        'deskripsi' => 'Kerjakan 8 soal uraian penalaran eksponensial pada peluruhan zat radioaktif dan penghitungan skala Richter gempa bumi.',
        'tenggat' => '2026-08-20 23:59:00',
        'created_at' => '2026-08-05 08:00:00',
        'kategori' => 'ganjil_tuntas'
    ],
    [
        'mapel_id' => 4,
        'judul' => 'Tugas 1 Ganjil: Analisis Vektor Penjumlahan Gaya Metode Poligon',
        'deskripsi' => 'Gambarkan diagram resultan gaya menggunakan busur dan penggaris millimeter block, lalu bandingkan dengan perhitungan analitis rumus kosinus.',
        'tenggat' => '2026-08-22 23:59:00',
        'created_at' => '2026-08-08 09:00:00',
        'kategori' => 'ganjil_tuntas'
    ],
    [
        'mapel_id' => 2,
        'judul' => 'Tugas 1 Ganjil: Analisis Struktur Teks Laporan Hasil Observasi',
        'deskripsi' => 'Bacalah teks observasi terlampir, lalu identifikasi pernyataan umum, deskripsi bagian, deskripsi manfaat, serta kalimat definisi dan deskripsinya.',
        'tenggat' => '2026-08-25 23:59:00',
        'created_at' => '2026-08-10 10:30:00',
        'kategori' => 'ganjil_tuntas'
    ],
    [
        'mapel_id' => 8,
        'judul' => 'Tugas 1 Ganjil: Simulasi Alur Computational Thinking pada Robot Pemilah Sampah',
        'deskripsi' => 'Uraikan flowchart dan pseudocode pemilahan sampah organik dan anorganik dengan konsep percabangan IF-ELSE majemuk.',
        'tenggat' => '2026-08-28 23:59:00',
        'created_at' => '2026-08-12 13:00:00',
        'kategori' => 'ganjil_tuntas'
    ],

    // Tugas Ganjil 2: September / Awal Oktober 2026 (Tuntas dinilai)
    [
        'mapel_id' => 1,
        'judul' => 'Tugas 2 Ganjil: Pemodelan Matematika Sistem Persamaan Linear Tiga Variabel',
        'deskripsi' => 'Susun model matematika dari studi kasus pembelian paket perlengkapan laboratorium dan tentukan harga satuan tiap komponen.',
        'tenggat' => '2026-09-26 23:59:00',
        'created_at' => '2026-09-10 08:30:00',
        'kategori' => 'ganjil_tuntas'
    ],
    [
        'mapel_id' => 4,
        'judul' => 'Tugas 2 Ganjil: Laporan Analisis Gerak Parabola dan Jarak Maksimum',
        'deskripsi' => 'Kembangkan tabel prediksi sudut elevasi penembakan terhadap jarak terjauh dan tinggi maksimum menggunakan rumus analitis.',
        'tenggat' => '2026-10-02 23:59:00',
        'created_at' => '2026-09-15 09:45:00',
        'kategori' => 'ganjil_tuntas'
    ],
    [
        'mapel_id' => 5,
        'judul' => 'Tugas 2 Ganjil: Lembar Kerja Penentuan Konfigurasi Elektron dan Bilangan Kuantum',
        'deskripsi' => 'Tentukan keempat bilangan kuantum (n, l, m, s) untuk elektron terakhir pada 10 unsur transisi periode 4.',
        'tenggat' => '2026-10-08 23:59:00',
        'created_at' => '2026-09-20 10:15:00',
        'kategori' => 'ganjil_tuntas'
    ],
    [
        'mapel_id' => 6,
        'judul' => 'Tugas 2 Ganjil: Laporan Praktikum Pengamatan Sel Daun Rhoeo discolor',
        'deskripsi' => 'Dokumentasikan hasil mikroskop sel saat ditetesi akuades vs larutan garam pekat. Jelaskan peristiwa plasmolisis yang teramati.',
        'tenggat' => '2026-10-12 23:59:00',
        'created_at' => '2026-09-25 11:30:00',
        'kategori' => 'ganjil_tuntas'
    ],

    // Tugas Ganjil 3: Baru saja lewat (Tenggat 1-2 November 2026) -> Sebagian sudah dinilai, sebagian belum dinilai (Pending Review Guru!)
    [
        'mapel_id' => 1,
        'judul' => 'Tugas 3 Ganjil: Analisis Diskriminan dan Nilai Ekstrim Fungsi Kuadrat',
        'deskripsi' => 'Kaji lintasan proyektil roket air dan hitung tinggi maksimum roket serta waktu yang diperlukan hingga menyentuh tanah kembali.',
        'tenggat' => '2026-11-01 23:59:00',
        'created_at' => '2026-10-20 08:00:00',
        'kategori' => 'ganjil_pending_review'
    ],
    [
        'mapel_id' => 4,
        'judul' => 'Tugas 3 Ganjil: Analisis Gaya Kontak dan Gesekan pada Bidang Miring',
        'deskripsi' => 'Hitung percepatan benda bermassa ganda yang dihubungkan tali melalui katrol licin di atas bidang miring kasar bersudut 37 derajat.',
        'tenggat' => '2026-11-02 23:59:00',
        'created_at' => '2026-10-22 09:30:00',
        'kategori' => 'ganjil_pending_review'
    ],

    // Tugas Ganjil 4: MENDATANG (Tenggat 10, 18, 25 November 2026) -> Belum dikumpulkan siswa Putri Wulandari (Tugas Menunggu di Dashboard!)
    [
        'mapel_id' => 1,
        'judul' => 'Tugas 4 Ganjil: Proyek Pemodelan Masalah Optimasi Biaya Produksi',
        'deskripsi' => 'Gunakan konsep fungsi kuadrat untuk mencari keuntungan maksimum dan biaya minimum dari data operasional UMKM konveksi lokal.',
        'tenggat' => '2026-11-12 23:59:00',
        'created_at' => '2026-10-28 08:30:00',
        'kategori' => 'ganjil_mendatang'
    ],
    [
        'mapel_id' => 3,
        'judul' => 'Tugas 4 Ganjil: Essay on Artificial Intelligence Ethics in Education',
        'deskripsi' => 'Submit your final analytical exposition draft on the ethical considerations of using AI for high school student homework.',
        'tenggat' => '2026-11-18 23:59:00',
        'created_at' => '2026-10-30 10:00:00',
        'kategori' => 'ganjil_mendatang'
    ],
    [
        'mapel_id' => 8,
        'judul' => 'Tugas 4 Ganjil: Analisis Dataset Prestasi Siswa Menggunakan Pivot Table',
        'deskripsi' => 'Unduh dataset spreadsheet terlampir, buat rekapitulasi nilai rata-rata per mata pelajaran menggunakan fungsi INDEX-MATCH dan Pivot Table.',
        'tenggat' => '2026-11-25 23:59:00',
        'created_at' => '2026-11-01 13:00:00',
        'kategori' => 'ganjil_mendatang'
    ],
];

// Feedback guru realistis
$komentarGuru = [
    95 => "Luar biasa! Penyelesaian sangat terstruktur, rapi, dan semua langkah analisis matematis dijabarkan secara runut.",
    92 => "Pekerjaan sangat baik! Pemahaman konsep kuat dan grafik/diagram pendukung dibuat dengan teliti.",
    88 => "Bagus sekali! Seluruh instruksi terpenuhi dengan baik. Pertahankan ketelitian saat menyederhanakan persamaan akhir.",
    85 => "Sudah cukup baik dan lengkap. Perhatikan sedikit kerapian penulisan simbol dan satuan pada hasil akhir.",
    80 => "Pekerjaan baik, namun pada soal nomor 4 perlu penjelasan argumentasi yang lebih mendalam.",
    78 => "Cukup baik. Mohon pelajari kembali cara merasionalkan pembagi pada langkah ketiga agar hasil lebih presisi.",
    75 => "Tugas sudah dikerjakan dengan usaha yang baik. Silakan cek catatan koreksi di lembar evaluasi untuk perbaikan ke depan.",
    100 => "Sempurna! Pekerjaan tanpa cela, orisinalitas tinggi dan penjelasan melebihi ekspektasi standar tugas.",
];

$tugasCount = 0;
$pengumpulanCount = 0;

foreach ($classesToSeed as $kId) {
    $studentsInClass = $siswaPerKelas[$kId] ?? [];
    if (empty($studentsInClass)) continue;

    foreach ($tugasDataset as $tDef) {
        if (!isset($pengampuMap[$kId][$tDef['mapel_id']])) continue;
        $pId = $pengampuMap[$kId][$tDef['mapel_id']];

        $tugasId = insertIfNotExists($pdo, 'tugas', ['pengampu_id', 'judul'], [
            'pengampu_id' => $pId,
            'judul' => $tDef['judul'],
            'deskripsi' => $tDef['deskripsi'],
            'lampiran' => $samplePdf,
            'lampiran_asli' => 'Petunjuk_Pengerjaan_Tugas.pdf',
            'tenggat' => $tDef['tenggat'],
            'created_at' => $tDef['created_at'],
        ]);
        $tugasCount++;

        // Kelola Pengumpulan sesuai kategori
        if ($tDef['kategori'] === 'genap_tuntas' || $tDef['kategori'] === 'ganjil_tuntas') {
            // Semua siswa mengumpulkan dan sudah dinilai
            foreach ($studentsInClass as $sIdx => $s) {
                $subFile = $pdfAlternatives[($s['id'] + $tugasId) % count($pdfAlternatives)];
                $subTime = date('Y-m-d H:i:s', strtotime($tDef['tenggat']) - (rand(12, 96) * 3600));

                // Nilai realistis: Siswa Putri Wulandari (ID 30) selalu dapat 88-100
                if ($s['id'] == 30) {
                    $grade = (rand(0, 4) === 0) ? 100.00 : (float) rand(88, 98);
                } else {
                    $gradePossibilities = [75, 78, 80, 82, 85, 88, 90, 92, 95];
                    $grade = (float) $gradePossibilities[($s['id'] + $tugasId) % count($gradePossibilities)];
                }

                $feedbackKey = (int) $grade;
                $feedback = $komentarGuru[$feedbackKey] ?? "Pekerjaan rapi dan memuaskan. Pertahankan prestasimu!";
                $dinilaiAt = date('Y-m-d H:i:s', strtotime($subTime) + (rand(24, 72) * 3600));

                insertIfNotExists($pdo, 'pengumpulan', ['tugas_id', 'siswa_id'], [
                    'tugas_id' => $tugasId,
                    'siswa_id' => (int) $s['id'],
                    'file' => $subFile,
                    'file_asli' => 'Tugas_' . preg_replace('/[^a-zA-Z0-9]/', '_', $tDef['judul']) . '_' . $s['username'] . '.pdf',
                    'dikumpulkan_at' => $subTime,
                    'status' => 'tepat_waktu',
                    'nilai' => $grade,
                    'komentar' => $feedback,
                    'dinilai_at' => $dinilaiAt,
                ]);
                $pengumpulanCount++;
            }
        } elseif ($tDef['kategori'] === 'ganjil_pending_review') {
            // Siswa mengumpulkan, tapi sebagian belum dinilai (guru butuh menilai!)
            foreach ($studentsInClass as $sIdx => $s) {
                $subFile = $pdfAlternatives[($s['id'] + $tugasId) % count($pdfAlternatives)];
                $subTime = date('Y-m-d H:i:s', strtotime($tDef['tenggat']) - (rand(2, 24) * 3600));

                // Setengah pertama sudah dinilai, setengah kedua BELUM dinilai (dinilai_at = null, nilai = null)
                $isGraded = ($sIdx % 2 === 0);
                $grade = $isGraded ? ((float) rand(85, 95)) : null;
                $dinilaiAt = $isGraded ? date('Y-m-d H:i:s', strtotime($subTime) + (rand(12, 36) * 3600)) : null;
                $komentar = $isGraded ? "Tugas telah diperiksa oleh guru." : null;

                insertIfNotExists($pdo, 'pengumpulan', ['tugas_id', 'siswa_id'], [
                    'tugas_id' => $tugasId,
                    'siswa_id' => (int) $s['id'],
                    'file' => $subFile,
                    'file_asli' => 'Tugas_' . preg_replace('/[^a-zA-Z0-9]/', '_', $tDef['judul']) . '_' . $s['username'] . '.pdf',
                    'dikumpulkan_at' => $subTime,
                    'status' => 'tepat_waktu',
                    'nilai' => $grade,
                    'komentar' => $komentar,
                    'dinilai_at' => $dinilaiAt,
                ]);
                $pengumpulanCount++;
            }
        } elseif ($tDef['kategori'] === 'ganjil_mendatang') {
            // Tugas MENDATANG: Sebagian siswa lain mungkin sudah kumpul duluan, tapi Putri Wulandari (ID 30) BELUM mengumpulkan
            // agar muncul di dashboardnya sebagai "Tugas Menunggu"
            foreach ($studentsInClass as $sIdx => $s) {
                if ($s['id'] == 30) {
                    // JANGAN masukkan pengumpulan untuk Putri Wulandari!
                    continue;
                }
                // Hanya 1-2 siswa rajin lain yang mengumpulkan lebih awal
                if ($sIdx < 2) {
                    $subFile = $pdfAlternatives[($s['id'] + $tugasId) % count($pdfAlternatives)];
                    $subTime = '2026-11-02 16:30:00';
                    insertIfNotExists($pdo, 'pengumpulan', ['tugas_id', 'siswa_id'], [
                        'tugas_id' => $tugasId,
                        'siswa_id' => (int) $s['id'],
                        'file' => $subFile,
                        'file_asli' => 'Draft_Awal_' . $s['username'] . '.pdf',
                        'dikumpulkan_at' => $subTime,
                        'status' => 'tepat_waktu',
                        'nilai' => null,
                        'komentar' => null,
                        'dinilai_at' => null,
                    ]);
                    $pengumpulanCount++;
                }
            }
        }
    }
}
echo "Tugas & Pengumpulan selesai diisi ($pengumpulanCount pengumpulan tercatat).\n";

// -------------------------------------------------------------
// 5. KUIS, SOAL, HASIL KUIS & JAWABAN KUIS (1.5 SEMESTER)
// -------------------------------------------------------------
echo "[5/10] Menyusun Kuis, Soal, Hasil Kuis & Jawaban (1.5 Semester)...\n";

$kuisDataset = [
    // --- SEMESTER GENAP 2025/2026 (Selesai, Bersejarah) ---
    [
        'mapel_id' => 1,
        'judul' => 'Kuis Genap: Transformasi Geometri dan Matriks',
        'durasi_menit' => 30,
        'mulai' => '2026-03-04 07:30:00',
        'selesai' => '2026-03-08 23:59:59',
        'created_at' => '2026-03-01 08:00:00',
        'kategori' => 'tuntas',
        'soal' => [
            ['p' => 'Titik A(3, -2) ditranslasikan oleh T = (2, 5). Koordinat bayangan titik A adalah ...', 'a' => '(5, 3)', 'b' => '(1, 7)', 'c' => '(5, -7)', 'd' => '(-1, 3)', 'e' => '(6, -10)', 'k' => 'a'],
            ['p' => 'Bayangan titik P(4, 7) jika dicerminkan terhadap sumbu X adalah ...', 'a' => '(-4, 7)', 'b' => '(4, -7)', 'c' => '(-4, -7)', 'd' => '(7, 4)', 'e' => '(-7, -4)', 'k' => 'b'],
            ['p' => 'Matriks yang bersesuaian dengan rotasi [O, 90°] berlawanan arah jarum jam adalah ...', 'a' => '[0 -1; 1 0]', 'b' => '[0 1; -1 0]', 'c' => '[1 0; 0 1]', 'd' => '[-1 0; 0 -1]', 'e' => '[0 1; 1 0]', 'k' => 'a'],
            ['p' => 'Titik B(2, -3) didilatasi dengan pusat (0,0) dan faktor skala k = 3. Bayangannya adalah ...', 'a' => '(6, -9)', 'b' => '(5, 0)', 'c' => '(-6, 9)', 'd' => '(6, 9)', 'e' => '(2/3, -1)', 'k' => 'a'],
            ['p' => 'Jika garis 2x + y = 4 ditranslasi T(1, 2), maka persamaan garis bayangannya adalah ...', 'a' => '2x + y = 8', 'b' => '2x + y = 6', 'c' => '2x + y = 4', 'd' => '2x - y = 8', 'e' => 'x + 2y = 8', 'k' => 'a'],
        ]
    ],
    [
        'mapel_id' => 4,
        'judul' => 'Kuis Genap: Gelombang Mekanik dan Bunyi',
        'durasi_menit' => 30,
        'mulai' => '2026-03-18 08:00:00',
        'selesai' => '2026-03-22 23:59:59',
        'created_at' => '2026-03-15 08:00:00',
        'kategori' => 'tuntas',
        'soal' => [
            ['p' => 'Gelombang yang arah getarnya tegak lurus terhadap arah rambatnya disebut gelombang ...', 'a' => 'Transversal', 'b' => 'Longitudinal', 'c' => 'Elektromagnetik', 'd' => 'Stasioner', 'e' => 'Bunyi', 'k' => 'a'],
            ['p' => 'Sebuah sumber bunyi berfrekuensi 680 Hz merambat di udara dengan laju 340 m/s. Panjang gelombangnya adalah ...', 'a' => '0.5 m', 'b' => '2 m', 'c' => '0.25 m', 'd' => '1 m', 'e' => '4 m', 'k' => 'a'],
            ['p' => 'Peristiwa penguatan bunyi akibat bergetarnya benda lain dengan frekuensi alami sama disebut ...', 'a' => 'Resonansi', 'b' => 'Difraksi', 'c' => 'Refraksi', 'd' => 'Polarisasi', 'e' => 'Interferensi', 'k' => 'a'],
            ['p' => 'Jika taraf intensitas sebuah sepeda motor adalah 60 dB, maka taraf intensitas 10 sepeda motor identik adalah ...', 'a' => '70 dB', 'b' => '600 dB', 'c' => '80 dB', 'd' => '65 dB', 'e' => '120 dB', 'k' => 'a'],
            ['p' => 'Sirine ambulans mendekati pengamat diam. Frekuensi yang didengar pengamat akan ...', 'a' => 'Lebih tinggi dari frekuensi sumber', 'b' => 'Lebih rendah dari frekuensi sumber', 'c' => 'Sama dengan frekuensi sumber', 'd' => 'Nol', 'e' => 'Hilang timbul', 'k' => 'a'],
        ]
    ],

    // --- SEMESTER GANJIL 2026/2027 (Berjalan) ---
    // Kuis 1: Agustus 2026 (Tuntas)
    [
        'mapel_id' => 1,
        'judul' => 'Kuis 1 Ganjil: Eksponen dan Operasi Logaritma',
        'durasi_menit' => 25,
        'mulai' => '2026-08-18 07:00:00',
        'selesai' => '2026-08-25 23:59:59',
        'created_at' => '2026-08-15 08:00:00',
        'kategori' => 'tuntas',
        'soal' => [
            ['p' => 'Bentuk sederhana dari (3³ × 3⁴) ÷ 3² adalah ...', 'a' => '3⁵', 'b' => '3⁶', 'c' => '3⁷', 'd' => '3⁹', 'e' => '3⁴', 'k' => 'a'],
            ['p' => 'Nilai dari ²log 32 adalah ...', 'a' => '5', 'b' => '4', 'c' => '6', 'd' => '8', 'e' => '16', 'k' => 'a'],
            ['p' => 'Jika ³log 2 = a, maka ³log 8 sama dengan ...', 'a' => '3a', 'b' => '2a', 'c' => 'a + 3', 'd' => 'a³', 'e' => 'a/3', 'k' => 'a'],
            ['p' => 'Bentuk rasional dari 6 / √3 adalah ...', 'a' => '2√3', 'b' => '3√2', 'c' => '6√3', 'd' => '√3', 'e' => '3√3', 'k' => 'a'],
            ['p' => 'Penyelesaian dari 2^(2x-1) = 32 adalah ...', 'a' => 'x = 3', 'b' => 'x = 2', 'c' => 'x = 4', 'd' => 'x = 5', 'e' => 'x = 1', 'k' => 'a'],
        ]
    ],
    // Kuis 2: September 2026 (Tuntas)
    [
        'mapel_id' => 8,
        'judul' => 'Kuis 2 Ganjil: Arsitektur Jaringan dan Subnetting IP',
        'durasi_menit' => 30,
        'mulai' => '2026-09-20 07:30:00',
        'selesai' => '2026-09-27 23:59:59',
        'created_at' => '2026-09-17 08:00:00',
        'kategori' => 'tuntas',
        'soal' => [
            ['p' => 'Lapisan OSI yang bertanggung jawab untuk pengalamatan logis (IP address) dan routing paket adalah ...', 'a' => 'Network Layer', 'b' => 'Transport Layer', 'c' => 'Data Link Layer', 'd' => 'Session Layer', 'e' => 'Application Layer', 'k' => 'a'],
            ['p' => 'Subnet mask standar untuk jaringan kelas C dengan prefix /24 adalah ...', 'a' => '255.255.255.0', 'b' => '255.255.0.0', 'c' => '255.0.0.0', 'd' => '255.255.255.128', 'e' => '255.255.255.255', 'k' => 'a'],
            ['p' => 'Protokol jaringan yang bertugas menerjemahkan nama domain (seperti google.com) menjadi IP address adalah ...', 'a' => 'DNS', 'b' => 'DHCP', 'c' => 'HTTP', 'd' => 'FTP', 'e' => 'SMTP', 'k' => 'a'],
            ['p' => 'Perangkat jaringan yang berfungsi menghubungkan dua atau lebih jaringan dengan segmen/subnet berbeda adalah ...', 'a' => 'Router', 'b' => 'Switch', 'c' => 'Hub', 'd' => 'Repeater', 'e' => 'Bridge', 'k' => 'a'],
            ['p' => 'Jumlah host yang dapat digunakan pada jaringan ber-subnet mask /29 adalah ...', 'a' => '6', 'b' => '8', 'c' => '14', 'd' => '16', 'e' => '30', 'k' => 'a'],
        ]
    ],
    // Kuis 3: SEDANG DIBUKA SEKARANG! (1 Nov - 10 Nov 2026) -> Menjadikan Kuis Aktif di Siswa
    [
        'mapel_id' => 1,
        'judul' => 'Kuis Terbuka: Evaluasi Sistem Persamaan Linear & Fungsi Kuadrat',
        'durasi_menit' => 35,
        'mulai' => '2026-11-01 07:00:00',
        'selesai' => '2026-11-10 23:59:59',
        'created_at' => '2026-10-31 08:00:00',
        'kategori' => 'sedang_dibuka',
        'soal' => [
            ['p' => 'Sumbu simetri dari grafik fungsi kuadrat f(x) = x² - 6x + 8 adalah ...', 'a' => 'x = 3', 'b' => 'x = -3', 'c' => 'x = 6', 'd' => 'x = 4', 'e' => 'x = 2', 'k' => 'a'],
            ['p' => 'Nilai optimum (minimum) dari fungsi kuadrat f(x) = x² - 6x + 8 adalah ...', 'a' => '-1', 'b' => '1', 'c' => '-8', 'd' => '8', 'e' => '0', 'k' => 'a'],
            ['p' => 'Jika suatu fungsi kuadrat memiliki D > 0, maka kurva parabola akan ...', 'a' => 'Memotong sumbu X di dua titik berbeda', 'b' => 'Menyinggung sumbu X di satu titik', 'c' => 'Tidak memotong sumbu X sama sekali', 'd' => 'Selalu berada di bawah sumbu X', 'e' => 'Berupa garis lurus vertikal', 'k' => 'a'],
            ['p' => 'Himpunan penyelesaian dari SPLTV dengan x + y + z = 6, 2x - y + z = 3, x - 2y + 3z = 6 memiliki nilai x = ...', 'a' => '1', 'b' => '2', 'c' => '3', 'd' => '4', 'e' => '0', 'k' => 'a'],
            ['p' => 'Koordinat titik potong grafik f(x) = 2x² - 4x - 6 terhadap sumbu Y adalah ...', 'a' => '(0, -6)', 'b' => '(0, 6)', 'c' => '(-6, 0)', 'd' => '(3, 0)', 'e' => '(-1, 0)', 'k' => 'a'],
        ]
    ],
    // Kuis 4: TERJADWAL MENDATANG (16 Nov - 23 Nov 2026) -> Belum Dibuka
    [
        'mapel_id' => 4,
        'judul' => 'Kuis Terjadwal: Dinamika Hukum Newton dan Gerak Melingkar Beraturan',
        'durasi_menit' => 30,
        'mulai' => '2026-11-16 07:00:00',
        'selesai' => '2026-11-23 23:59:59',
        'created_at' => '2026-11-02 08:00:00',
        'kategori' => 'belum_dibuka',
        'soal' => [
            ['p' => 'Gaya sentripetal pada benda yang bergerak melingkar beraturan selalu mengarah ke ...', 'a' => 'Pusat lingkaran', 'b' => 'Menjauhi pusat lingkaran', 'c' => 'Arah garis singgung lintasan', 'd' => 'Searah kecepatan sudut', 'e' => 'Ke bawah mengikuti gravitasi', 'k' => 'a'],
            ['p' => 'Jika jari-jari putaran digandakan dan laju linier tetap, maka percepatan sentripetal akan menjadi ...', 'a' => 'Setengah kali semula', 'b' => 'Dua kali semula', 'c' => 'Empat kali semula', 'd' => 'Seperempat kali semula', 'e' => 'Tetap sama', 'k' => 'a'],
            ['p' => 'Koefisien gesekan statis selalu ...', 'a' => 'Lebih besar atau sama dengan koefisien gesekan kinetis', 'b' => 'Lebih kecil dari koefisien gesekan kinetis', 'c' => 'Selalu sama dengan nol', 'd' => 'Selalu lebih besar dari 1', 'e' => 'Memiliki satuan Newton', 'k' => 'a'],
            ['p' => 'Besar gaya gravitasi antara dua benda bermassa m1 dan m2 yang berjarak r berbanding terbalik dengan ...', 'a' => 'Kuadrat jarak (r²)', 'b' => 'Jarak (r)', 'c' => 'Massa benda pertama', 'd' => 'Konstanta gravitasi G', 'e' => 'Akar jarak (√r)', 'k' => 'a'],
            ['p' => 'Gaya normal pada benda diam di bidang datar bermassa 5 kg (g = 10 m/s²) adalah ...', 'a' => '50 N', 'b' => '5 N', 'c' => '0.5 N', 'd' => '500 N', 'e' => '0 N', 'k' => 'a'],
        ]
    ],
];

$kuisCount = 0;
$hasilKuisCount = 0;

foreach ($classesToSeed as $kId) {
    $studentsInClass = $siswaPerKelas[$kId] ?? [];
    if (empty($studentsInClass)) continue;

    foreach ($kuisDataset as $qDef) {
        if (!isset($pengampuMap[$kId][$qDef['mapel_id']])) continue;
        $pId = $pengampuMap[$kId][$qDef['mapel_id']];

        $kuisId = insertIfNotExists($pdo, 'kuis', ['pengampu_id', 'judul'], [
            'pengampu_id' => $pId,
            'judul' => $qDef['judul'],
            'durasi_menit' => $qDef['durasi_menit'],
            'mulai' => $qDef['mulai'],
            'selesai' => $qDef['selesai'],
            'created_at' => $qDef['created_at'],
        ]);
        $kuisCount++;

        // Masukkan Soal
        $soalIds = [];
        foreach ($qDef['soal'] as $sData) {
            $sId = insertIfNotExists($pdo, 'soal', ['kuis_id', 'pertanyaan'], [
                'kuis_id' => $kuisId,
                'pertanyaan' => $sData['p'],
                'opsi_a' => $sData['a'],
                'opsi_b' => $sData['b'],
                'opsi_c' => $sData['c'],
                'opsi_d' => $sData['d'],
                'opsi_e' => $sData['e'],
                'kunci' => $sData['k'],
            ]);
            $soalIds[] = ['id' => $sId, 'kunci' => $sData['k']];
        }

        // Kelola Hasil Kuis
        if ($qDef['kategori'] === 'tuntas') {
            // Semua siswa sudah mengerjakan dan selesai
            foreach ($studentsInClass as $sIdx => $s) {
                $mulaiAt = date('Y-m-d H:i:s', strtotime($qDef['mulai']) + (rand(4, 48) * 3600));
                $selesaiAt = date('Y-m-d H:i:s', strtotime($mulaiAt) + (rand(15, 28) * 60));

                // Hitung skor bervariasi:
                // Putri Wulandari selalu dapat 100 atau 80
                $benarCount = ($s['id'] == 30) ? ((rand(0, 3) === 0) ? 5 : 4) : rand(3, 5);
                $skorKuis = (float) ($benarCount * 20.0);

                $hasilId = insertIfNotExists($pdo, 'hasil_kuis', ['kuis_id', 'siswa_id'], [
                    'kuis_id' => $kuisId,
                    'siswa_id' => (int) $s['id'],
                    'nilai' => $skorKuis,
                    'mulai_at' => $mulaiAt,
                    'selesai_at' => $selesaiAt,
                ]);
                $hasilKuisCount++;

                // Jawaban kuis
                foreach ($soalIds as $idxSoal => $sItem) {
                    $ans = ($idxSoal < $benarCount) ? $sItem['kunci'] : ($sItem['kunci'] === 'a' ? 'b' : 'a');
                    insertIfNotExists($pdo, 'jawaban_kuis', ['hasil_kuis_id', 'soal_id'], [
                        'hasil_kuis_id' => $hasilId,
                        'soal_id' => $sItem['id'],
                        'jawaban' => $ans,
                    ]);
                }
            }
        } elseif ($qDef['kategori'] === 'sedang_dibuka') {
            // Kuis saat ini SEDANG DIBUKA!
            // Siswa Putri Wulandari sudah menyelesaikan dengan nilai 100!
            // Sebagian teman lainnya sudah, sebagian belum.
            foreach ($studentsInClass as $sIdx => $s) {
                if ($s['id'] == 30 || $sIdx % 2 === 0) {
                    $mulaiAt = '2026-11-02 08:10:00';
                    $selesaiAt = '2026-11-02 08:35:00';
                    $benarCount = ($s['id'] == 30) ? 5 : 4;
                    $skorKuis = (float) ($benarCount * 20.0);

                    $hasilId = insertIfNotExists($pdo, 'hasil_kuis', ['kuis_id', 'siswa_id'], [
                        'kuis_id' => $kuisId,
                        'siswa_id' => (int) $s['id'],
                        'nilai' => $skorKuis,
                        'mulai_at' => $mulaiAt,
                        'selesai_at' => $selesaiAt,
                    ]);
                    $hasilKuisCount++;

                    foreach ($soalIds as $idxSoal => $sItem) {
                        $ans = ($idxSoal < $benarCount) ? $sItem['kunci'] : ($sItem['kunci'] === 'a' ? 'b' : 'a');
                        insertIfNotExists($pdo, 'jawaban_kuis', ['hasil_kuis_id', 'soal_id'], [
                            'hasil_kuis_id' => $hasilId,
                            'soal_id' => $sItem['id'],
                            'jawaban' => $ans,
                        ]);
                    }
                }
            }
        }
    }
}
echo "Kuis & Hasil Kuis selesai diisi ($hasilKuisCount data hasil kuis).\n";

// -------------------------------------------------------------
// 6. PRESENSI SISWA LENGKAP (1.5 SEMESTER)
// -------------------------------------------------------------
echo "[6/10] Menyusun Data Presensi Rutin Mingguan (1.5 Semester)...\n";

// Buat tanggal pertemuan:
// Semester Genap: Setiap Senin & Kamis dari 12 Januari 2026 s.d. 15 Juni 2026
// Semester Ganjil: Setiap Senin & Kamis dari 20 Juli 2026 s.d. 2 November 2026
$datesGenap = [];
$cur = strtotime('2026-01-12');
$endGenap = strtotime('2026-06-15');
while ($cur <= $endGenap) {
    $dayOfWeek = date('N', $cur); // 1 = Senin, 4 = Kamis
    if ($dayOfWeek == 1 || $dayOfWeek == 4) {
        $datesGenap[] = date('Y-m-d', $cur);
    }
    $cur = strtotime('+1 day', $cur);
}

$datesGanjil = [];
$cur = strtotime('2026-07-20');
$endGanjil = strtotime('2026-11-02');
while ($cur <= $endGanjil) {
    $dayOfWeek = date('N', $cur);
    if ($dayOfWeek == 1 || $dayOfWeek == 4) {
        $datesGanjil[] = date('Y-m-d', $cur);
    }
    $cur = strtotime('+1 day', $cur);
}

$allPresensiDates = array_merge($datesGenap, $datesGanjil);
$absensiCount = 0;

foreach ($classesToSeed as $kId) {
    $studentsInClass = $siswaPerKelas[$kId] ?? [];
    if (empty($studentsInClass)) continue;

    // Ambil pengampu utama matematika dan bahasa indonesia untuk kelas ini
    $pMtk = $pengampuMap[$kId][1] ?? null;
    $pBind = $pengampuMap[$kId][2] ?? null;

    $activePengampus = array_filter([$pMtk, $pBind]);
    foreach ($activePengampus as $pId) {
        foreach ($allPresensiDates as $tgl) {
            foreach ($studentsInClass as $s) {
                // Status kehadiran realistis: 95% hadir, 3% sakit, 2% izin
                $randVal = ($s['id'] * 17 + strtotime($tgl)) % 100;
                if ($s['id'] == 30) {
                    // Putri Wulandari: 97% hadir, 1 sakit, 1 izin
                    if ($randVal === 42) {
                        $st = 'sakit';
                        $ket = 'Flu dan demam ringan (surat dokter terlampir)';
                    } elseif ($randVal === 88) {
                        $st = 'izin';
                        $ket = 'Menghadiri undangan seleksi OSN tingkat kabupaten';
                    } else {
                        $st = 'hadir';
                        $ket = null;
                    }
                } else {
                    if ($randVal < 4) {
                        $st = 'sakit';
                        $ket = 'Sakit demam';
                    } elseif ($randVal < 7) {
                        $st = 'izin';
                        $ket = 'Acara keluarga';
                    } else {
                        $st = 'hadir';
                        $ket = null;
                    }
                }

                insertIfNotExists($pdo, 'absensi', ['pengampu_id', 'siswa_id', 'tanggal'], [
                    'pengampu_id' => $pId,
                    'siswa_id' => (int) $s['id'],
                    'tanggal' => $tgl,
                    'status' => $st,
                    'keterangan' => $ket,
                    'created_at' => $tgl . ' 07:15:00',
                ]);
                $absensiCount++;
            }
        }
    }
}
echo "Presensi selesai diisi ($absensiCount log absensi tercatat).\n";

// -------------------------------------------------------------
// 7. CATATAN SISWA BELAJAR MANDIRI
// -------------------------------------------------------------
echo "[7/10] Menyusun Catatan Belajar Siswa Mandiri...\n";

$catatanDataset = [
    // Putri Wulandari (ID 30)
    [
        'siswa_id' => 30,
        'mapel_id' => 1,
        'judul' => 'Sifat-Sifat Logaritma & Trik Pengerjaan Cepat',
        'isi' => "1. ^a log (b × c) = ^a log b + ^a log c\n2. ^a log (b / c) = ^a log b - ^a log c\n3. ^a log (b^n) = n × ^a log b\n4. ^(a^m) log (b^n) = (n/m) × ^a log b\n5. ^a log b = (^c log b) / (^c log a) = 1 / (^b log a)\n\nTrik: Jika basis logaritma sama, selesaikan langsung variabel di dalam numerus. Jangan lupa cek syarat numerus: numerus > 0!",
        'created_at' => '2026-08-14 19:40:00'
    ],
    [
        'siswa_id' => 30,
        'mapel_id' => 4,
        'judul' => 'Ringkasan Hukum Newton & Diagram Bebas Bidang Miring',
        'isi' => "Komponen gaya pada bidang miring bersudut θ:\n- Sumbu sejajar bidang miring: W sin θ\n- Sumbu tegak lurus bidang: W cos θ\n- Gaya normal: N = W cos θ = m × g × cos θ\n- Gaya gesekan kinetis: f_k = μ_k × N = μ_k × m × g × cos θ\n\nPercepatan meluncur ke bawah:\na = g × (sin θ - μ_k cos θ)\n\nCatatan penting: Jangan lupa cek apakah gaya pendorong melampaui gaya gesek statis maksimum f_s = μ_s × N!",
        'created_at' => '2026-09-22 20:15:00'
    ],
    [
        'siswa_id' => 30,
        'mapel_id' => 8,
        'judul' => 'Cheat Sheet SQL DML & Query Antar Tabel',
        'isi' => "Query esensial untuk tugas proyek basis data:\n\n1. Inner Join:\nSELECT u.nama, k.nama AS kelas\nFROM users u\nJOIN kelas k ON k.id = u.kelas_id;\n\n2. Left Join dengan agregasi:\nSELECT p.id, COUNT(pg.id) as total_kumpul\nFROM tugas p\nLEFT JOIN pengumpulan pg ON pg.tugas_id = p.id\nGROUP BY p.id;\n\n3. Filter Agregat (HAVING):\nHAVING COUNT(pg.id) > 5;",
        'created_at' => '2026-10-15 21:00:00'
    ],
    [
        'siswa_id' => 30,
        'mapel_id' => 5,
        'judul' => 'Aturan Pengisian Elektron Menurut Kaidah Aufbau & Larangan Pauli',
        'isi' => "Urutan pengisian tingkat energi orbital:\n1s -> 2s -> 2p -> 3s -> 3p -> 4s -> 3d -> 4p -> 5s -> 4d -> 5p -> 6s -> 4f -> 5d ...\n\nPengecualian kestabilan subkulit setengah penuh dan penuh:\n- Cr (Z=24): [Ar] 4s1 3d5 (bukan 4s2 3d4)\n- Cu (Z=29): [Ar] 4s1 3d10 (bukan 4s2 3d9)",
        'created_at' => '2026-10-25 19:30:00'
    ],
    // Siswa lain (Rendi Saputra ID 32)
    [
        'siswa_id' => 32,
        'mapel_id' => 4,
        'judul' => 'Rumus Gerak Lurus Berubah Beraturan (GLBB)',
        'isi' => "Tiga persamaan sakti GLBB:\n1. v_t = v_0 + a × t\n2. s = v_0 × t + 0.5 × a × t²\n3. v_t² = v_0² + 2 × a × s\n\nUntuk gerak jatuh bebas: v_0 = 0, a = g.",
        'created_at' => '2026-09-04 18:20:00'
    ],
    // Sari Dewi Lestari (ID 33)
    [
        'siswa_id' => 33,
        'mapel_id' => 2,
        'judul' => 'Kaidah Kebahasaan Teks Anekdot',
        'isi' => "Ciri utama anekdot:\n- Berisi sindiran halus yang dikemas dalam cerita lucu / menggelitik\n- Menggunakan kalimat retoris (pertanyaan tanpa butuh jawaban)\n- Menggunakan konjungsi temporal (kemudian, setelah itu, lalu)\n- Menggunakan kata kerja aksi (menulis, tertawa, berlari)",
        'created_at' => '2026-10-02 20:00:00'
    ],
];

foreach ($catatanDataset as $c) {
    insertIfNotExists($pdo, 'catatan_siswa', ['siswa_id', 'judul'], [
        'siswa_id' => $c['siswa_id'],
        'mapel_id' => $c['mapel_id'],
        'judul' => $c['judul'],
        'isi' => $c['isi'],
        'created_at' => $c['created_at'],
        'updated_at' => $c['created_at'],
    ]);
}
echo "Catatan siswa selesai diisi.\n";

// -------------------------------------------------------------
// 8. LENCANA PENCAPAIAN SISWA
// -------------------------------------------------------------
echo "[8/10] Memberikan Lencana Pencapaian & Mengaktifkan Leaderboard...\n";

$pencapaianDataset = [
    // Putri Wulandari (ID 30) - Siswa teladan kelas XI-IPA
    [
        'siswa_id' => 30,
        'tipe' => 'tepat_waktu',
        'label' => 'Pengumpul Tepat Waktu',
        'deskripsi' => 'Selalu mengumpulkan tugas sebelum tenggat waktu selama 1,5 semester',
        'ikon' => '⏰',
        'diperoleh_at' => '2026-03-15 14:00:00',
    ],
    [
        'siswa_id' => 30,
        'tipe' => 'nilai_sempurna',
        'label' => 'Nilai Sempurna',
        'deskripsi' => 'Meraih nilai sempurna 100 pada kuis dan latihan tugas',
        'ikon' => '💯',
        'diperoleh_at' => '2026-04-10 10:00:00',
    ],
    [
        'siswa_id' => 30,
        'tipe' => 'rajin_kuis',
        'label' => 'Rajin Latihan',
        'deskripsi' => 'Menyelesaikan 5 kuis online tanpa terlewat dengan predikat memuaskan',
        'ikon' => '📝',
        'diperoleh_at' => '2026-08-25 11:30:00',
    ],
    [
        'siswa_id' => 30,
        'tipe' => 'bintang_prestasi',
        'label' => 'Bintang Prestasi Sains',
        'deskripsi' => 'Konsisten mempertahankan peringkat atas dan rekor presensi 98% di kelas XI-IPA',
        'ikon' => '⭐',
        'diperoleh_at' => '2026-10-20 16:00:00',
    ],

    // Rendi Saputra (ID 32)
    [
        'siswa_id' => 32,
        'tipe' => 'tepat_waktu',
        'label' => 'Pengumpul Tepat Waktu',
        'deskripsi' => 'Mengumpulkan minimal 3 tugas sebelum batas waktu',
        'ikon' => '⏰',
        'diperoleh_at' => '2026-03-20 15:00:00',
    ],
    [
        'siswa_id' => 32,
        'tipe' => 'rajin_kuis',
        'label' => 'Rajin Latihan',
        'deskripsi' => 'Telah menyelesaikan kuis online secara konsisten',
        'ikon' => '📝',
        'diperoleh_at' => '2026-09-28 09:00:00',
    ],

    // Sari Dewi Lestari (ID 33)
    [
        'siswa_id' => 33,
        'tipe' => 'tepat_waktu',
        'label' => 'Pengumpul Tepat Waktu',
        'deskripsi' => 'Mengumpulkan minimal 3 tugas sebelum batas waktu',
        'ikon' => '⏰',
        'diperoleh_at' => '2026-04-05 16:30:00',
    ],
    [
        'siswa_id' => 33,
        'tipe' => 'nilai_sempurna',
        'label' => 'Nilai Sempurna',
        'deskripsi' => 'Meraih nilai sempurna 100 pada kuis Bahasa Indonesia',
        'ikon' => '💯',
        'diperoleh_at' => '2026-09-02 11:00:00',
    ],

    // Qori Ramadhani (ID 31)
    [
        'siswa_id' => 31,
        'tipe' => 'tepat_waktu',
        'label' => 'Pengumpul Tepat Waktu',
        'deskripsi' => 'Semua tugas dikumpulkan tepat waktu',
        'ikon' => '⏰',
        'diperoleh_at' => '2026-04-12 11:00:00',
    ],

    // Taufik Hidayat (ID 34)
    [
        'siswa_id' => 34,
        'tipe' => 'rajin_kuis',
        'label' => 'Rajin Latihan',
        'deskripsi' => 'Konsisten menyelesaikan latihan mandiri',
        'ikon' => '📝',
        'diperoleh_at' => '2026-08-20 14:00:00',
    ],

    // Umi Habibah (ID 35)
    [
        'siswa_id' => 35,
        'tipe' => 'tepat_waktu',
        'label' => 'Pengumpul Tepat Waktu',
        'deskripsi' => 'Mengumpulkan minimal 3 tugas tepat waktu',
        'ikon' => '⏰',
        'diperoleh_at' => '2026-04-20 09:00:00',
    ],
];

foreach ($pencapaianDataset as $p) {
    insertIfNotExists($pdo, 'pencapaian', ['siswa_id', 'tipe'], [
        'siswa_id' => $p['siswa_id'],
        'tipe' => $p['tipe'],
        'label' => $p['label'],
        'deskripsi' => $p['deskripsi'],
        'ikon' => $p['ikon'],
        'diperoleh_at' => $p['diperoleh_at'],
    ]);
}
echo "Lencana pencapaian selesai diberikan.\n";

// -------------------------------------------------------------
// 9. PENGUMUMAN GURU & KALENDER AKADEMIK
// -------------------------------------------------------------
echo "[9/10] Menambahkan Pengumuman Guru & Kalender Akademik...\n";

// Pengumuman Guru
$pengumumanDataset = [
    [
        'kelas_id' => 3, 'mapel_id' => 1,
        'judul' => 'Persiapan Penilaian Akhir Semester (PAS) Ganjil Matematika',
        'isi' => "Assalamu'alaikum wr. wb.,\n\nUntuk seluruh siswa kelas XI-IPA, Penilaian Akhir Semester (PAS) Ganjil akan diselenggarakan pada akhir bulan November 2026. Kisi-kisi materi yang diujikan mencakup:\n1. Eksponen, Bentuk Akar, dan Logaritma\n2. Sistem Persamaan Linear Tiga Variabel (SPLTV)\n3. Fungsi Kuadrat dan Masalah Optimasi\n\nSilakan manfaatkan modul di LMS dan kerjakan kuis latihan yang telah dibagikan. Selamat belajar!\n\nSalam,\nBudi Hartono, S.Si.",
        'created_at' => '2026-10-29 08:30:00'
    ],
    [
        'kelas_id' => 3, 'mapel_id' => 4,
        'judul' => 'Jadwal Asistensi Praktikum Fisika Lab: Gerak Parabola',
        'isi' => "Halo rekan-rekan XI-IPA! Bagi kelompok yang data praktikum gerak parabolanya masih belum lengkap atau terkendala kalibrasi busur derajat, asistensi dibuka hari Rabu pukul 14:00 di Lab Fisika.\n\nHarap membawa draft laporan dan data mentah pengukuran.",
        'created_at' => '2026-10-25 11:00:00'
    ],
    [
        'kelas_id' => 3, 'mapel_id' => 8,
        'judul' => 'Tenggat Tugas Proyek Spreadsheet & Evaluasi Mandiri',
        'isi' => "Diberitahukan kepada seluruh siswa bahwa submission Tugas Proyek Spreadsheet akan ditutup pada 25 November 2026. Pastikan file dalam format .xlsx atau PDF hasil ekspor bersih.\n\nSemangat berkarya!",
        'created_at' => '2026-11-01 09:15:00'
    ],
];

foreach ($pengumumanDataset as $ann) {
    if (isset($pengampuMap[$ann['kelas_id']][$ann['mapel_id']])) {
        $pId = $pengampuMap[$ann['kelas_id']][$ann['mapel_id']];
        insertIfNotExists($pdo, 'pengumuman', ['pengampu_id', 'judul'], [
            'pengampu_id' => $pId,
            'judul' => $ann['judul'],
            'isi' => $ann['isi'],
            'created_at' => $ann['created_at'],
        ]);
    }
}

// Kalender Akademik Semester Genap & Ganjil
$kalenderDataset = [
    // Genap 2025/2026 (TA ID 2)
    [
        'judul' => 'Hari Pertama Masuk Sekolah Semester Genap',
        'deskripsi' => 'Awal perkuliahan dan pembelajaran semester genap tahun ajaran 2025/2026',
        'tipe' => 'kegiatan',
        'tanggal_mulai' => '2026-01-05',
        'tanggal_selesai' => '2026-01-05',
        'tahun_ajaran_id' => 2,
    ],
    [
        'judul' => 'Penilaian Tengah Semester (PTS) Genap',
        'deskripsi' => 'Ujian tengah semester genap untuk seluruh jenjang kelas X, XI, dan XII',
        'tipe' => 'ujian',
        'tanggal_mulai' => '2026-03-09',
        'tanggal_selesai' => '2026-03-20',
        'tahun_ajaran_id' => 2,
    ],
    [
        'judul' => 'Libur Hari Raya Idul Fitri 1447 H',
        'deskripsi' => 'Cuti bersama dan libur nasional Hari Raya Idul Fitri',
        'tipe' => 'libur',
        'tanggal_mulai' => '2026-03-18',
        'tanggal_selesai' => '2026-03-27',
        'tahun_ajaran_id' => 2,
    ],
    [
        'judul' => 'Penilaian Akhir Tahun (PAT) / PAS Genap',
        'deskripsi' => 'Ujian kenaikan kelas untuk kelas X dan XI',
        'tipe' => 'ujian',
        'tanggal_mulai' => '2026-06-01',
        'tanggal_selesai' => '2026-06-12',
        'tahun_ajaran_id' => 2,
    ],
    [
        'judul' => 'Pembagian Rapor Kenaikan Kelas (Semester Genap)',
        'deskripsi' => 'Penyerahan laporan hasil belajar siswa kepada orang tua/wali murid',
        'tipe' => 'kegiatan',
        'tanggal_mulai' => '2026-06-19',
        'tanggal_selesai' => '2026-06-19',
        'tahun_ajaran_id' => 2,
    ],
    [
        'judul' => 'Libur Akhir Tahun Ajaran 2025/2026',
        'deskripsi' => 'Libur kenaikan kelas sebelum memasuki tahun ajaran baru',
        'tipe' => 'libur',
        'tanggal_mulai' => '2026-06-22',
        'tanggal_selesai' => '2026-07-10',
        'tahun_ajaran_id' => 2,
    ],

    // Ganjil 2026/2027 (TA ID 1) - Event Tambahan yang Relevan
    [
        'judul' => 'Masa Pengenalan Lingkungan Sekolah (MPLS) & Orientasi Akademik',
        'deskripsi' => 'Kegiatan pengenalan budaya belajar dan sosialisasi kurikulum baru',
        'tipe' => 'kegiatan',
        'tanggal_mulai' => '2026-07-13',
        'tanggal_selesai' => '2026-07-17',
        'tahun_ajaran_id' => 1,
    ],
    [
        'judul' => 'Bulan Bahasa & Gebyar Literasi SMAN 10 Garut',
        'deskripsi' => 'Perlombaan membaca puisi, pidato tiga bahasa, dan penulisan cerpen kreatif',
        'tipe' => 'kegiatan',
        'tanggal_mulai' => '2026-10-26',
        'tanggal_selesai' => '2026-10-28',
        'tahun_ajaran_id' => 1,
    ],
];

foreach ($kalenderDataset as $kal) {
    insertIfNotExists($pdo, 'kalender_akademik', ['judul', 'tanggal_mulai', 'tahun_ajaran_id'], [
        'judul' => $kal['judul'],
        'deskripsi' => $kal['deskripsi'],
        'tipe' => $kal['tipe'],
        'tanggal_mulai' => $kal['tanggal_mulai'],
        'tanggal_selesai' => $kal['tanggal_selesai'],
        'tahun_ajaran_id' => $kal['tahun_ajaran_id'],
        'created_at' => '2026-07-01 08:00:00',
    ]);
}
echo "Pengumuman dan Kalender Akademik diperbarui.\n";

// -------------------------------------------------------------
// 10. PERPUSTAKAAN DIGITAL (KOLEKSI BUKU SISWA KELAS XI)
// -------------------------------------------------------------
echo "[10/10] Melengkapi Koleksi E-Perpustakaan Digital...\n";

$perpusDataset = [
    [
        'judul' => 'Matematika Tingkat Lanjut untuk SMA/MA Kelas XI',
        'penulis' => 'Al Azhary Masta, dkk. (Pusat Perbukuan Kemendikbudristek)',
        'deskripsi' => 'Buku teks utama siswa kelas XI jenjang SMA/MA. Membahas geometri analitik, sistem persamaan linier, polinomial, matriks, dan kalkulus diferensial.',
        'kategori' => 'wajib',
        'mapel_id' => 1,
        'tingkat' => 'XI',
        'file_asli' => 'Buku_Siswa_Matematika_Tingkat_Lanjut_XI.pdf',
    ],
    [
        'judul' => 'Fisika untuk SMA/MA Kelas XI (Kurikulum Merdeka)',
        'penulis' => 'Marianna Magdalena Radjawane, dkk.',
        'deskripsi' => 'Panduan pembelajaran Fisika kelas XI berbasis inkuiri sains. Mengulas vektor perpindahan, kinematika gerak, dinamika gerak partikel, dan hukum gravitasi Newton.',
        'kategori' => 'wajib',
        'mapel_id' => 4,
        'tingkat' => 'XI',
        'file_asli' => 'Buku_Siswa_Fisika_Kelas_XI.pdf',
    ],
    [
        'judul' => 'Kimia untuk SMA/MA Kelas XI',
        'penulis' => 'Munasprianto Ramli, dkk.',
        'deskripsi' => 'Buku teks kimia kelas XI mengenai struktur atom dan tabel periodik, ikatan kimia, stoikiometri reaksi larutan, serta termokimia.',
        'kategori' => 'wajib',
        'mapel_id' => 5,
        'tingkat' => 'XI',
        'file_asli' => 'Buku_Siswa_Kimia_Kelas_XI.pdf',
    ],
    [
        'judul' => 'Biologi untuk SMA/MA Kelas XI',
        'penulis' => 'Rini Solihat, dkk.',
        'deskripsi' => 'Buku teks utama biologi kelas XI yang mengeksplorasi struktur dan fungsi sel, transpor membran, serta anatomi sistem peredaran dan pernapasan manusia.',
        'kategori' => 'wajib',
        'mapel_id' => 6,
        'tingkat' => 'XI',
        'file_asli' => 'Buku_Siswa_Biologi_Kelas_XI.pdf',
    ],
    [
        'judul' => 'Informatika untuk SMA/MA Kelas XI',
        'penulis' => 'Iryanto, dkk.',
        'deskripsi' => 'Buku teks informatika kelas XI mencakup berpikir komputasional tingkat lanjut, pemrograman algoritma, basis data SQL, dan rekayasa perangkat lunak.',
        'kategori' => 'wajib',
        'mapel_id' => 8,
        'tingkat' => 'XI',
        'file_asli' => 'Buku_Siswa_Informatika_Kelas_XI.pdf',
    ],
    [
        'judul' => 'Pathway to English for Senior High School Grade XI',
        'penulis' => 'Th. M. Sudarwati, Eudia Grace',
        'deskripsi' => 'English textbook designed to develop senior high students global communicative competence across diverse text types.',
        'kategori' => 'pendukung',
        'mapel_id' => 3,
        'tingkat' => 'XI',
        'file_asli' => 'Pathway_to_English_Grade_XI.pdf',
    ],
];

foreach ($perpusDataset as $b) {
    insertIfNotExists($pdo, 'perpustakaan', ['judul', 'tingkat'], [
        'judul' => $b['judul'],
        'penulis' => $b['penulis'],
        'deskripsi' => $b['deskripsi'],
        'kategori' => $b['kategori'],
        'mapel_id' => $b['mapel_id'],
        'tingkat' => $b['tingkat'],
        'file' => $samplePdf,
        'file_asli' => $b['file_asli'],
        'cover' => null,
        'uploader_id' => 1,
        'created_at' => '2026-07-15 08:30:00',
    ]);
}
echo "E-Perpustakaan digital selesai diperbarui.\n";

echo "\n=== PROSES SEEDER 1,5 SEMESTER SELESAI DENGAN SUKSES! ===\n";

