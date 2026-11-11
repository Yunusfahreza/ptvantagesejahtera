<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\HttpException;
use App\Models\GoogleMeet;

/**
 * Monitoring sesi Google Meet seluruh sekolah untuk Admin.
 */
final class MeetAdminController extends Controller
{
    public function index(): void
    {
        $this->render('admin/meet/index', [
            'judul' => 'Monitoring Google Meet',
            'data'  => (new GoogleMeet())->semuaUntukAdmin(),
        ]);
    }

    public function hapus(): void
    {
        $this->wajibPost();

        $id    = (int) post('id', '0');
        $model = new GoogleMeet();
        $meet  = $id > 0 ? $model->cariDenganId($id) : null;

        if ($meet === null) {
            throw HttpException::tidakAda('Data Google Meet tidak ditemukan.');
        }

        $model->hapus($id);
        Flash::sukses('Jadwal Google Meet berhasil dihapus.');
        redirect('/admin/meet');
    }
}
