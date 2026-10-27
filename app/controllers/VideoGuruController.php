<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\BerkasTidakValid;
use App\Core\Controller;
use App\Core\FileDownloader;
use App\Core\FileUploader;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\Pengampu;
use App\Models\Video;

/**
 * F15: guru mengelola video pembelajaran.
 */
final class VideoGuruController extends Controller
{
    public function index(): void
    {
        $guruId = (int) Auth::id();

        $this->render('guru/video/index', [
            'judul'    => 'Video Pembelajaran',
            'data'     => (new Video())->milikGuru($guruId),
            'pengampu' => (new Pengampu())->milikGuru($guruId),
        ]);
    }

    public function form(?string $id = null): void
    {
        $guruId = (int) Auth::id();
        $video  = null;

        if ($id !== null) {
            $video = (new Video())->cariMilikGuru($this->idWajib($id), $guruId);

            if ($video === null) {
                throw HttpException::terlarang('Video ini bukan milik Anda.');
            }
        }

        $this->render('guru/video/form', [
            'judul'    => $video === null ? 'Tambah Video' : 'Ubah Video',
            'video'    => $video,
            'pengampu' => (new Pengampu())->milikGuru($guruId),
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
        $tipe       = post('tipe');
        $urlEmbed   = post('url_embed');
        $durasi     = post('durasi');

        $model = new Video();
        $lama  = $id > 0 ? $model->cariMilikGuru($id, $guruId) : null;

        if ($id > 0 && $lama === null) {
            throw HttpException::terlarang('Video ini bukan milik Anda.');
        }

        if ((new Pengampu())->cariMilikGuru($pengampuId, $guruId) === null) {
            Flash::error('Kelas dan mata pelajaran yang dipilih bukan yang Anda ampu.');
            redirect($id > 0 ? '/guru/video/form/' . $id : '/guru/video/form');
        }

        $error = match (true) {
            $judul === ''            => 'Judul video wajib diisi.',
            mb_strlen($judul) > 150  => 'Judul maksimal 150 karakter.',
            !in_array($tipe, ['upload', 'youtube', 'gdrive'], true) => 'Tipe video tidak valid.',
            default                  => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($id > 0 ? '/guru/video/form/' . $id : '/guru/video/form');
        }

        // Parse YouTube/GDrive URL ke embed URL
        if ($tipe === 'youtube' && $urlEmbed !== '') {
            $urlEmbed = $this->parseYoutubeUrl($urlEmbed);
        } elseif ($tipe === 'gdrive' && $urlEmbed !== '') {
            $urlEmbed = $this->parseGdriveUrl($urlEmbed);
        }

        if ($tipe !== 'upload' && $urlEmbed === '') {
            Flash::error('URL video wajib diisi untuk tipe YouTube/Google Drive.');
            redirect($id > 0 ? '/guru/video/form/' . $id : '/guru/video/form');
        }

        $file     = null;
        $fileAsli = null;

        if ($tipe === 'upload') {
            $adaBerkas = ($_FILES['berkas']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

            if ($id === 0 && !$adaBerkas) {
                Flash::error('File video wajib diunggah.');
                redirect('/guru/video/form');
            }

            if ($adaBerkas) {
                $uploader = new FileUploader(null, 100 * 1024 * 1024); // 100 MB

                try {
                    $berkas = $uploader->simpan($_FILES['berkas']);
                    $file     = $berkas['nama'];
                    $fileAsli = $berkas['asli'];
                } catch (BerkasTidakValid $e) {
                    Flash::error($e->getMessage());
                    redirect($id > 0 ? '/guru/video/form/' . $id : '/guru/video/form');
                }
            }
        }

        if ($id > 0) {
            $model->ubah($id, $pengampuId, $judul, $deskripsi, $tipe !== 'upload' ? $urlEmbed : $lama['url_embed'], $durasi !== '' ? $durasi : null);

            if ($file !== null) {
                $fileLama = (string) ($lama['file'] ?? '');
                $model->gantiFile($id, $file, $fileAsli);

                if ($fileLama !== '') {
                    (new FileUploader())->hapus($fileLama);
                }
            }

            Flash::sukses('Video berhasil diubah.');
        } else {
            $model->buat(
                $pengampuId, $judul, $deskripsi, $tipe,
                $file, $fileAsli,
                $tipe !== 'upload' ? $urlEmbed : null,
                $durasi !== '' ? $durasi : null
            );
            Flash::sukses('Video berhasil ditambahkan.');
        }

        redirect('/guru/video');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $guruId = (int) Auth::id();
        $id     = (int) post('id', '0');
        $model  = new Video();
        $video  = $id > 0 ? $model->cariMilikGuru($id, $guruId) : null;

        if ($video === null) {
            throw HttpException::terlarang('Video ini bukan milik Anda.');
        }

        $model->hapus($id);

        if (($video['file'] ?? '') !== '') {
            (new FileUploader())->hapus((string) $video['file']);
        }

        Flash::sukses('Video berhasil dihapus.');
        redirect('/guru/video');
    }

    public function stream(string $id): void
    {
        $video = (new Video())->cariMilikGuru($this->idWajib($id), (int) Auth::id());

        if ($video === null || $video['tipe'] !== 'upload') {
            throw HttpException::tidakAda('Video tidak ditemukan.');
        }

        (new FileDownloader())->kirim((string) $video['file'], (string) $video['file_asli']);
    }

    private function parseYoutubeUrl(string $url): string
    {
        // Extract YouTube video ID from various URL formats
        $videoId = '';

        if (preg_match('#(?:youtube\.com/(?:watch\?v=|embed/|v/)|youtu\.be/)([a-zA-Z0-9_-]{11})#', $url, $m)) {
            $videoId = $m[1];
        }

        return $videoId !== '' ? 'https://www.youtube.com/embed/' . $videoId : $url;
    }

    private function parseGdriveUrl(string $url): string
    {
        // Extract Google Drive file ID
        if (preg_match('#/d/([a-zA-Z0-9_-]+)#', $url, $m)) {
            return 'https://drive.google.com/file/d/' . $m[1] . '/preview';
        }

        return $url;
    }
}
