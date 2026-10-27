<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\Catatan;
use App\Models\Mapel;

/**
 * F20: catatan belajar pribadi siswa (Notes).
 */
final class CatatanSiswaController extends Controller
{
    public function index(): void
    {
        $siswaId = (int) Auth::id();
        $model   = new Catatan();

        $this->render('siswa/catatan/index', [
            'judul' => 'Catatan Belajar Saya',
            'data'  => $model->milikSiswa($siswaId),
        ]);
    }

    public function form(?string $id = null): void
    {
        $siswaId = (int) Auth::id();
        $catatan = null;

        if ($id !== null) {
            $catatan = (new Catatan())->cariMilikSiswa($this->idWajib($id), $siswaId);
            if ($catatan === null) {
                throw HttpException::terlarang('Catatan ini bukan milik Anda.');
            }
        }

        $this->render('siswa/catatan/form', [
            'judul'   => $catatan === null ? 'Buat Catatan Baru' : 'Ubah Catatan',
            'catatan' => $catatan,
            'mapel'   => (new Mapel())->semuaAktif(),
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $siswaId = (int) Auth::id();
        $id      = (int) post('id', '0');
        $mapelId = post('mapel_id') !== '' ? (int) post('mapel_id') : null;
        $judul   = post('judul');
        $isi     = post('isi');

        $model = new Catatan();
        if ($id > 0 && $model->cariMilikSiswa($id, $siswaId) === null) {
            throw HttpException::terlarang('Catatan ini bukan milik Anda.');
        }

        $error = match (true) {
            $judul === ''           => 'Judul catatan wajib diisi.',
            mb_strlen($judul) > 200 => 'Judul maksimal 200 karakter.',
            $isi === ''             => 'Isi catatan tidak boleh kosong.',
            default                 => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($id > 0 ? '/siswa/catatan/form/' . $id : '/siswa/catatan/form');
        }

        if ($id > 0) {
            $model->ubah($id, $mapelId, $judul, $isi);
            Flash::sukses('Catatan berhasil diperbarui.');
        } else {
            $model->buat($siswaId, $mapelId, $judul, $isi);
            Flash::sukses('Catatan baru berhasil disimpan.');
        }

        redirect('/siswa/catatan');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $siswaId = (int) Auth::id();
        $id      = (int) post('id', '0');
        $model   = new Catatan();

        if ($model->cariMilikSiswa($id, $siswaId) === null) {
            throw HttpException::terlarang('Catatan ini bukan milik Anda.');
        }

        $model->hapus($id);
        Flash::sukses('Catatan berhasil dihapus.');
        redirect('/siswa/catatan');
    }
}
