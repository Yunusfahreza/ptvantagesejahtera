<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\BerkasTidakValid;
use App\Core\Controller;
use App\Core\FileDownloader;
use App\Core\FileUploader;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\Mapel;
use App\Models\Perpustakaan;

/**
 * F17: perpustakaan digital — bank e-book, modul referensi, dan bahan ajar.
 */
final class PerpustakaanController extends Controller
{
    public function index(): void
    {
        $mapelId = get('mapel') !== '' ? (int) get('mapel') : null;
        $tingkat = get('tingkat');
        $cari    = trim(get('cari'));

        $model = new Perpustakaan();
        $buku  = $model->daftar($mapelId, $tingkat !== '' ? $tingkat : null, $cari);
        $mapel = (new Mapel())->semuaAktif();

        $this->render('perpustakaan/index', [
            'judul'   => 'Perpustakaan Digital',
            'data'    => $buku,
            'mapel'   => $mapel,
            'filter'  => [
                'mapel'   => $mapelId,
                'tingkat' => $tingkat,
                'cari'    => $cari,
            ],
            'peran'   => Auth::peran(),
        ]);
    }

    public function form(?string $id = null): void
    {
        $peran = Auth::peran();
        if (!in_array($peran, ['admin', 'guru'], true)) {
            throw HttpException::terlarang('Hanya guru dan admin yang dapat mengelola buku.');
        }

        $buku = null;
        if ($id !== null) {
            $buku = (new Perpustakaan())->cari($this->idWajib($id));
            if ($buku === null) {
                throw HttpException::tidakAda('Buku tidak ditemukan.');
            }
            if ($peran !== 'admin' && (int) $buku['uploader_id'] !== (int) Auth::id()) {
                throw HttpException::terlarang('Anda bukan pengunggah buku ini.');
            }
        }

        $this->render('perpustakaan/form', [
            'judul' => $buku === null ? 'Tambah Koleksi Buku' : 'Ubah Data Buku',
            'buku'  => $buku,
            'mapel' => (new Mapel())->semuaAktif(),
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $peran = Auth::peran();
        if (!in_array($peran, ['admin', 'guru'], true)) {
            throw HttpException::terlarang('Hanya guru dan admin yang dapat mengelola buku.');
        }

        $userId    = (int) Auth::id();
        $id        = (int) post('id', '0');
        $judul     = post('judul');
        $penulis   = post('penulis');
        $deskripsi = post('deskripsi');
        $kategori  = post('kategori', 'pendukung');
        $mapelId   = post('mapel_id') !== '' ? (int) post('mapel_id') : null;
        $tingkat   = post('tingkat', 'semua');

        $model = new Perpustakaan();
        $lama  = $id > 0 ? $model->cari($id) : null;

        if ($id > 0) {
            if ($lama === null) {
                throw HttpException::tidakAda('Buku tidak ditemukan.');
            }
            if ($peran !== 'admin' && (int) $lama['uploader_id'] !== $userId) {
                throw HttpException::terlarang('Anda bukan pengunggah buku ini.');
            }
        }

        $error = match (true) {
            $judul === ''           => 'Judul buku wajib diisi.',
            mb_strlen($judul) > 200 => 'Judul maksimal 200 karakter.',
            !in_array($kategori, ['wajib', 'pendukung'], true) => 'Kategori tidak valid.',
            !in_array($tingkat, ['X', 'XI', 'XII', 'semua'], true) => 'Tingkat tidak valid.',
            default                 => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($id > 0 ? '/perpustakaan/form/' . $id : '/perpustakaan/form');
        }

        $file     = null;
        $fileAsli = null;
        $adaBerkas = ($_FILES['berkas']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

        if ($id === 0 && !$adaBerkas) {
            Flash::error('Berkas e-book (PDF) wajib diunggah.');
            redirect('/perpustakaan/form');
        }

        if ($adaBerkas) {
            $uploader = new FileUploader(['pdf', 'epub'], 50 * 1024 * 1024);
            try {
                $berkas   = $uploader->simpan($_FILES['berkas']);
                $file     = $berkas['nama'];
                $fileAsli = $berkas['asli'];
            } catch (BerkasTidakValid $e) {
                Flash::error($e->getMessage());
                redirect($id > 0 ? '/perpustakaan/form/' . $id : '/perpustakaan/form');
            }
        }

        // Upload Cover opsional
        $cover = null;
        if (($_FILES['cover']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $uploaderCover = new FileUploader(['jpg', 'jpeg', 'png', 'webp'], 5 * 1024 * 1024);
            try {
                $berkasCover = $uploaderCover->simpan($_FILES['cover']);
                $cover = $berkasCover['nama'];
            } catch (BerkasTidakValid $e) {
                Flash::error('Cover tidak valid: ' . $e->getMessage());
                redirect($id > 0 ? '/perpustakaan/form/' . $id : '/perpustakaan/form');
            }
        }

        if ($id > 0) {
            $model->ubah($id, $judul, $penulis !== '' ? $penulis : null, $deskripsi !== '' ? $deskripsi : null, $kategori, $mapelId, $tingkat);

            if ($file !== null) {
                $fileLama = (string) ($lama['file'] ?? '');
                $model->gantiFile($id, $file, $fileAsli);
                if ($fileLama !== '') {
                    (new FileUploader())->hapus($fileLama);
                }
            }

            if ($cover !== null) {
                $coverLama = (string) ($lama['cover'] ?? '');
                $model->gantiCover($id, $cover);
                if ($coverLama !== '') {
                    (new FileUploader())->hapus($coverLama);
                }
            }

            Flash::sukses('Data buku berhasil diperbarui.');
        } else {
            $model->buat(
                $judul,
                $penulis !== '' ? $penulis : null,
                $deskripsi !== '' ? $deskripsi : null,
                $kategori,
                $mapelId,
                $tingkat,
                $file,
                $fileAsli,
                $cover,
                $userId
            );
            Flash::sukses('Buku berhasil ditambahkan ke perpustakaan.');
        }

        redirect('/perpustakaan');
    }

    public function unduh(string $id): void
    {
        $buku = (new Perpustakaan())->cari($this->idWajib($id));
        if ($buku === null) {
            throw HttpException::tidakAda('Buku tidak ditemukan.');
        }

        (new FileDownloader())->kirim((string) $buku['file'], (string) $buku['file_asli']);
    }

    public function baca(string $id): void
    {
        $buku = (new Perpustakaan())->cari($this->idWajib($id));
        if ($buku === null) {
            throw HttpException::tidakAda('Buku tidak ditemukan.');
        }

        (new FileDownloader())->tampil((string) $buku['file'], (string) $buku['file_asli']);
    }

    public function cover(string $id): void
    {
        $buku = (new Perpustakaan())->cari($this->idWajib($id));
        if ($buku === null || ($buku['cover'] ?? '') === '') {
            throw HttpException::tidakAda('Sampul tidak ditemukan.');
        }

        (new FileDownloader())->tampil((string) $buku['cover'], 'cover.jpg');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $peran  = Auth::peran();
        $userId = (int) Auth::id();
        $id     = (int) post('id', '0');
        $model  = new Perpustakaan();
        $buku   = $id > 0 ? $model->cari($id) : null;

        if ($buku === null) {
            throw HttpException::tidakAda('Buku tidak ditemukan.');
        }

        if ($peran !== 'admin' && (int) $buku['uploader_id'] !== $userId) {
            throw HttpException::terlarang('Anda tidak berhak menghapus buku ini.');
        }

        $model->hapus($id);
        (new FileUploader())->hapus((string) $buku['file']);
        if (($buku['cover'] ?? '') !== '') {
            (new FileUploader())->hapus((string) $buku['cover']);
        }

        Flash::sukses('Buku berhasil dihapus dari perpustakaan.');
        redirect('/perpustakaan');
    }
}
