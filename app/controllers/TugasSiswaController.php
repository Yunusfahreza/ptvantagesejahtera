<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\BerkasTidakValid;
use App\Core\Controller;
use App\Core\FileDownloader;
use App\Core\FileUploader;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\Pengumpulan;
use App\Models\Tugas;

/**
 * F08: siswa mengumpulkan tugas.
 *
 * Satu pengumpulan per siswa per tugas. Kumpul ulang diizinkan selama
 * belum dinilai; setelah dinilai terkunci. Status tepat_waktu/terlambat
 * dan waktunya ditentukan server, bukan klien.
 */
final class TugasSiswaController extends Controller
{
    public function index(): void
    {
        $siswaId = (int) Auth::id();

        $this->render('siswa/tugas/index', [
            'judul' => 'Tugas',
            'data'  => (new Tugas())->untukSiswa($siswaId, $this->kelasSiswa()),
        ]);
    }

    public function detail(string $id): void
    {
        $siswaId = (int) Auth::id();
        $tugas   = (new Tugas())->cariUntukSiswa($this->idWajib($id), $siswaId, $this->kelasSiswa());

        if ($tugas === null) {
            throw HttpException::terlarang('Tugas ini bukan untuk kelas Anda.');
        }

        $uploader = new FileUploader();

        $this->render('siswa/tugas/detail', [
            'judul'    => $tugas['judul'],
            'tugas'    => $tugas,
            'terkunci' => $tugas['nilai'] !== null,
            'maks'     => ukuran_file($uploader->maksByte()),
            'ekstensi' => $uploader->daftarEkstensi(),
        ]);
    }

    public function kumpul(): void
    {
        $this->wajibPost();

        $siswaId = (int) Auth::id();
        $kelasId = $this->kelasSiswa();
        $tugasId = (int) post('tugas_id', '0');

        $tugas = $tugasId > 0
            ? (new Tugas())->cariUntukSiswa($tugasId, $siswaId, $kelasId)
            : null;

        if ($tugas === null) {
            throw HttpException::terlarang('Tugas ini bukan untuk kelas Anda.');
        }

        $kembali = '/siswa/tugas/detail/' . $tugasId;

        // Sudah dinilai -> terkunci, tidak boleh kumpul ulang.
        if ($tugas['nilai'] !== null) {
            Flash::error('Tugas ini sudah dinilai sehingga tidak bisa dikumpulkan ulang.');
            redirect($kembali);
        }

        $uploader = new FileUploader();

        try {
            $berkas = $uploader->simpan($_FILES['berkas'] ?? null);
        } catch (BerkasTidakValid $e) {
            Flash::error($e->getMessage());
            redirect($kembali);
        }

        $model = new Pengumpulan();
        $lama  = $model->cariMilikSiswa($tugasId, $siswaId);

        if ($lama === null) {
            $model->simpanBaru($tugasId, $siswaId, $berkas['nama'], $berkas['asli']);
        } else {
            $model->gantiBerkas((int) $lama['id'], $berkas['nama'], $berkas['asli']);
            $uploader->hapus((string) $lama['file']);
        }

        // Status final dibaca ulang dari server supaya pesan yang tampil akurat.
        $baru = $model->cariMilikSiswa($tugasId, $siswaId);

        Flash::sukses(
            ($baru['status'] ?? '') === 'terlambat'
                ? 'Tugas tersimpan dengan status TERLAMBAT (melewati tenggat).'
                : 'Tugas tersimpan dengan status tepat waktu.'
        );

        redirect($kembali);
    }

    public function unduhLampiran(string $id): void
    {
        $tugas = (new Tugas())->cariUntukSiswa(
            $this->idWajib($id),
            (int) Auth::id(),
            $this->kelasSiswa()
        );

        if ($tugas === null) {
            throw HttpException::terlarang('Tugas ini bukan untuk kelas Anda.');
        }

        if ($tugas['lampiran'] === null) {
            throw HttpException::tidakAda('Tugas ini tidak punya lampiran.');
        }

        (new FileDownloader())->kirim(
            (string) $tugas['lampiran'],
            (string) ($tugas['lampiran_asli'] ?? 'lampiran')
        );
    }

    /**
     * Siswa hanya boleh mengunduh berkas pengumpulan miliknya sendiri.
     */
    public function unduhBerkasSendiri(string $id): void
    {
        $tugas = (new Tugas())->cariUntukSiswa(
            $this->idWajib($id),
            (int) Auth::id(),
            $this->kelasSiswa()
        );

        if ($tugas === null || $tugas['pengumpulan_id'] === null) {
            throw HttpException::terlarang('Anda belum punya pengumpulan untuk tugas ini.');
        }

        (new FileDownloader())->kirim(
            (string) $tugas['pengumpulan_file'],
            (string) $tugas['pengumpulan_file_asli']
        );
    }

    private function kelasSiswa(): int
    {
        $kelasId = Auth::kelasId();

        if ($kelasId === null) {
            throw HttpException::terlarang(
                'Akun Anda belum ditempatkan di kelas. Hubungi administrator sekolah.'
            );
        }

        return $kelasId;
    }
}
