<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\Mapel;

/**
 * F03: CRUD mata pelajaran.
 */
final class MapelController extends Controller
{
    public function index(): void
    {
        $model  = new Mapel();
        $editId = query_int('edit');

        $this->render('admin/mapel', [
            'judul' => 'Mata Pelajaran',
            'data'  => $model->daftar(),
            'edit'  => $editId > 0 ? $model->cariDenganId($editId) : null,
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $id    = (int) post('id', '0');
        $kode  = mb_strtoupper(post('kode'));
        $nama  = post('nama');
        $model = new Mapel();

        $error = match (true) {
            $kode === ''
                => 'Kode mata pelajaran wajib diisi.',
            preg_match('#^[A-Z0-9]{2,20}$#', $kode) !== 1
                => 'Kode hanya boleh huruf dan angka, panjang 2 sampai 20 karakter.',
            $nama === ''
                => 'Nama mata pelajaran wajib diisi.',
            mb_strlen($nama) > 100
                => 'Nama mata pelajaran maksimal 100 karakter.',
            $model->kodeDipakai($kode, $id > 0 ? $id : null)
                => 'Kode mata pelajaran sudah dipakai.',
            default => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($id > 0 ? '/admin/mapel?edit=' . $id : '/admin/mapel');
        }

        if ($id > 0) {
            if ($model->cariDenganId($id) === null) {
                Flash::error('Mata pelajaran tidak ditemukan.');
                redirect('/admin/mapel');
            }

            $model->ubah($id, $kode, $nama);
            Flash::sukses('Mata pelajaran berhasil diubah.');
        } else {
            $model->buat($kode, $nama);
            Flash::sukses('Mata pelajaran berhasil ditambahkan.');
        }

        redirect('/admin/mapel');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $id    = (int) post('id', '0');
        $model = new Mapel();

        if ($id <= 0 || $model->cariDenganId($id) === null) {
            Flash::error('Mata pelajaran tidak ditemukan.');
            redirect('/admin/mapel');
        }

        $jumlah = $model->jumlahRelasi($id);

        if ($jumlah > 0) {
            Flash::error(
                'Mata pelajaran tidak bisa dihapus karena masih dipakai '
                . $jumlah . ' penugasan guru pengampu.'
            );
            redirect('/admin/mapel');
        }

        $model->hapus($id);
        Flash::sukses('Mata pelajaran berhasil dihapus.');
        redirect('/admin/mapel');
    }
}
