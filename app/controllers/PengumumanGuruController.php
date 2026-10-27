<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\Pengampu;
use App\Models\Pengumuman;

/**
 * F13: guru membuat pengumuman untuk satu pengampu miliknya.
 */
final class PengumumanGuruController extends Controller
{
    public function index(): void
    {
        $guruId = (int) Auth::id();

        $this->render('guru/pengumuman/index', [
            'judul'    => 'Pengumuman',
            'data'     => (new Pengumuman())->milikGuru($guruId),
            'pengampu' => (new Pengampu())->milikGuru($guruId),
        ]);
    }

    public function form(?string $id = null): void
    {
        $guruId     = (int) Auth::id();
        $pengumuman = null;

        if ($id !== null) {
            $pengumuman = (new Pengumuman())->cariMilikGuru($this->idWajib($id), $guruId);

            if ($pengumuman === null) {
                throw HttpException::terlarang('Pengumuman ini bukan milik Anda.');
            }
        }

        $this->render('guru/pengumuman/form', [
            'judul'      => $pengumuman === null ? 'Buat Pengumuman' : 'Ubah Pengumuman',
            'pengumuman' => $pengumuman,
            'pengampu'   => (new Pengampu())->milikGuru($guruId),
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $guruId     = (int) Auth::id();
        $id         = (int) post('id', '0');
        $pengampuId = (int) post('pengampu_id', '0');
        $judul      = post('judul');
        $isi        = post('isi');

        $model = new Pengumuman();

        if ($id > 0 && $model->cariMilikGuru($id, $guruId) === null) {
            throw HttpException::terlarang('Pengumuman ini bukan milik Anda.');
        }

        $kembali = $id > 0 ? '/guru/pengumuman/form/' . $id : '/guru/pengumuman/form';

        if ((new Pengampu())->cariMilikGuru($pengampuId, $guruId) === null) {
            Flash::error('Kelas dan mata pelajaran yang dipilih bukan yang Anda ampu.');
            redirect($kembali);
        }

        $error = match (true) {
            $judul === ''           => 'Judul pengumuman wajib diisi.',
            mb_strlen($judul) > 150 => 'Judul maksimal 150 karakter.',
            $isi === ''             => 'Isi pengumuman wajib diisi.',
            mb_strlen($isi) > 5000  => 'Isi maksimal 5000 karakter.',
            default                 => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($kembali);
        }

        if ($id > 0) {
            $model->ubah($id, $pengampuId, $judul, $isi);
            Flash::sukses('Pengumuman berhasil diubah.');
        } else {
            $model->buat($pengampuId, $judul, $isi);
            Flash::sukses('Pengumuman berhasil dibuat.');
        }

        redirect('/guru/pengumuman');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $guruId = (int) Auth::id();
        $id     = (int) post('id', '0');
        $model  = new Pengumuman();

        if ($id <= 0 || $model->cariMilikGuru($id, $guruId) === null) {
            throw HttpException::terlarang('Pengumuman ini bukan milik Anda.');
        }

        $model->hapus($id);
        Flash::sukses('Pengumuman berhasil dihapus.');
        redirect('/guru/pengumuman');
    }
}
