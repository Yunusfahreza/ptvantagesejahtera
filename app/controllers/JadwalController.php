<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Pengampu;

/**
 * F18: jadwal pelajaran mingguan (Admin kelola, Guru & Siswa melihat).
 */
final class JadwalController extends Controller
{
    public function index(): void
    {
        $peran = Auth::peran();
        $model = new Jadwal();

        if ($peran === 'admin') {
            $this->render('admin/jadwal/index', [
                'judul'    => 'Kelola Jadwal Pelajaran',
                'data'     => $model->daftar(),
                'pengampu' => (new Pengampu())->semuaAktif(),
            ]);
            return;
        }

        if ($peran === 'guru') {
            $guruId = (int) Auth::id();
            $jadwal = $model->untukGuru($guruId);
            $this->render('guru/jadwal', [
                'judul'  => 'Jadwal Mengajar',
                'jadwal' => $this->kelompokkanHari($jadwal),
            ]);
            return;
        }

        // Siswa
        $kelasId = Auth::kelasId();
        if ($kelasId === null) {
            throw HttpException::terlarang('Akun Anda belum ditempatkan di kelas.');
        }

        $jadwal = $model->untukKelas($kelasId);
        $this->render('siswa/jadwal', [
            'judul'  => 'Jadwal Pelajaran Kelas',
            'jadwal' => $this->kelompokkanHari($jadwal),
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        if (Auth::peran() !== 'admin') {
            throw HttpException::terlarang('Hanya admin yang dapat mengelola jadwal.');
        }

        $id          = (int) post('id', '0');
        $pengampuId  = (int) post('pengampu_id', '0');
        $hari        = post('hari');
        $jamMulai    = post('jam_mulai');
        $jamSelesai  = post('jam_selesai');
        $ruangan     = post('ruangan');

        $hariValid = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

        $error = match (true) {
            $pengampuId === 0            => 'Guru pengampu wajib dipilih.',
            !in_array($hari, $hariValid) => 'Hari tidak valid.',
            $jamMulai === ''             => 'Jam mulai wajib diisi.',
            $jamSelesai === ''           => 'Jam selesai wajib diisi.',
            $jamMulai >= $jamSelesai     => 'Jam selesai harus setelah jam mulai.',
            default                      => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect('/admin/jadwal');
        }

        $model = new Jadwal();

        if ($id > 0) {
            $model->ubah($id, $pengampuId, $hari, $jamMulai, $jamSelesai, $ruangan !== '' ? $ruangan : null);
            Flash::sukses('Jadwal pelajaran berhasil diperbarui.');
        } else {
            $model->buat($pengampuId, $hari, $jamMulai, $jamSelesai, $ruangan !== '' ? $ruangan : null);
            Flash::sukses('Jadwal pelajaran berhasil ditambahkan.');
        }

        redirect('/admin/jadwal');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        if (Auth::peran() !== 'admin') {
            throw HttpException::terlarang('Hanya admin yang dapat menghapus jadwal.');
        }

        $id = (int) post('id', '0');
        (new Jadwal())->hapus($id);

        Flash::sukses('Jadwal berhasil dihapus.');
        redirect('/admin/jadwal');
    }

    /**
     * Mengelompokkan list jadwal per hari (senin - sabtu).
     *
     * @param list<array> $list
     * @return array<string, list<array>>
     */
    private function kelompokkanHari(array $list): array
    {
        $hasil = [
            'senin'  => [],
            'selasa' => [],
            'rabu'   => [],
            'kamis'  => [],
            'jumat'  => [],
            'sabtu'  => [],
        ];

        foreach ($list as $item) {
            $hari = strtolower((string) ($item['hari'] ?? ''));
            if (isset($hasil[$hari])) {
                $hasil[$hari][] = $item;
            }
        }

        return $hasil;
    }
}
