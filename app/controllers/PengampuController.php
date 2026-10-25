<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pengampu;
use App\Models\User;

/**
 * F04: tetapkan guru pengampu. Pasangan (kelas, mapel) wajib unik.
 */
final class PengampuController extends Controller
{
    public function index(): void
    {
        $model  = new Pengampu();
        $editId = query_int('edit');

        $this->render('admin/pengampu', [
            'judul' => 'Guru Pengampu',
            'data'  => $model->daftar(),
            'guru'  => (new User())->daftarGuruAktif(),
            'kelas' => (new Kelas())->daftarRingkas(),
            'mapel' => (new Mapel())->daftar(),
            'edit'  => $editId > 0 ? $model->cariDenganId($editId) : null,
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $id      = (int) post('id', '0');
        $guruId  = (int) post('guru_id', '0');
        $kelasId = (int) post('kelas_id', '0');
        $mapelId = (int) post('mapel_id', '0');

        $model = new Pengampu();
        $guru  = $guruId > 0 ? (new User())->cariDenganId($guruId) : null;

        $error = match (true) {
            $guru === null || $guru['role'] !== 'guru'
                => 'Guru tidak valid.',
            $guru['status'] !== 'aktif'
                => 'Guru tersebut berstatus nonaktif.',
            $kelasId <= 0 || (new Kelas())->cariDenganId($kelasId) === null
                => 'Kelas tidak valid.',
            $mapelId <= 0 || (new Mapel())->cariDenganId($mapelId) === null
                => 'Mata pelajaran tidak valid.',
            $model->dipakai($kelasId, $mapelId, $id > 0 ? $id : null)
                => 'Mata pelajaran itu sudah punya guru pengampu di kelas tersebut.',
            default => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($id > 0 ? '/admin/pengampu?edit=' . $id : '/admin/pengampu');
        }

        if ($id > 0) {
            if ($model->cariDenganId($id) === null) {
                Flash::error('Penugasan tidak ditemukan.');
                redirect('/admin/pengampu');
            }

            $model->ubah($id, $guruId, $kelasId, $mapelId);
            Flash::sukses('Penugasan guru pengampu berhasil diubah.');
        } else {
            $model->buat($guruId, $kelasId, $mapelId);
            Flash::sukses('Penugasan guru pengampu berhasil ditambahkan.');
        }

        redirect('/admin/pengampu');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $id    = (int) post('id', '0');
        $model = new Pengampu();

        if ($id <= 0 || $model->cariDenganId($id) === null) {
            Flash::error('Penugasan tidak ditemukan.');
            redirect('/admin/pengampu');
        }

        $jumlah = $model->jumlahRelasi($id);

        if ($jumlah > 0) {
            Flash::error(
                'Penugasan tidak bisa dihapus karena masih memiliki ' . $jumlah
                . ' konten (materi, tugas, kuis, atau pengumuman). Hapus kontennya lebih dulu.'
            );
            redirect('/admin/pengampu');
        }

        $model->hapus($id);
        Flash::sukses('Penugasan guru pengampu berhasil dihapus.');
        redirect('/admin/pengampu');
    }
}
