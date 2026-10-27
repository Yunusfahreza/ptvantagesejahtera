<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\Absensi;
use App\Models\Pengampu;

/**
 * F19: guru mencatat kehadiran siswa per kelas dan mata pelajaran.
 */
final class AbsensiGuruController extends Controller
{
    public function index(): void
    {
        $guruId   = (int) Auth::id();
        $pengampu = (new Pengampu())->milikGuru($guruId);

        if ($pengampu === []) {
            $this->render('guru/absensi/index', [
                'judul'        => 'Presensi & Kehadiran',
                'pengampu'     => [],
                'terpilih'     => null,
                'tanggal'      => date('Y-m-d'),
                'siswa'        => [],
                'tanggalAda'   => [],
            ]);
            return;
        }

        $pengampuId = get('pengampu') !== '' ? (int) get('pengampu') : (int) $pengampu[0]['id'];
        $tanggal    = get('tanggal') !== '' ? get('tanggal') : date('Y-m-d');

        // Pastikan pengampu milik guru ini
        $pModel = new Pengampu();
        $target = $pModel->cariMilikGuru($pengampuId, $guruId);

        if ($target === null) {
            $target = $pengampu[0];
            $pengampuId = (int) $target['id'];
        }

        $aModel = new Absensi();
        $siswa  = $aModel->daftarPerPengampuTanggal($pengampuId, $tanggal, (int) $target['kelas_id']);
        $tercatat = $aModel->tanggalTercatat($pengampuId);

        $this->render('guru/absensi/index', [
            'judul'      => 'Presensi: ' . $target['mapel_nama'] . ' - ' . $target['kelas_nama'],
            'pengampu'   => $pengampu,
            'terpilih'   => $target,
            'tanggal'    => $tanggal,
            'siswa'      => $siswa,
            'tanggalAda' => $tercatat,
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $guruId     = (int) Auth::id();
        $pengampuId = (int) post('pengampu_id', '0');
        $tanggal    = post('tanggal', date('Y-m-d'));
        $statusArr  = (array) ($_POST['status'] ?? []);
        $ketArr     = (array) ($_POST['keterangan'] ?? []);

        $target = (new Pengampu())->cariMilikGuru($pengampuId, $guruId);
        if ($target === null) {
            throw HttpException::terlarang('Mata pelajaran bukan yang Anda ampu.');
        }

        $aModel = new Absensi();
        $validStatus = ['hadir', 'izin', 'sakit', 'alpha'];

        foreach ($statusArr as $siswaId => $status) {
            $sid = (int) $siswaId;
            if ($sid <= 0 || !in_array($status, $validStatus, true)) {
                continue;
            }
            $ket = isset($ketArr[$sid]) ? trim((string) $ketArr[$sid]) : null;
            $aModel->simpan($pengampuId, $sid, $tanggal, $status, $ket !== '' ? $ket : null);
        }

        Flash::sukses('Presensi kehadiran tanggal ' . tanggal_id($tanggal) . ' berhasil disimpan.');
        redirect('/guru/absensi?pengampu=' . $pengampuId . '&tanggal=' . $tanggal);
    }
}
