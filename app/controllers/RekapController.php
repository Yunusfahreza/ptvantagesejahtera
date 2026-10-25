<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\HttpException;
use App\Core\RekapNilai;
use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Pengampu;
use App\Models\User;

/**
 * F12: rekap nilai per kelas+mapel, per siswa, dan ekspor CSV.
 */
final class RekapController extends Controller
{
    public function index(): void
    {
        $this->render('guru/rekap/index', [
            'judul' => 'Rekap Nilai',
            'data'  => (new Pengampu())->milikGuru((int) Auth::id()),
        ]);
    }

    public function perPengampu(string $id): void
    {
        $pengampu = $this->pengampuMilikSaya($id);

        // Finalisasi kuis kedaluwarsa dulu supaya angka rekap tidak bolong.
        $this->finalisasiKuis((int) $pengampu['id']);

        $rekap = (new RekapNilai())->susun((int) $pengampu['id'], (int) $pengampu['kelas_id']);

        $this->render('guru/rekap/pengampu', [
            'judul'    => 'Rekap ' . $pengampu['kelas_nama'] . ' - ' . $pengampu['mapel_nama'],
            'pengampu' => $pengampu,
            'rekap'    => $rekap,
        ]);
    }

    public function perSiswa(string $id, string $siswa): void
    {
        $pengampu = $this->pengampuMilikSaya($id);
        $siswaId  = $this->idWajib($siswa);

        $data = (new User())->cariDenganId($siswaId);

        // Siswa harus benar-benar berada di kelas pengampu ini.
        if ($data === null
            || $data['role'] !== 'siswa'
            || (int) $data['kelas_id'] !== (int) $pengampu['kelas_id']
        ) {
            throw HttpException::terlarang('Siswa ini bukan anggota kelas yang Anda ampu.');
        }

        $this->finalisasiKuis((int) $pengampu['id']);

        $rekap = (new RekapNilai())->susun((int) $pengampu['id'], (int) $pengampu['kelas_id']);
        $baris = null;

        foreach ($rekap['baris'] as $b) {
            if ((int) $b['siswa']['id'] === $siswaId) {
                $baris = $b;
                break;
            }
        }

        $this->render('guru/rekap/siswa', [
            'judul'    => 'Rekap ' . $data['nama'],
            'pengampu' => $pengampu,
            'siswa'    => $data,
            'rekap'    => $rekap,
            'baris'    => $baris,
        ]);
    }

    /**
     * Ekspor CSV dengan BOM UTF-8 dan pemisah titik koma, mengikuti
     * default Excel di lokal Indonesia.
     */
    public function eksporCsv(string $id): void
    {
        $pengampu = $this->pengampuMilikSaya($id);
        $this->finalisasiKuis((int) $pengampu['id']);

        $rekap = (new RekapNilai())->susun((int) $pengampu['id'], (int) $pengampu['kelas_id']);

        $nama = sprintf(
            'rekap-%s-%s-%s.csv',
            $this->slug((string) $pengampu['kelas_nama']),
            $this->slug((string) $pengampu['mapel_kode']),
            date('Ymd')
        );

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $nama . '"');
        header('Cache-Control: private, no-store');

        $keluaran = fopen('php://output', 'wb');

        // BOM supaya Excel mengenali UTF-8.
        fwrite($keluaran, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($keluaran, ['Kelas', $pengampu['kelas_nama']], ';');
        fputcsv($keluaran, ['Mata pelajaran', $pengampu['mapel_nama']], ';');
        fputcsv($keluaran, ['Guru pengampu', $pengampu['guru_nama']], ';');
        fputcsv($keluaran, ['Diekspor', date('d M Y H:i')], ';');
        fputcsv($keluaran, [], ';');

        $header = ['Nama siswa', 'Username'];

        foreach ($rekap['tugas'] as $t) {
            $header[] = 'Tugas: ' . $t['judul'];
        }

        foreach ($rekap['kuis'] as $q) {
            $header[] = 'Kuis: ' . $q['judul'];
        }

        $header[] = 'Rata-rata';
        fputcsv($keluaran, $header, ';');

        foreach ($rekap['baris'] as $b) {
            $kolom = [$b['siswa']['nama'], $b['siswa']['username']];

            foreach ($rekap['tugas'] as $t) {
                $kolom[] = $this->angkaCsv($b['nilai_tugas'][(int) $t['id']] ?? null);
            }

            foreach ($rekap['kuis'] as $q) {
                $kolom[] = $this->angkaCsv($b['nilai_kuis'][(int) $q['id']] ?? null);
            }

            $kolom[] = $this->angkaCsv($b['rata']);
            fputcsv($keluaran, $kolom, ';');
        }

        fputcsv($keluaran, [], ';');
        fputcsv($keluaran, ['Rata-rata kelas', $this->angkaCsv($rekap['rata_kelas'])], ';');

        fclose($keluaran);
        exit;
    }

    /**
     * Nilai kosong ditulis sebagai string kosong, bukan 0, sesuai PRD F12.
     * Desimal memakai koma agar terbaca sebagai angka di Excel Indonesia.
     */
    private function angkaCsv(mixed $nilai): string
    {
        if ($nilai === null || $nilai === '') {
            return '';
        }

        return str_replace('.', ',', (string) round((float) $nilai, 2));
    }

    private function pengampuMilikSaya(string $id): array
    {
        $pengampu = (new Pengampu())->cariMilikGuru($this->idWajib($id), (int) Auth::id());

        if ($pengampu === null) {
            throw HttpException::terlarang('Kelas dan mata pelajaran ini bukan yang Anda ampu.');
        }

        return $pengampu;
    }

    private function finalisasiKuis(int $pengampuId): void
    {
        $model = new HasilKuis();

        foreach ((new Kuis())->untukPengampu($pengampuId) as $q) {
            $model->finalisasiYangKedaluwarsa((int) $q['id']);
        }
    }

    private function slug(string $teks): string
    {
        $teks = strtolower($teks);
        $teks = (string) preg_replace('#[^a-z0-9]+#', '-', $teks);

        return trim($teks, '-');
    }
}
