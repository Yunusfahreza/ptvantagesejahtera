<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\Kelas;
use App\Models\TahunAjaran;

/**
 * F03: CRUD kelas.
 */
final class KelasController extends Controller
{
    private const TINGKAT = ['X', 'XI', 'XII'];

    public function index(): void
    {
        $model  = new Kelas();
        $editId = query_int('edit');

        $this->render('admin/kelas', [
            'judul' => 'Kelas',
            'data'  => $model->daftar(),
            'tahun' => (new TahunAjaran())->daftar(),
            'edit'  => $editId > 0 ? $model->cariDenganId($editId) : null,
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $id      = (int) post('id', '0');
        $nama    = post('nama');
        $tingkat = post('tingkat');
        $tahunId = (int) post('tahun_ajaran_id', '0');
        $model   = new Kelas();

        $error = match (true) {
            $nama === ''
                => 'Nama kelas wajib diisi.',
            mb_strlen($nama) > 30
                => 'Nama kelas maksimal 30 karakter.',
            !in_array($tingkat, self::TINGKAT, true)
                => 'Tingkat harus X, XI, atau XII.',
            $tahunId <= 0 || (new TahunAjaran())->cariDenganId($tahunId) === null
                => 'Tahun ajaran tidak valid.',
            $model->dipakai($nama, $tahunId, $id > 0 ? $id : null)
                => 'Kelas dengan nama tersebut sudah ada di tahun ajaran itu.',
            default => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($id > 0 ? '/admin/kelas?edit=' . $id : '/admin/kelas');
        }

        if ($id > 0) {
            if ($model->cariDenganId($id) === null) {
                Flash::error('Kelas tidak ditemukan.');
                redirect('/admin/kelas');
            }

            $model->ubah($id, $nama, $tingkat, $tahunId);
            Flash::sukses('Kelas berhasil diubah.');
        } else {
            $model->buat($nama, $tingkat, $tahunId);
            Flash::sukses('Kelas berhasil ditambahkan.');
        }

        redirect('/admin/kelas');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $id    = (int) post('id', '0');
        $model = new Kelas();

        if ($id <= 0 || $model->cariDenganId($id) === null) {
            Flash::error('Kelas tidak ditemukan.');
            redirect('/admin/kelas');
        }

        $jumlah = $model->jumlahRelasi($id);

        if ($jumlah > 0) {
            Flash::error(
                'Kelas tidak bisa dihapus karena masih dipakai ' . $jumlah
                . ' data (siswa atau guru pengampu). Pindahkan data tersebut lebih dulu.'
            );
            redirect('/admin/kelas');
        }

        $model->hapus($id);
        Flash::sukses('Kelas berhasil dihapus.');
        redirect('/admin/kelas');
    }
}
