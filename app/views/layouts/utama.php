<?php

use App\Core\Auth;
use App\Core\Config;
use App\Core\Csrf;
use App\Core\Flash;

$user  = Auth::user();
$peran = Auth::peran();
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
    <!-- Floating Glass Island (Navbar) -->
    <header class="spatial-nav-wrapper">
        <nav class="navbar navbar-expand-lg spatial-nav-island">
            <div class="container-fluid px-2">
                <a class="navbar-brand me-3" href="<?= e(url($user ? Auth::dashboardPath() : '/')) ?>">
                    <span class="spatial-brand-dot"></span>
                    <span class="fw-semibold"><?= e(Config::get('app.nama')) ?></span>
                </a>

                <?php if ($user !== null): ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama"
                            aria-controls="menuUtama" aria-expanded="false" aria-label="Buka menu">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="menuUtama">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                            <?php foreach (require __DIR__ . '/menu.php' as $item): ?>
                                <?php
                                $aktif = str_ends_with(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '', $item['path']);
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $aktif ? 'active' : '' ?>" href="<?= e(url($item['path'])) ?>">
                                        <?= e($item['label']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="dropdown ms-lg-2">
                            <button class="btn spatial-profile-pill dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-white-50">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <span><?= e($user['nama']) ?></span>
                                <span class="spatial-profile-badge"><?= e($peran) ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="<?= e(url('/profil')) ?>">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <circle cx="12" cy="12" r="3"/>
                                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                        </svg>
                                        Profil Pengguna
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="<?= e(url('/password')) ?>">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                        </svg>
                                        Ganti Password
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="post" action="<?= e(url('/logout')) ?>" class="px-2 py-1">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger w-100">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                                <polyline points="16 17 21 12 16 7"/>
                                                <line x1="21" y1="12" x2="9" y2="12"/>
                                            </svg>
                                            Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container-fluid container-xl py-4">
        <?php foreach ($pesan as $p): ?>
            <div class="alert alert-<?= e($p['tipe']) ?> alert-dismissible fade show" role="alert">
                <?= e($p['pesan']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endforeach; ?>

        <?= $isi ?? '' ?>
    </main>

    <footer class="text-center py-4 small">
        <?= e(Config::get('app.nama')) ?> &middot; PT. Vantage Sejahtera
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(aset('js/app.js')) ?>"></script>
<?php if (!empty($skrip)): ?>
    <script src="<?= e(aset('js/' . $skrip . '.js')) ?>"></script>
<?php endif; ?>
</body>
</html>
