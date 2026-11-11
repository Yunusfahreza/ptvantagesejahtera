<?php

use App\Core\Config;
use App\Core\Csrf;
use App\Core\Flash;

$pesan   = Flash::ambilSemua();
$cssPath = __DIR__ . '/../../../public/assets/css/app.css';
$cssVer  = is_file($cssPath) ? filemtime($cssPath) : time();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="<?= e(Csrf::token()) ?>">
    <meta name="theme-color" content="#edf2f7">
    <title><?= e($judul ?? Config::get('app.nama')) ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= e(aset('css/app.css')) ?>?v=<?= $cssVer ?>">
</head>
<body>

<!-- 3D Liquid Backdrop -->
<div class="antigravity-backdrop" aria-hidden="true" 
     style="background-image: url('<?= e(aset('images/3d-fluid-backdrop.jpg')) ?>');">
    <div class="ambient-softbox"></div>
</div>

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-4 py-lg-5" style="position: relative; z-index: 10;">
    <div class="w-100" style="max-width: 1180px;">
        <?php foreach ($pesan as $p): ?>
            <div class="alert alert-<?= e($p['tipe']) ?> alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 20px;">
                <span><?= e($p['pesan']) ?></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endforeach; ?>

        <?= $isi ?? '' ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(aset('js/app.js')) ?>?v=<?= $cssVer ?>"></script>
</body>
</html>
