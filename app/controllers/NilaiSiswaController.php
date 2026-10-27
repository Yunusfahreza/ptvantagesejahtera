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

        $this->render('siswa/nilai', [
            'judul'     => 'Nilai Saya',
            'kelompok'  => $this->kelompokkan(
                (new Pengumpulan())->nilaiSiswa($siswaId),
                $hasilKuis->nilaiSiswa($siswaId)
            ),
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
}
