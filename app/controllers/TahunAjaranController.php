<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\TahunAjaran;

/**
 * F03: CRUD tahun ajaran. Hanya satu yang boleh aktif.
 */
final class TahunAjaranController extends Controller
{
    public function index(): void
    {
        $model = new TahunAjaran();
        $editId = query_int('edit');

        $this->render('admin/tahun', [
            'judul' => 'Tahun Ajaran',
            'data'  => $model->daftar(),
            'edit'  => $editId > 0 ? $model->cariDenganId($editId) : null,
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $id       = (int) post('id', '0');
        $nama     = post('nama');
        $semester = post('semester');
        $model    = new TahunAjaran();

        $error = match (true) {
            $nama === ''
                => 'Nama tahun ajaran wajib diisi.',
            preg_match('#^[0-9]{4}/[0-9]{4}$#', $nama) !== 1
                => 'Format nama tahun ajaran harus seperti 2026/2027.',
            !in_array($semester, ['ganjil', 'genap'], true)
                => 'Semester harus ganjil atau genap.',
            $model->dipakai($nama, $semester, $id > 0 ? $id : null)
                => 'Tahun ajaran dengan semester tersebut sudah ada.',
            default => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($id > 0 ? '/admin/tahun?edit=' . $id : '/admin/tahun');
        }

        if ($id > 0) {
            if ($model->cariDenganId($id) === null) {
                Flash::error('Tahun ajaran tidak ditemukan.');
                redirect('/admin/tahun');
            }

            $model->ubah($id, $nama, $semester);
            Flash::sukses('Tahun ajaran berhasil diubah.');
        } else {
            $model->buat($nama, $semester);
            Flash::sukses('Tahun ajaran berhasil ditambahkan. Aktifkan bila perlu.');
        }

        redirect('/admin/tahun');
    }

    public function aktifkan(): void
    {
        $this->wajibPost();

        $id    = (int) post('id', '0');
        $model = new TahunAjaran();

        if ($id <= 0 || $model->cariDenganId($id) === null) {
            Flash::error('Tahun ajaran tidak ditemukan.');
            redirect('/admin/tahun');
        }

        $model->aktifkan($id);
        Flash::sukses('Tahun ajaran diaktifkan. Tahun ajaran lain otomatis dinonaktifkan.');
        redirect('/admin/tahun');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $id    = (int) post('id', '0');
        $model = new TahunAjaran();
        $baris = $id > 0 ? $model->cariDenganId($id) : null;

        if ($baris === null) {
            Flash::error('Tahun ajaran tidak ditemukan.');
            redirect('/admin/tahun');
        }

        if ((int) $baris['aktif'] === 1) {
            Flash::error('Tahun ajaran yang sedang aktif tidak bisa dihapus.');
            redirect('/admin/tahun');
        }

        $jumlah = $model->jumlahKelas($id);

        if ($jumlah > 0) {
            Flash::error(
                'Tahun ajaran tidak bisa dihapus karena masih dipakai ' . $jumlah . ' kelas.'
            );
            redirect('/admin/tahun');
        }

        $model->hapus($id);
        Flash::sukses('Tahun ajaran berhasil dihapus.');
        redirect('/admin/tahun');
    }
}
