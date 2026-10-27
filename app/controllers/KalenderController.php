<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\KalenderAkademik;
use App\Models\TahunAjaran;

/**
 * F23: kalender akademik sekolah — jadwal ujian, libur, dan kegiatan.
 */
final class KalenderController extends Controller
{
    public function index(): void
    {
        $tModel = new TahunAjaran();
        $tahunAktif = $tModel->aktif();

        if ($tahunAktif === null) {
            $tahunAktif = ['id' => 0, 'nama' => 'Belum Diatur'];
        }

        $tahunId = (int) $tahunAktif['id'];
        $model   = new KalenderAkademik();
        $events  = $tahunId > 0 ? $model->daftar($tahunId) : [];

        $this->render('kalender/index', [
            'judul'      => 'Kalender Akademik Sekolah',
            'tahunAktif' => $tahunAktif,
            'events'     => $events,
            'mendatang'  => $model->mendatang(10),
            'peran'      => Auth::peran(),
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        if (Auth::peran() !== 'admin') {
            throw HttpException::terlarang('Hanya admin yang dapat mengelola agenda sekolah.');
        }

        $id             = (int) post('id', '0');
        $judul          = post('judul');
        $deskripsi      = post('deskripsi');
        $tipe           = post('tipe', 'kegiatan');
        $tanggalMulai   = post('tanggal_mulai');
        $tanggalSelesai = post('tanggal_selesai');
        $tahunAjaranId  = (int) post('tahun_ajaran_id', '0');

        $tipeValid = ['ujian', 'libur', 'kegiatan', 'lainnya'];

        $error = match (true) {
            $judul === ''               => 'Judul kegiatan wajib diisi.',
            !in_array($tipe, $tipeValid)=> 'Tipe agenda tidak valid.',
            $tanggalMulai === ''        => 'Tanggal mulai wajib diisi.',
            $tanggalSelesai === ''      => 'Tanggal selesai wajib diisi.',
            $tanggalMulai > $tanggalSelesai => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            $tahunAjaranId === 0        => 'Tahun ajaran wajib dipilih.',
            default                     => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect('/kalender');
        }

        $model = new KalenderAkademik();

        if ($id > 0) {
            $model->ubah($id, $judul, $deskripsi !== '' ? $deskripsi : null, $tipe, $tanggalMulai, $tanggalSelesai, $tahunAjaranId);
            Flash::sukses('Agenda sekolah berhasil diperbarui.');
        } else {
            $model->buat($judul, $deskripsi !== '' ? $deskripsi : null, $tipe, $tanggalMulai, $tanggalSelesai, $tahunAjaranId);
            Flash::sukses('Agenda sekolah berhasil ditambahkan.');
        }

        redirect('/kalender');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        if (Auth::peran() !== 'admin') {
            throw HttpException::terlarang('Hanya admin yang dapat menghapus agenda.');
        }

        $id = (int) post('id', '0');
        (new KalenderAkademik())->hapus($id);

        Flash::sukses('Agenda berhasil dihapus.');
        redirect('/kalender');
    }
}
