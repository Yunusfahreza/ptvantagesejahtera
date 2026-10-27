<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Pengampu;
use App\Models\Soal;
use DateTimeImmutable;

/**
 * F10: guru mengelola kuis dan soal pilihan ganda.
 */
final class KuisGuruController extends Controller
{
    private const OPSI = ['a', 'b', 'c', 'd', 'e'];
    private const DURASI_MAKS = 480;

    public function index(): void
    {
        $guruId = (int) Auth::id();

        $this->render('guru/kuis/index', [
            'judul'    => 'Kuis',
            'data'     => (new Kuis())->milikGuru($guruId),
            'pengampu' => (new Pengampu())->milikGuru($guruId),
        ]);
    }

    public function form(?string $id = null): void
    {
        $guruId = (int) Auth::id();
        $kuis   = null;

        if ($id !== null) {
            $kuis = (new Kuis())->cariMilikGuru($this->idWajib($id), $guruId);

            if ($kuis === null) {
                throw HttpException::terlarang('Kuis ini bukan milik Anda.');
            }
        }

        $this->render('guru/kuis/form', [
            'judul'       => $kuis === null ? 'Buat Kuis' : 'Ubah Kuis',
            'kuis'        => $kuis,
            'pengampu'    => (new Pengampu())->milikGuru($guruId),
            'durasi_maks' => self::DURASI_MAKS,
        ]);
    }

    public function simpan(): void
    {
        $this->wajibPost();

        $guruId     = (int) Auth::id();
        $id         = (int) post('id', '0');
        $pengampuId = (int) post('pengampu_id', '0');
        $judul      = post('judul');
        $durasi     = (int) post('durasi_menit', '0');
        $mulai      = $this->bacaDatetime(post('mulai'));
        $selesai    = $this->bacaDatetime(post('selesai'));

        $model = new Kuis();

        if ($id > 0 && $model->cariMilikGuru($id, $guruId) === null) {
            throw HttpException::terlarang('Kuis ini bukan milik Anda.');
        }

        $kembali = $id > 0 ? '/guru/kuis/form/' . $id : '/guru/kuis/form';

        if ((new Pengampu())->cariMilikGuru($pengampuId, $guruId) === null) {
            Flash::error('Kelas dan mata pelajaran yang dipilih bukan yang Anda ampu.');
            redirect($kembali);
        }

        $error = match (true) {
            $judul === ''               => 'Judul kuis wajib diisi.',
            mb_strlen($judul) > 150     => 'Judul maksimal 150 karakter.',
            $durasi < 1                 => 'Durasi minimal 1 menit.',
            $durasi > self::DURASI_MAKS => 'Durasi maksimal ' . self::DURASI_MAKS . ' menit.',
            $mulai === null             => 'Waktu mulai wajib diisi dengan benar.',
            $selesai === null           => 'Waktu selesai wajib diisi dengan benar.',
            $selesai <= $mulai          => 'Waktu selesai harus setelah waktu mulai.',
            default                     => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($kembali);
        }

        if ($id > 0) {
            $model->ubah($id, $pengampuId, $judul, $durasi, $mulai, $selesai);
            Flash::sukses('Kuis berhasil diubah.');
            redirect('/guru/kuis');
        }

        $baru = $model->buat($pengampuId, $judul, $durasi, $mulai, $selesai);
        Flash::sukses('Kuis dibuat. Sekarang tambahkan soalnya.');
        redirect('/guru/kuis/soal/' . $baru);
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $guruId = (int) Auth::id();
        $id     = (int) post('id', '0');
        $model  = new Kuis();

        if ($id <= 0 || $model->cariMilikGuru($id, $guruId) === null) {
            throw HttpException::terlarang('Kuis ini bukan milik Anda.');
        }

        $jumlah = $model->jumlahHasil($id);

        if ($jumlah > 0) {
            Flash::error(
                'Kuis tidak bisa dihapus karena sudah dikerjakan ' . $jumlah . ' siswa.'
            );
            redirect('/guru/kuis');
        }

        $model->hapus($id);
        Flash::sukses('Kuis berhasil dihapus.');
        redirect('/guru/kuis');
    }

    // --- Soal ------------------------------------------------------------

    public function soal(string $id): void
    {
        $guruId = (int) Auth::id();
        $kuis   = (new Kuis())->cariMilikGuru($this->idWajib($id), $guruId);

        if ($kuis === null) {
            throw HttpException::terlarang('Kuis ini bukan milik Anda.');
        }

        $editId = query_int('edit');
        $model  = new Soal();

        $this->render('guru/kuis/soal', [
            'judul'    => 'Soal: ' . $kuis['judul'],
            'kuis'     => $kuis,
            'data'     => $model->untukGuru((int) $kuis['id']),
            'edit'     => $editId > 0 ? $model->cariDiKuis($editId, (int) $kuis['id']) : null,
            // Kuis yang sudah ada hasilnya: soal dibekukan (PRD F10).
            'terkunci' => (int) $kuis['jumlah_peserta'] > 0,
        ]);
    }

    public function simpanSoal(): void
    {
        $this->wajibPost();

        $guruId = (int) Auth::id();
        $kuisId = (int) post('kuis_id', '0');
        $id     = (int) post('id', '0');

        $kuis = $kuisId > 0 ? (new Kuis())->cariMilikGuru($kuisId, $guruId) : null;

        if ($kuis === null) {
            throw HttpException::terlarang('Kuis ini bukan milik Anda.');
        }

        $kembali = '/guru/kuis/soal/' . $kuisId;

        if ((int) $kuis['jumlah_peserta'] > 0) {
            Flash::error('Kuis sudah dikerjakan siswa, soal tidak bisa diubah lagi.');
            redirect($kembali);
        }

        $model      = new Soal();
        $pertanyaan = post('pertanyaan');
        $kunci      = strtolower(post('kunci'));

        // Opsi A dan B wajib; C sampai E boleh kosong dan disimpan NULL.
        $opsi = [];
        foreach (self::OPSI as $huruf) {
            $nilai = post('opsi_' . $huruf);
            $opsi[$huruf] = $nilai === '' ? null : $nilai;
        }

        $error = match (true) {
            $pertanyaan === ''              => 'Pertanyaan wajib diisi.',
            mb_strlen($pertanyaan) > 2000   => 'Pertanyaan maksimal 2000 karakter.',
            $opsi['a'] === null             => 'Opsi A wajib diisi.',
            $opsi['b'] === null             => 'Opsi B wajib diisi.',
            !in_array($kunci, self::OPSI, true) => 'Kunci jawaban harus A sampai E.',
            // Kunci wajib menunjuk opsi yang benar-benar terisi.
            $opsi[$kunci] === null          => 'Kunci jawaban menunjuk opsi yang kosong.',
            default                         => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($id > 0 ? $kembali . '?edit=' . $id : $kembali);
        }

        if ($id > 0) {
            if ($model->cariDiKuis($id, $kuisId) === null) {
                throw HttpException::tidakAda('Soal tidak ditemukan pada kuis ini.');
            }

            $model->ubah($id, $pertanyaan, $opsi, $kunci);
            Flash::sukses('Soal berhasil diubah.');
        } else {
            $model->buat($kuisId, $pertanyaan, $opsi, $kunci);
            Flash::sukses('Soal berhasil ditambahkan.');
        }

        redirect($kembali);
    }

    public function hapusSoal(): void
    {
        $this->wajibPost();

        $guruId = (int) Auth::id();
        $kuisId = (int) post('kuis_id', '0');
        $id     = (int) post('id', '0');

        $kuis = $kuisId > 0 ? (new Kuis())->cariMilikGuru($kuisId, $guruId) : null;

        if ($kuis === null) {
            throw HttpException::terlarang('Kuis ini bukan milik Anda.');
        }

        $kembali = '/guru/kuis/soal/' . $kuisId;

        if ((int) $kuis['jumlah_peserta'] > 0) {
            Flash::error('Kuis sudah dikerjakan siswa, soal tidak bisa dihapus lagi.');
            redirect($kembali);
        }

        $model = new Soal();

        if ($id <= 0 || $model->cariDiKuis($id, $kuisId) === null) {
            throw HttpException::tidakAda('Soal tidak ditemukan pada kuis ini.');
        }

        $model->hapus($id);
        Flash::sukses('Soal berhasil dihapus.');
        redirect($kembali);
    }

    // --- Hasil -----------------------------------------------------------

    public function hasil(string $id): void
    {
        $guruId = (int) Auth::id();
        $kuis   = (new Kuis())->cariMilikGuru($this->idWajib($id), $guruId);

        if ($kuis === null) {
            throw HttpException::terlarang('Kuis ini bukan milik Anda.');
        }

        $model = new HasilKuis();

        // Penegakan di server: percobaan yang waktunya habis tapi belum
        // final difinalisasi di sini (PRD F11).
        $model->finalisasiYangKedaluwarsa((int) $kuis['id']);

        $this->render('guru/kuis/hasil', [
            'judul' => 'Hasil: ' . $kuis['judul'],
            'kuis'  => $kuis,
            'siswa' => $model->daftarUntukKuis((int) $kuis['id'], (int) $kuis['kelas_id']),
        ]);
    }

    private function bacaDatetime(string $nilai): ?string
    {
        if ($nilai === '') {
            return null;
        }

        foreach (['Y-m-d\TH:i', 'Y-m-d\TH:i:s', 'Y-m-d H:i', 'Y-m-d H:i:s'] as $format) {
            $tanggal = DateTimeImmutable::createFromFormat($format, $nilai);

            if ($tanggal !== false) {
                return $tanggal->format('Y-m-d H:i:s');
            }
        }

        return null;
    }
}
