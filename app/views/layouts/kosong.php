<?php

use App\Core\Config;
use App\Core\Csrf;
use App\Core\Flash;

$pesan = Flash::ambilSemua();
?>
<!doctype html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="<?= e(Csrf::token()) ?>">
    <meta name="theme-color" content="#070709">
    <title><?= e($judul ?? Config::get('app.nama')) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= e(aset('css/app.css')) ?>">
</head>
<body>

<!-- Ambient Atmospheric Orbs (Spatial Refraction Field) -->
<div class="ambient-scene" aria-hidden="true">
    <div class="ambient-orb ambient-orb-1"></div>
    <div class="ambient-orb ambient-orb-2"></div>
</div>

<div class="spatial-shell">
    <main class="container py-5" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        <div style="width: 100%; max-width: 440px;">
            <?php foreach ($pesan as $p): ?>
                <div class="alert alert-<?= e($p['tipe']) ?>" role="alert"><?= e($p['pesan']) ?></div>
            <?php endforeach; ?>

            <?= $isi ?? '' ?>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
