<?php

use App\Core\Auth;

$ikon = match ($status) {
    403     => 'Akses Ditolak',
    404     => 'Halaman Tidak Ditemukan',
    default => 'Terjadi Kesalahan',
};
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center p-4 p-md-5">
                <p class="display-4 fw-bold text-danger mb-1"><?= e($status) ?></p>
                <h1 class="h4 mb-3"><?= e($ikon) ?></h1>
                <p class="text-body-secondary"><?= e($pesan) ?></p>
                <a class="btn btn-primary mt-2"
                   href="<?= e(url(Auth::sudahLogin() ? Auth::dashboardPath() : '/login')) ?>">
                    <?= Auth::sudahLogin() ? 'Kembali ke Dashboard' : 'Ke Halaman Login' ?>
                </a>
            </div>
        </div>
    </div>
</div>
