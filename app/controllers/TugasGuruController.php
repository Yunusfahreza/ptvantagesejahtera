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
use App\Models\Pengumpulan;
use App\Models\Tugas;

/**
 * F07 dan F09: guru mengelola tugas dan menilai pengumpulan.
 */
final class TugasGuruController extends Controller
{
    public function index(): void
    {
        $guruId = (int) Auth::id();

        $this->render('guru/tugas/index', [
            'judul'    => 'Tugas',
            'data'     => (new Tugas())->milikGuru($guruId),
            'pengampu' => (new Pengampu())->milikGuru($guruId),
        ]);
    }

    public function form(?string $id = null): void
    {
        $guruId = (int) Auth::id();
        $tugas  = null;

        if ($id !== null) {
            $tugas = (new Tugas())->cariMilikGuru($this->idWajib($id), $guruId);

            if ($tugas === null) {
                throw HttpException::terlarang('Tugas ini bukan milik Anda.');
            }
        }

        $uploader = new FileUploader();

        $this->render('guru/tugas/form', [
            'judul'    => $tugas === null ? 'Buat Tugas' : 'Ubah Tugas',
            'tugas'    => $tugas,
            'pengampu' => (new Pengampu())->milikGuru($guruId),
            'maks'     => ukuran_file($uploader->maksByte()),
            'ekstensi' => $uploader->daftarEkstensi(),
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
        $tenggat    = $this->bacaDatetime(post('tenggat'));

        $model = new Tugas();
        $lama  = $id > 0 ? $model->cariMilikGuru($id, $guruId) : null;

        if ($id > 0 && $lama === null) {
            throw HttpException::terlarang('Tugas ini bukan milik Anda.');
        }

        $kembali = $id > 0 ? '/guru/tugas/form/' . $id : '/guru/tugas/form';

        if ((new Pengampu())->cariMilikGuru($pengampuId, $guruId) === null) {
            Flash::error('Kelas dan mata pelajaran yang dipilih bukan yang Anda ampu.');
            redirect($kembali);
        }

        $error = match (true) {
            $judul === ''                => 'Judul tugas wajib diisi.',
            mb_strlen($judul) > 150      => 'Judul maksimal 150 karakter.',
            mb_strlen($deskripsi) > 5000 => 'Deskripsi maksimal 5000 karakter.',
            $tenggat === null            => 'Tenggat wajib diisi dengan tanggal dan jam yang benar.',
            default                      => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($kembali);
        }

        $uploader  = new FileUploader();
        $adaBerkas = ($_FILES['berkas']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

        try {
            $berkas = $adaBerkas ? $uploader->simpan($_FILES['berkas']) : null;
        } catch (BerkasTidakValid $e) {
            Flash::error($e->getMessage());
            redirect($kembali);
        }

        if ($id > 0) {
            $model->ubah($id, $pengampuId, $judul, $deskripsi, $tenggat);

            if ($berkas !== null) {
                $lampiranLama = $lama['lampiran'] === null ? null : (string) $lama['lampiran'];
                $model->gantiLampiran($id, $berkas['nama'], $berkas['asli']);
                $uploader->hapus($lampiranLama);
            }

            Flash::sukses('Tugas berhasil diubah.');
        } else {
            $model->buat(
                $pengampuId,
                $judul,
                $deskripsi,
                $tenggat,
                $berkas['nama'] ?? null,
                $berkas['asli'] ?? null
            );
            Flash::sukses('Tugas berhasil dibuat.');
        }

        redirect('/guru/tugas');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $guruId = (int) Auth::id();
        $id     = (int) post('id', '0');
        $model  = new Tugas();
        $tugas  = $id > 0 ? $model->cariMilikGuru($id, $guruId) : null;

        if ($tugas === null) {
            throw HttpException::terlarang('Tugas ini bukan milik Anda.');
        }

        // PRD F07: tugas yang sudah punya pengumpulan tidak boleh dihapus.
        $jumlah = $model->jumlahPengumpulan($id);

        if ($jumlah > 0) {
            Flash::error(
                'Tugas tidak bisa dihapus karena sudah ada ' . $jumlah
                . ' pengumpulan dari siswa.'
            );
            redirect('/guru/tugas');
        }

        $model->hapus($id);
        (new FileUploader())->hapus($tugas['lampiran'] === null ? null : (string) $tugas['lampiran']);

        Flash::sukses('Tugas berhasil dihapus.');
        redirect('/guru/tugas');
    }

    // --- Penilaian (F09) -------------------------------------------------

    public function nilai(string $id): void
    {
        $guruId = (int) Auth::id();
        $tugas  = (new Tugas())->cariMilikGuru($this->idWajib($id), $guruId);

        if ($tugas === null) {
            throw HttpException::terlarang('Tugas ini bukan milik Anda.');
        }

        $this->render('guru/tugas/nilai', [
            'judul' => 'Nilai: ' . $tugas['judul'],
            'tugas' => $tugas,
            'siswa' => (new Pengumpulan())->daftarUntukTugas(
                (int) $tugas['id'],
                (int) $tugas['kelas_id']
            ),
        ]);
    }

    public function simpanNilai(): void
    {
        $this->wajibPost();

        $guruId        = (int) Auth::id();
        $pengumpulanId = (int) post('pengumpulan_id', '0');
        $nilaiMentah   = post('nilai');
        $komentar      = post('komentar');

        $model = new Pengumpulan();
        // Kepemilikan: pengumpulan hanya terbaca kalau tugasnya milik guru ini (T12).
        $baris = $pengumpulanId > 0 ? $model->cariUntukGuru($pengumpulanId, $guruId) : null;

        if ($baris === null) {
            throw HttpException::terlarang('Pengumpulan ini bukan pada tugas Anda.');
        }

        $kembali = '/guru/tugas/nilai/' . $baris['tugas_id'];

        if (!is_numeric($nilaiMentah)) {
            Flash::error('Nilai wajib berupa angka.');
            redirect($kembali);
        }

        $nilai = (float) $nilaiMentah;

        if ($nilai < 0 || $nilai > 100) {
            Flash::error('Nilai harus di antara 0 dan 100.');
            redirect($kembali);
        }

        if (mb_strlen($komentar) > 2000) {
            Flash::error('Komentar maksimal 2000 karakter.');
            redirect($kembali);
        }

        $model->simpanNilai($pengumpulanId, round($nilai, 2), $komentar);

        Flash::sukses('Nilai untuk ' . $baris['siswa_nama'] . ' tersimpan.');
        redirect($kembali);
    }

    // --- Unduhan ---------------------------------------------------------

    public function unduhLampiran(string $id): void
    {
        $tugas = (new Tugas())->cariMilikGuru($this->idWajib($id), (int) Auth::id());

        if ($tugas === null) {
            throw HttpException::terlarang('Tugas ini bukan milik Anda.');
        }

        if ($tugas['lampiran'] === null) {
            throw HttpException::tidakAda('Tugas ini tidak punya lampiran.');
        }

        (new FileDownloader())->kirim(
            (string) $tugas['lampiran'],
            (string) ($tugas['lampiran_asli'] ?? 'lampiran')
        );
    }

    public function unduhPengumpulan(string $id): void
    {
        $baris = (new Pengumpulan())->cariUntukGuru($this->idWajib($id), (int) Auth::id());

        if ($baris === null) {
            throw HttpException::terlarang('Pengumpulan ini bukan pada tugas Anda.');
        }

        (new FileDownloader())->kirim((string) $baris['file'], (string) $baris['file_asli']);
    }

    /**
     * Terima nilai dari input datetime-local dan normalkan ke format MySQL.
     * Mengembalikan null kalau tidak bisa diurai.
     */
    private function bacaDatetime(string $nilai): ?string
    {
        if ($nilai === '') {
            return null;
        }

        foreach (['Y-m-d\TH:i', 'Y-m-d\TH:i:s', 'Y-m-d H:i', 'Y-m-d H:i:s'] as $format) {
            $tanggal = \DateTimeImmutable::createFromFormat($format, $nilai);

            if ($tanggal !== false) {
                return $tanggal->format('Y-m-d H:i:s');
            }
        }

        return null;
    }
}
