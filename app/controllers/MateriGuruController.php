<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\BerkasTidakValid;
use App\Core\Controller;
use App\Core\FileDownloader;
use App\Core\FileUploader;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\Materi;
use App\Models\Pengampu;

/**
 * F05: guru mengelola materi pada pengampu miliknya sendiri.
 */
final class MateriGuruController extends Controller
{
    public function index(): void
    {
        $guruId = (int) Auth::id();

        $this->render('guru/materi/index', [
            'judul'    => 'Materi',
            'data'     => (new Materi())->milikGuru($guruId),
            'pengampu' => (new Pengampu())->milikGuru($guruId),
        ]);
    }

    public function form(?string $id = null): void
    {
        $guruId = (int) Auth::id();
        $materi = null;

        if ($id !== null) {
            // Kepemilikan diperiksa di query: materi guru lain mengembalikan null.
            $materi = (new Materi())->cariMilikGuru($this->idWajib($id), $guruId);

            if ($materi === null) {
                throw HttpException::terlarang('Materi ini bukan milik Anda.');
            }
        }

        $uploader = new FileUploader();

        $this->render('guru/materi/form', [
            'judul'         => $materi === null ? 'Unggah Materi' : 'Ubah Materi',
            'materi'        => $materi,
            'pengampu'      => (new Pengampu())->milikGuru($guruId),
            'maks'          => ukuran_file($uploader->maksByte()),
            'ekstensi'      => $uploader->daftarEkstensi(),
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $guruId     = (int) Auth::id();
        $id         = (int) post('id', '0');
        $pengampuId = (int) post('pengampu_id', '0');
        $judul      = post('judul');
        $deskripsi  = post('deskripsi');

        $model  = new Materi();
        $lama   = $id > 0 ? $model->cariMilikGuru($id, $guruId) : null;

        if ($id > 0 && $lama === null) {
            throw HttpException::terlarang('Materi ini bukan milik Anda.');
        }

        // Pengampu tujuan juga harus milik guru yang sama.
        if ((new Pengampu())->cariMilikGuru($pengampuId, $guruId) === null) {
            Flash::error('Kelas dan mata pelajaran yang dipilih bukan yang Anda ampu.');
            redirect($id > 0 ? '/guru/materi/form/' . $id : '/guru/materi/form');
        }

        $error = match (true) {
            $judul === ''              => 'Judul materi wajib diisi.',
            mb_strlen($judul) > 150    => 'Judul maksimal 150 karakter.',
            mb_strlen($deskripsi) > 5000 => 'Deskripsi maksimal 5000 karakter.',
            default                    => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($id > 0 ? '/guru/materi/form/' . $id : '/guru/materi/form');
        }

        $uploader  = new FileUploader();
        $adaBerkas = ($_FILES['berkas']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

        // Materi baru wajib punya berkas; saat mengubah, berkas opsional.
        if ($id === 0 && !$adaBerkas) {
            Flash::error('Berkas materi wajib diunggah.');
            redirect('/guru/materi/form');
        }

        try {
            $berkas = $adaBerkas ? $uploader->simpan($_FILES['berkas']) : null;
        } catch (BerkasTidakValid $e) {
            Flash::error($e->getMessage());
            redirect($id > 0 ? '/guru/materi/form/' . $id : '/guru/materi/form');
        }

        if ($id > 0) {
            $model->ubah($id, $pengampuId, $judul, $deskripsi);

            if ($berkas !== null) {
                // Simpan nama lama dulu, baru hapus setelah DB diperbarui.
                $fileLama = (string) $lama['file'];
                $model->gantiFile($id, $berkas['nama'], $berkas['asli']);
                $uploader->hapus($fileLama);
            }

            Flash::sukses('Materi berhasil diubah.');
        } else {
            $model->buat($pengampuId, $judul, $deskripsi, $berkas['nama'], $berkas['asli']);
            Flash::sukses('Materi berhasil diunggah.');
        }

        redirect('/guru/materi');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $guruId = (int) Auth::id();
        $id     = (int) post('id', '0');
        $model  = new Materi();
        $materi = $id > 0 ? $model->cariMilikGuru($id, $guruId) : null;

        if ($materi === null) {
            throw HttpException::terlarang('Materi ini bukan milik Anda.');
        }

        $model->hapus($id);
        // Berkas fisik dibuang setelah record hilang supaya tidak ada orphan link.
        (new FileUploader())->hapus((string) $materi['file']);

        Flash::sukses('Materi dan berkasnya berhasil dihapus.');
        redirect('/guru/materi');
    }

    public function unduh(string $id): void
    {
        $materi = (new Materi())->cariMilikGuru($this->idWajib($id), (int) Auth::id());

        if ($materi === null) {
            throw HttpException::terlarang('Materi ini bukan milik Anda.');
        }

        (new FileDownloader())->kirim((string) $materi['file'], (string) $materi['file_asli']);
    }

    public function lihat(string $id): void
    {
        $materi = (new Materi())->cariMilikGuru($this->idWajib($id), (int) Auth::id());

        if ($materi === null) {
            throw HttpException::terlarang('Materi ini bukan milik Anda.');
        }

        (new FileDownloader())->tampil((string) $materi['file'], (string) $materi['file_asli']);
    }
}
