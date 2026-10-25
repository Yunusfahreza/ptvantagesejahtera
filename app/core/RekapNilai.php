<?php

namespace App\Core;

use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Pengumpulan;
use App\Models\Tugas;
use App\Models\User;

/**
 * F12: menyusun matriks rekap nilai satu pengampu.
 *
 * Aturan PRD: tugas yang belum dikumpulkan atau belum dinilai bernilai NULL
 * dan TIDAK dihitung sebagai 0 pada rata-rata.
 */
final class RekapNilai
{
    public function __construct(
        private User $user = new User(),
        private Tugas $tugas = new Tugas(),
        private Kuis $kuis = new Kuis(),
        private Pengumpulan $pengumpulan = new Pengumpulan(),
        private HasilKuis $hasilKuis = new HasilKuis(),
    ) {
    }

    /**
     * @return array{
     *     tugas: list<array>,
     *     kuis: list<array>,
     *     baris: list<array{siswa:array, nilai_tugas:array, nilai_kuis:array, rata:float|null}>,
     *     rata_kelas: float|null
     * }
     */
    public function susun(int $pengampuId, int $kelasId): array
    {
        $siswa = $this->user->siswaDiKelas($kelasId);
        $tugas = $this->tugas->untukPengampu($pengampuId);
        $kuis  = $this->kuis->untukPengampu($pengampuId);

        $petaTugas = $this->petakan($this->pengumpulan->nilaiUntukPengampu($pengampuId), 'tugas_id');
        $petaKuis  = $this->petakan($this->hasilKuis->nilaiUntukPengampu($pengampuId), 'kuis_id');

        $baris      = [];
        $semuaRata  = [];

        foreach ($siswa as $s) {
            $siswaId    = (int) $s['id'];
            $nilaiTugas = [];
            $nilaiKuis  = [];
            $terkumpul  = [];

            foreach ($tugas as $t) {
                $nilai = $petaTugas[$siswaId][(int) $t['id']] ?? null;
                $nilaiTugas[(int) $t['id']] = $nilai;

                if ($nilai !== null) {
                    $terkumpul[] = (float) $nilai;
                }
            }

            foreach ($kuis as $q) {
                $nilai = $petaKuis[$siswaId][(int) $q['id']] ?? null;
                $nilaiKuis[(int) $q['id']] = $nilai;

                if ($nilai !== null) {
                    $terkumpul[] = (float) $nilai;
                }
            }

            // Rata-rata hanya dari nilai yang ada. Tanpa nilai -> null, bukan 0.
            $rata = $terkumpul === [] ? null : round(array_sum($terkumpul) / count($terkumpul), 2);

            if ($rata !== null) {
                $semuaRata[] = $rata;
            }

            $baris[] = [
                'siswa'       => $s,
                'nilai_tugas' => $nilaiTugas,
                'nilai_kuis'  => $nilaiKuis,
                'rata'        => $rata,
            ];
        }

        return [
            'tugas'      => $tugas,
            'kuis'       => $kuis,
            'baris'      => $baris,
            'rata_kelas' => $semuaRata === [] ? null : round(array_sum($semuaRata) / count($semuaRata), 2),
        ];
    }

    /**
     * Ubah daftar baris datar menjadi peta [siswa_id][kolom_id] => nilai.
     *
     * @param list<array> $baris
     * @return array<int,array<int,string|null>>
     */
    private function petakan(array $baris, string $kolomId): array
    {
        $peta = [];

        foreach ($baris as $b) {
            $peta[(int) $b['siswa_id']][(int) $b[$kolomId]] = $b['nilai'];
        }

        return $peta;
    }
}
