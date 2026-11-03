<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\HttpException;
use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Pengampu;
use App\Models\Pengumpulan;

/**
 * F12 sisi siswa: halaman "Nilai Saya", dikelompokkan per mata pelajaran.
 */
final class NilaiSiswaController extends Controller
{
    public function index(): void
    {
        $siswaId = (int) Auth::id();
        $kelasId = Auth::kelasId();

        if ($kelasId === null) {
            throw HttpException::terlarang(
                'Akun Anda belum ditempatkan di kelas. Hubungi administrator sekolah.'
            );
        }

        // Pastikan kuis yang waktunya sudah habis sudah dinilai sebelum ditampilkan.
        $hasilKuis = new HasilKuis();
        $modelKuis = new Kuis();

        foreach ((new Pengampu())->untukKelas($kelasId) as $p) {
            foreach ($modelKuis->untukPengampu((int) $p['id']) as $q) {
                $hasilKuis->finalisasiYangKedaluwarsa((int) $q['id']);
            }
        }

        $rawTugas = (new Pengumpulan())->nilaiSiswa($siswaId);
        $rawKuis  = $hasilKuis->nilaiSiswa($siswaId);

        $kelompok = $this->kelompokkan($rawTugas, $rawKuis);
        $rangkuman = $this->hitungRangkuman($kelompok, $rawTugas, $rawKuis);

        $this->render('siswa/nilai', [
            'judul'     => 'Nilai Saya',
            'kelompok'  => $kelompok,
            'rangkuman' => $rangkuman,
        ]);
    }

    /**
     * Gabungkan nilai tugas dan kuis menjadi satu peta per mata pelajaran,
     * lengkap dengan rata-ratanya.
     *
     * @param list<array> $tugas
     * @param list<array> $kuis
     * @return array<string,array{kode:string, item:list<array>, rata:float|null}>
     */
    private function kelompokkan(array $tugas, array $kuis): array
    {
        $kelompok = [];

        foreach ($tugas as $t) {
            $mapel = (string) $t['mapel_nama'];
            $kelompok[$mapel]['kode'] = (string) $t['mapel_kode'];
            $kelompok[$mapel]['item'][] = [
                'jenis'    => 'Tugas',
                'judul'    => $t['judul'],
                'waktu'    => $t['tenggat'],
                'status'   => $t['status'],
                'nilai'    => $t['nilai'],
                'komentar' => $t['komentar'],
            ];
        }

        foreach ($kuis as $q) {
            $mapel = (string) $q['mapel_nama'];
            $kelompok[$mapel]['kode'] = (string) $q['mapel_kode'];
            $kelompok[$mapel]['item'][] = [
                'jenis'    => 'Kuis',
                'judul'    => $q['judul'],
                'waktu'    => $q['mulai'],
                'status'   => $q['selesai_at'] === null ? 'belum_selesai' : 'selesai',
                'nilai'    => $q['nilai'],
                'komentar' => null,
            ];
        }

        foreach ($kelompok as $mapel => $isi) {
            $nilai = array_values(array_filter(
                array_column($isi['item'], 'nilai'),
                static fn ($n) => $n !== null
            ));

            $kelompok[$mapel]['rata'] = $nilai === []
                ? null
                : round(array_sum(array_map('floatval', $nilai)) / count($nilai), 2);
        }

        ksort($kelompok);

        return $kelompok;
    }

    /**
     * Hitung rangkuman statistik menyeluruh dan susun data visualisasi grafik interaktif
     * yang disesuaikan secara personal untuk siswa.
     *
     * @param array<string,array{kode:string, item:list<array>, rata:float|null}> $kelompok
     * @param list<array> $rawTugas
     * @param list<array> $rawKuis
     * @return array
     */
    private function hitungRangkuman(array $kelompok, array $rawTugas, array $rawKuis): array
    {
        $allScores = [];
        $tugasScores = [];
        $kuisScores = [];
        $linimasa = [];

        foreach ($rawTugas as $t) {
            if ($t['nilai'] !== null) {
                $val = (float) $t['nilai'];
                $allScores[] = $val;
                $tugasScores[] = $val;
                $linimasa[] = [
                    'waktu'   => (string) $t['tenggat'],
                    'tanggal' => substr((string) $t['tenggat'], 0, 10),
                    'label'   => function_exists('tanggal_id') ? tanggal_id($t['tenggat']) : substr((string) $t['tenggat'], 0, 10),
                    'judul'   => (string) $t['judul'],
                    'mapel'   => (string) $t['mapel_nama'],
                    'kode'    => (string) $t['mapel_kode'],
                    'jenis'   => 'Tugas',
                    'nilai'   => $val,
                ];
            }
        }

        foreach ($rawKuis as $q) {
            if ($q['nilai'] !== null) {
                $val = (float) $q['nilai'];
                $allScores[] = $val;
                $kuisScores[] = $val;
                $linimasa[] = [
                    'waktu'   => (string) $q['mulai'],
                    'tanggal' => substr((string) $q['mulai'], 0, 10),
                    'label'   => function_exists('tanggal_id') ? tanggal_id($q['mulai']) : substr((string) $q['mulai'], 0, 10),
                    'judul'   => (string) $q['judul'],
                    'mapel'   => (string) $q['mapel_nama'],
                    'kode'    => (string) $q['mapel_kode'],
                    'jenis'   => 'Kuis',
                    'nilai'   => $val,
                ];
            }
        }

        // Urutkan linimasa secara kronologis (ASC)
        usort($linimasa, static fn ($a, $b) => strcmp($a['waktu'], $b['waktu']));

        // Hitung rata-rata kumulatif berjalan
        $runningSum = 0;
        $runningCount = 0;
        foreach ($linimasa as &$item) {
            $runningSum += $item['nilai'];
            $runningCount++;
            $item['kumulatif'] = round($runningSum / $runningCount, 2);
        }
        unset($item);

        $totalAsesmen = count($allScores);
        $rerataKeseluruhan = $totalAsesmen > 0 ? round(array_sum($allScores) / $totalAsesmen, 2) : 0.0;
        $rerataTugas = count($tugasScores) > 0 ? round(array_sum($tugasScores) / count($tugasScores), 2) : 0.0;
        $rerataKuis = count($kuisScores) > 0 ? round(array_sum($kuisScores) / count($kuisScores), 2) : 0.0;

        // Distribusi Predikat (A, B, C, D)
        $distribusi = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        $jumlahTuntas = 0; // KKM = 75.0
        foreach ($allScores as $s) {
            if ($s >= 90.0) {
                $distribusi['A']++;
            } elseif ($s >= 80.0) {
                $distribusi['B']++;
            } elseif ($s >= 75.0) {
                $distribusi['C']++;
            } else {
                $distribusi['D']++;
            }

            if ($s >= 75.0) {
                $jumlahTuntas++;
            }
        }
        $persenTuntas = $totalAsesmen > 0 ? round(($jumlahTuntas / $totalAsesmen) * 100, 1) : 0.0;

        // Tentukan Predikat Utama
        if ($rerataKeseluruhan >= 90.0) {
            $predikatHuruf = 'A';
            $predikatLabel = 'Sangat Memuaskan';
        } elseif ($rerataKeseluruhan >= 80.0) {
            $predikatHuruf = 'B';
            $predikatLabel = 'Baik & Kompeten';
        } elseif ($rerataKeseluruhan >= 75.0) {
            $predikatHuruf = 'C';
            $predikatLabel = 'Cukup (Tuntas KKM)';
        } else {
            $predikatHuruf = 'D';
            $predikatLabel = 'Perlu Bimbingan';
        }

        // Cari Mapel Tertinggi dan Terendah
        $mapelList = [];
        $mapelTertinggi = null;
        $mapelTerendah = null;

        $grafikMapel = [
            'labels'       => [],
            'kodes'        => [],
            'rerata'       => [],
            'rerata_tugas' => [],
            'rerata_kuis'  => [],
        ];

        foreach ($kelompok as $namaMapel => $dataMapel) {
            $r = $dataMapel['rata'];
            if ($r !== null) {
                $mapelList[$namaMapel] = [
                    'nama' => $namaMapel,
                    'kode' => $dataMapel['kode'],
                    'rata' => $r,
                ];

                if ($mapelTertinggi === null || $r > $mapelTertinggi['rata']) {
                    $mapelTertinggi = $mapelList[$namaMapel];
                }
                if ($mapelTerendah === null || $r < $mapelTerendah['rata']) {
                    $mapelTerendah = $mapelList[$namaMapel];
                }

                // Hitung rata-rata per jenis untuk mapel ini
                $tScoresMapel = [];
                $qScoresMapel = [];
                foreach ($dataMapel['item'] as $it) {
                    if ($it['nilai'] !== null) {
                        if ($it['jenis'] === 'Tugas') {
                            $tScoresMapel[] = (float) $it['nilai'];
                        } else {
                            $qScoresMapel[] = (float) $it['nilai'];
                        }
                    }
                }

                $grafikMapel['labels'][] = $namaMapel;
                $grafikMapel['kodes'][] = $dataMapel['kode'];
                $grafikMapel['rerata'][] = $r;
                $grafikMapel['rerata_tugas'][] = $tScoresMapel !== [] ? round(array_sum($tScoresMapel) / count($tScoresMapel), 2) : null;
                $grafikMapel['rerata_kuis'][] = $qScoresMapel !== [] ? round(array_sum($qScoresMapel) / count($qScoresMapel), 2) : null;
            }
        }

        return [
            'total_asesmen'       => $totalAsesmen,
            'total_tugas'         => count($tugasScores),
            'total_kuis'          => count($kuisScores),
            'rerata_keseluruhan'  => $rerataKeseluruhan,
            'rerata_tugas'        => $rerataTugas,
            'rerata_kuis'         => $rerataKuis,
            'nilai_tertinggi'     => $allScores !== [] ? max($allScores) : null,
            'nilai_terendah'      => $allScores !== [] ? min($allScores) : null,
            'persen_tuntas'       => $persenTuntas,
            'predikat_huruf'      => $predikatHuruf,
            'predikat_label'      => $predikatLabel,
            'mapel_tertinggi'     => $mapelTertinggi,
            'mapel_terendah'      => $mapelTerendah,
            'distribusi'          => $distribusi,
            'grafik_mapel'        => $grafikMapel,
            'linimasa'            => $linimasa,
        ];
    }
}
