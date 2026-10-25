<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Soal;

/**
 * F11: siswa mengerjakan kuis.
 *
 * Aturan yang ditegakkan di server:
 *   - hanya bisa mulai di dalam jendela mulai..selesai
 *   - satu percobaan per siswa per kuis
 *   - batas pengerjaan = MIN(mulai_at + durasi, kuis.selesai)
 *   - setiap request setelah batas memicu finalisasi dari jawaban tersimpan
 *   - nilai dihitung server, kunci jawaban tidak pernah dikirim ke klien
 */
final class KuisSiswaController extends Controller
{
    private const OPSI = ['a', 'b', 'c', 'd', 'e'];

    public function index(): void
    {
        $siswaId = (int) Auth::id();

        $this->render('siswa/kuis/index', [
            'judul' => 'Kuis',
            'data'  => (new Kuis())->untukSiswa($siswaId, $this->kelasSiswa()),
        ]);
    }

    public function mulai(): void
    {
        $this->wajibPost();

        $siswaId = (int) Auth::id();
        $kelasId = $this->kelasSiswa();
        $kuisId  = (int) post('kuis_id', '0');

        $kuis = $kuisId > 0
            ? (new Kuis())->cariUntukSiswa($kuisId, $siswaId, $kelasId)
            : null;

        if ($kuis === null) {
            throw HttpException::terlarang('Kuis ini bukan untuk kelas Anda.');
        }

        if ((int) $kuis['jumlah_soal'] === 0) {
            Flash::error('Kuis ini belum memiliki soal.');
            redirect('/siswa/kuis');
        }

        $model = new HasilKuis();

        // T10: percobaan kedua diarahkan ke hasil, atau lanjut mengerjakan
        // kalau waktunya masih ada.
        $hasil = $model->mulai($kuisId, $siswaId);

        if ($hasil === null) {
            Flash::error('Kuis sedang tidak dibuka. Periksa jadwal mulai dan selesainya.');
            redirect('/siswa/kuis');
        }

        if (!$hasil['baru']) {
            $percobaan = $model->cariMilikSiswa($kuisId, $siswaId);

            if ($percobaan !== null && $percobaan['selesai_at'] !== null) {
                Flash::info('Anda sudah mengerjakan kuis ini. Berikut hasilnya.');
                redirect('/siswa/kuis/hasil/' . $kuisId);
            }
        }

        redirect('/siswa/kuis/kerjakan/' . $kuisId);
    }

    public function kerjakan(string $id): void
    {
        $siswaId = (int) Auth::id();
        $kuisId  = $this->idWajib($id);
        $kelasId = $this->kelasSiswa();

        if ((new Kuis())->cariUntukSiswa($kuisId, $siswaId, $kelasId) === null) {
            throw HttpException::terlarang('Kuis ini bukan untuk kelas Anda.');
        }

        $model     = new HasilKuis();
        $percobaan = $model->cariMilikSiswa($kuisId, $siswaId);

        if ($percobaan === null) {
            Flash::error('Anda belum memulai kuis ini.');
            redirect('/siswa/kuis');
        }

        // Waktu habis atau sudah final -> finalisasi lalu tampilkan hasil.
        // Inilah yang membuat "tutup tab lalu buka lagi" tetap berujung nilai.
        if ($percobaan['selesai_at'] !== null || (int) $percobaan['waktu_habis'] === 1) {
            $model->finalisasi((int) $percobaan['id']);
            redirect('/siswa/kuis/hasil/' . $kuisId);
        }

        $this->render('siswa/kuis/kerjakan', [
            'judul'     => $percobaan['judul'],
            'percobaan' => $percobaan,
            // Soal dikirim tanpa kolom kunci.
            'soal'      => (new Soal())->untukSiswa($kuisId),
            'jawaban'   => $model->jawabanTersimpan((int) $percobaan['id']),
            // Sisa detik dihitung dari waktu server, bukan jam browser.
            'sisa'      => max(0, strtotime((string) $percobaan['batas_waktu'])
                                  - strtotime((string) $percobaan['waktu_server'])),
            'skrip'     => 'kuis',
        ]);
    }

    /**
     * Autosave satu jawaban lewat AJAX. Membalas JSON, bukan halaman.
     */
    public function jawab(): void
    {
        $this->wajibPostJson();

        $siswaId = (int) Auth::id();
        $kelasId = Auth::kelasId();
        $kuisId  = (int) post('kuis_id', '0');
        $soalId  = (int) post('soal_id', '0');
        $jawaban = strtolower(post('jawaban'));

        if ($kelasId === null || !in_array($jawaban, self::OPSI, true)) {
            $this->json(['ok' => false, 'pesan' => 'Jawaban tidak valid.'], 422);

            return;
        }

        if ((new Kuis())->cariUntukSiswa($kuisId, $siswaId, $kelasId) === null) {
            $this->json(['ok' => false, 'pesan' => 'Kuis ini bukan untuk kelas Anda.'], 403);

            return;
        }

        $model     = new HasilKuis();
        $percobaan = $model->cariMilikSiswa($kuisId, $siswaId);

        if ($percobaan === null) {
            $this->json(['ok' => false, 'pesan' => 'Anda belum memulai kuis ini.'], 409);

            return;
        }

        // Jawaban setelah batas waktu ditolak, dan percobaan langsung difinalisasi.
        if ($percobaan['selesai_at'] !== null || (int) $percobaan['waktu_habis'] === 1) {
            $model->finalisasi((int) $percobaan['id']);

            $this->json([
                'ok'      => false,
                'selesai' => true,
                'pesan'   => 'Waktu sudah habis. Jawaban yang tersimpan telah dinilai.',
            ], 409);

            return;
        }

        if (!$model->simpanJawaban((int) $percobaan['id'], $soalId, $jawaban)) {
            $this->json(['ok' => false, 'pesan' => 'Jawaban tidak bisa disimpan.'], 409);

            return;
        }

        $this->json([
            'ok'   => true,
            'sisa' => max(0, strtotime((string) $percobaan['batas_waktu'])
                             - strtotime((string) $percobaan['waktu_server'])),
        ]);
    }

    public function selesai(): void
    {
        $this->wajibPost();

        $siswaId = (int) Auth::id();
        $kelasId = $this->kelasSiswa();
        $kuisId  = (int) post('kuis_id', '0');

        if ((new Kuis())->cariUntukSiswa($kuisId, $siswaId, $kelasId) === null) {
            throw HttpException::terlarang('Kuis ini bukan untuk kelas Anda.');
        }

        $model     = new HasilKuis();
        $percobaan = $model->cariMilikSiswa($kuisId, $siswaId);

        if ($percobaan === null) {
            Flash::error('Anda belum memulai kuis ini.');
            redirect('/siswa/kuis');
        }

        $model->finalisasi((int) $percobaan['id']);

        Flash::sukses('Kuis selesai. Nilai Anda sudah dihitung.');
        redirect('/siswa/kuis/hasil/' . $kuisId);
    }

    public function hasil(string $id): void
    {
        $siswaId = (int) Auth::id();
        $kuisId  = $this->idWajib($id);
        $kelasId = $this->kelasSiswa();

        $kuis = (new Kuis())->cariUntukSiswa($kuisId, $siswaId, $kelasId);

        if ($kuis === null) {
            throw HttpException::terlarang('Kuis ini bukan untuk kelas Anda.');
        }

        $model     = new HasilKuis();
        $percobaan = $model->cariMilikSiswa($kuisId, $siswaId);

        if ($percobaan === null) {
            Flash::info('Anda belum mengerjakan kuis ini.');
            redirect('/siswa/kuis');
        }

        // Membuka halaman hasil setelah waktu habis juga memicu finalisasi.
        if ($percobaan['selesai_at'] === null && (int) $percobaan['waktu_habis'] === 1) {
            $model->finalisasi((int) $percobaan['id']);
            $percobaan = $model->cariMilikSiswa($kuisId, $siswaId);
        }

        // Masih dalam waktu dan belum submit -> kembalikan ke halaman pengerjaan.
        if ($percobaan['selesai_at'] === null) {
            redirect('/siswa/kuis/kerjakan/' . $kuisId);
        }

        $this->render('siswa/kuis/hasil', [
            'judul'     => 'Hasil ' . $kuis['judul'],
            'kuis'      => $kuis,
            'percobaan' => $percobaan,
            'dijawab'   => count($model->jawabanTersimpan((int) $percobaan['id'])),
        ]);
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
