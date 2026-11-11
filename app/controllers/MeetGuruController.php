<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\GoogleMeet;
use App\Models\Pengampu;

/**
 * Manajemen sesi kelas tatap muka daring Google Meet untuk peran Guru.
 */
final class MeetGuruController extends Controller
{
    public function index(): void
    {
        $guruId = (int) Auth::id();

        $this->render('guru/meet/index', [
            'judul'    => 'Google Meet & Kelas Live',
            'data'     => (new GoogleMeet())->milikGuru($guruId),
            'pengampu' => (new Pengampu())->milikGuru($guruId),
        ]);
    }

    public function form(?string $id = null): void
    {
        $guruId = (int) Auth::id();
        $meet   = null;

        if ($id !== null) {
            $meet = (new GoogleMeet())->cariMilikGuru($this->idWajib($id), $guruId);

            if ($meet === null) {
                throw HttpException::terlarang('Sesi Google Meet ini bukan milik Anda.');
            }
        }

        $this->render('guru/meet/form', [
            'judul'    => $meet === null ? 'Jadwalkan Google Meet' : 'Ubah Jadwal Google Meet',
            'meet'     => $meet,
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
        $linkInput  = post('link_meet');
        $tanggal    = post('tanggal');
        $jamMulai   = post('jam_mulai');
        $jamSelesai = post('jam_selesai');
        $status     = post('status', 'terjadwal');

        $model = new GoogleMeet();
        $lama  = $id > 0 ? $model->cariMilikGuru($id, $guruId) : null;

        if ($id > 0 && $lama === null) {
            throw HttpException::terlarang('Sesi Google Meet ini bukan milik Anda.');
        }

        $targetRedirect = $id > 0 ? '/guru/meet/form/' . $id : '/guru/meet/form';

        if ((new Pengampu())->cariMilikGuru($pengampuId, $guruId) === null) {
            Flash::error('Kelas dan mata pelajaran yang dipilih bukan yang Anda ampu.');
            redirect($targetRedirect);
        }

        $linkMeet = GoogleMeet::normalisasiLink($linkInput);

        $error = match (true) {
            $judul === ''                                                 => 'Judul pertemuan wajib diisi.',
            mb_strlen($judul) > 150                                       => 'Judul pertemuan maksimal 150 karakter.',
            $linkMeet === null                                            => 'Masukkan tautan meet.google.com/xxx-xxxx-xxx atau kode meeting yang valid.',
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)                => 'Format tanggal tidak valid.',
            !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jamMulai)            => 'Jam mulai wajib diisi dengan format HH:MM.',
            !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $jamSelesai)          => 'Jam selesai wajib diisi dengan format HH:MM.',
            strcmp($jamSelesai, $jamMulai) <= 0                           => 'Jam selesai harus lebih lambat daripada jam mulai.',
            !in_array($status, ['terjadwal', 'berlangsung', 'selesai'], true) => 'Status pertemuan tidak valid.',
            default                                                       => null,
        };

        if ($error !== null) {
            Flash::error($error);
            redirect($targetRedirect);
        }

        if ($id > 0) {
            $model->ubah($id, $pengampuId, $judul, $deskripsi, $linkMeet, $tanggal, $jamMulai, $jamSelesai, $status);
            Flash::sukses('Jadwal Google Meet berhasil diperbarui.');
        } else {
            $model->buat($pengampuId, $judul, $deskripsi, $linkMeet, $tanggal, $jamMulai, $jamSelesai, $status);
            Flash::sukses('Jadwal Google Meet berhasil dibuat dan dibagikan ke kelas.');
        }

        redirect('/guru/meet');
    }

    public function status(): void
    {
        $this->wajibPost();

        $guruId = (int) Auth::id();
        $id     = (int) post('id', '0');
        $status = post('status', '');

        if (!in_array($status, ['terjadwal', 'berlangsung', 'selesai'], true)) {
            Flash::error('Status tidak valid.');
            redirect('/guru/meet');
        }

        $model = new GoogleMeet();
        $meet  = $id > 0 ? $model->cariMilikGuru($id, $guruId) : null;

        if ($meet === null) {
            throw HttpException::terlarang('Sesi Google Meet ini bukan milik Anda.');
        }

        $model->ubahStatus($id, $status);

        $pesan = match ($status) {
            'berlangsung' => 'Sesi Google Meet ditandai Sedang Berlangsung (LIVE). Siswa kini melihat pengingat aktif.',
            'selesai'     => 'Sesi Google Meet telah ditandai Selesai.',
            default       => 'Sesi Google Meet dijadwalkan kembali.',
        };

        Flash::sukses($pesan);
        redirect('/guru/meet');
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $guruId = (int) Auth::id();
        $id     = (int) post('id', '0');
        $model  = new GoogleMeet();
        $meet   = $id > 0 ? $model->cariMilikGuru($id, $guruId) : null;

        if ($meet === null) {
            throw HttpException::terlarang('Sesi Google Meet ini bukan milik Anda.');
        }

        $model->hapus($id);
        Flash::sukses('Jadwal Google Meet berhasil dihapus.');
        redirect('/guru/meet');
    }
}
