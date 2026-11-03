<?php

use App\Core\Auth;
use App\Core\Config;
use App\Core\Csrf;
use App\Core\Flash;

$user    = Auth::user();
$peran   = Auth::peran();
$pesan   = Flash::ambilSemua();
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';

// Normalisasi URL untuk penanda aktif menu yang 100% presisi (bahkan di subfolder Laragon)
$basePath = function_exists('base_path') ? base_path() : '';
$appPath  = $uriPath;
if ($basePath !== '' && str_starts_with($appPath, $basePath)) {
    $appPath = substr($appPath, strlen($basePath)) ?: '/';
}
$appPath = '/' . ltrim($appPath, '/');

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
    
    <!-- Google Fonts: Plus Jakarta Sans & Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= e(aset('css/app.css')) ?>?v=<?= $cssVer ?>">
</head>
<body>

<!-- Hidden Working Logout Form with CSRF Protection -->
<form id="logoutFormAction" method="post" action="<?= e(url('/logout')) ?>" style="display: none;">
    <?= csrf_field() ?>
</form>

<!-- ==========================================================================
     3D ETHEREAL FLUID LIQUID STUDIO WALLPAPER (EXACT MATCH TO REFERENCE)
     ========================================================================== -->
<div class="antigravity-backdrop" aria-hidden="true" 
     style="background-image: url('<?= e(aset('images/3d-fluid-backdrop.jpg')) ?>');">
    <div class="ambient-softbox"></div>
</div>

<!-- ==========================================================================
     FLOATING SPATIAL GLASS COCKPIT WINDOW (VISIONOS / MACOS STAGE MANAGER SLATE)
     ========================================================================== -->
<div class="spatial-app-shell">
    <?php if ($user !== null): ?>
    <!-- ── LEFT EMBEDDED SIDEBAR (INDEPENDENT FLOATING TRANSLUCENT ISLAND) ─── -->
    <aside class="spatial-sidebar" id="sidebarMenu">
        <!-- 1. Brand Logo Header -->
        <div class="spatial-sidebar-header">
            <a class="spatial-sidebar-brand" href="<?= e(url(Auth::dashboardPath())) ?>">
                <div class="spatial-brand-icon" style="border-radius: 12px; background: #0f172a; width: 34px; height: 34px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div class="spatial-brand-meta">
                    <div class="spatial-brand-title" style="font-size: 0.92rem; letter-spacing: -0.02em;">LMS SMAN 10</div>
                    <div class="spatial-brand-subtitle" style="font-size: 0.62rem; color: var(--slate-tertiary);">Garut &bull; Belajar</div>
                </div>
            </a>
            <button class="btn btn-sm spatial-mobile-toggle d-lg-none" type="button"
                    onclick="document.getElementById('sidebarMenu').classList.remove('show'); document.getElementById('sidebarBackdrop').classList.remove('show');">
                ✕
            </button>
        </div>

        <!-- 2. Scrollable Navigation Menu (Clean Pill Capsules) -->
        <nav class="spatial-sidebar-nav">
            <div class="spatial-nav-section-label" style="font-size: 0.62rem; color: var(--slate-tertiary); letter-spacing: 0.08em; margin-bottom: 6px; padding: 0.2rem 0.6rem;">
                NAVIGASI UTAMA
            </div>
            <ul class="nav flex-column list-unstyled m-0 p-0">
                <?php foreach (require __DIR__ . '/menu.php' as $item): ?>
                    <?php
                    $isHome = in_array($item['path'], ['/', '/admin', '/guru', '/siswa'], true);
                    $aktif = $isHome
                        ? (rtrim($appPath, '/') === rtrim($item['path'], '/'))
                        : (str_starts_with($appPath, $item['path']));
                    ?>
                    <li class="nav-item">
                        <a class="spatial-sidebar-link <?= $aktif ? 'active' : '' ?>" href="<?= e(url($item['path'])) ?>">
                            <span class="sidebar-icon"><?= $item['icon'] ?? '' ?></span>
                            <span class="sidebar-text"><?= e($item['label']) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- 3. Pinned Footer Profile & Logout (Inside Sidebar Bottom) -->
        <div class="spatial-sidebar-footer">
            <div class="d-flex align-items-center gap-2 p-2 rounded-4" style="background: rgba(255, 255, 255, 0.8); border: 1.5px solid rgba(255, 255, 255, 0.9); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);">
                <div class="spatial-user-avatar" style="width: 34px; height: 34px; border-radius: 50%; font-size: 0.82rem; background: #0f172a; color: #ffffff; flex-shrink: 0;">
                    <?= strtoupper(substr((string) ($user['nama'] ?? 'U'), 0, 1)) ?>
                </div>
                <div class="overflow-hidden flex-grow-1">
                    <div class="small fw-bold text-truncate" style="color: var(--slate-primary); font-size: 0.78rem;">
                        <?= e($user['nama'] ?? '-') ?>
                    </div>
                    <div class="small text-truncate" style="font-size: 0.65rem; color: var(--slate-tertiary); text-transform: uppercase;">
                        <?= e($peran ?? 'User') ?>
                    </div>
                </div>
                <button type="button" class="btn btn-link p-1 text-danger text-decoration-none" data-bs-toggle="modal" data-bs-target="#crystalLogoutModal" title="Keluar">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </div>
        </div>
    </aside>

    <!-- Mobile Backdrop -->
    <div class="spatial-sidebar-backdrop d-lg-none" id="sidebarBackdrop"
         onclick="document.getElementById('sidebarMenu').classList.remove('show'); this.classList.remove('show');"></div>
    <?php endif; ?>

    <!-- ── RIGHT CONTENT (Area Konten & Topbar Kapsul Melayang) ─────────────── -->
    <div class="spatial-main-wrapper <?= $user === null ? 'spatial-main-full' : '' ?>">
        <!-- Floating Pill Topbar (Matching the Reference Image Controls) -->
        <header class="spatial-topbar d-flex flex-wrap align-items-center justify-content-between gap-3">
            <!-- Left Side: Title & Date Selector -->
            <div class="d-flex align-items-center gap-3">
                <?php if ($user !== null): ?>
                    <button class="btn btn-sm spatial-mobile-toggle d-lg-none" type="button"
                            onclick="document.getElementById('sidebarMenu').classList.add('show'); document.getElementById('sidebarBackdrop').classList.add('show');"
                            aria-label="Buka Menu">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>
                <?php endif; ?>
                
                <div>
                    <h1 class="h4 fw-bold mb-0" style="color: var(--slate-primary); letter-spacing: -0.02em;">
                        <?= e($judul ?? Config::get('app.nama')) ?>
                    </h1>
                </div>

                <!-- Date Capsule & Period Switcher -->
                <div class="d-none d-xl-flex align-items-center gap-2 ms-2">
                    <span class="control-capsule-btn" style="font-size: 0.78rem; padding: 6px 14px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span><?= tanggal_id(date('Y-m-d')) ?></span>
                    </span>

                    <div class="d-inline-flex p-1 rounded-pill" style="background: rgba(255, 255, 255, 0.75); border: 1.5px solid rgba(255, 255, 255, 0.9); box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);">
                        <span class="badge rounded-pill bg-white text-dark shadow-sm px-2.5 py-1" style="font-weight: 700; font-size: 0.72rem;">Hari</span>
                        <span class="badge rounded-pill text-secondary px-2.5 py-1" style="font-weight: 600; font-size: 0.72rem;">Pekan</span>
                        <span class="badge rounded-pill text-secondary px-2.5 py-1" style="font-weight: 600; font-size: 0.72rem;">Semester</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Floating Control Capsules -->
            <div class="d-flex align-items-center gap-2">
                <!-- Video Call / Pembelajaran Pill (Dark Capsule like image) -->
                <?php if ($user !== null): ?>
                    <a href="<?= e(url('/' . ($peran ?? 'siswa') . '/video')) ?>" class="control-capsule-btn control-capsule-dark d-none d-sm-inline-flex" title="Video Pembelajaran">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="23 7 16 12 23 17 23 7"/>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                        </svg>
                        <span>Video Materi</span>
                    </a>
                <?php endif; ?>

                <!-- Time & Weather Widget Capsule -->
                <span class="control-capsule-btn d-none d-md-inline-flex">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span><?= date('H:i') ?> &bull; 28&deg; Garut</span>
                </span>

                <!-- Instant Search Pill Capsule -->
                <div class="search-pill-capsule d-none d-lg-inline-flex">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="color: var(--slate-tertiary);">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" placeholder="Ketik pencarian..." data-instant-filter="table, .subject-card, .file-capsule-item">
                </div>

                <!-- Circular Notification Bell Button with Badge -->
                <a href="<?= e(url('/' . ($peran ?? 'siswa') . '/pengumuman')) ?>" class="icon-circle-capsule" title="Pengumuman & Notifikasi">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span class="icon-circle-badge">3</span>
                </a>

                <!-- User Profile Dropdown Pill -->
                <?php if ($user !== null): ?>
                    <div class="dropdown">
                        <button class="control-capsule-btn dropdown-toggle border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                                  style="width: 22px; height: 22px; background: #0f172a; font-size: 0.65rem;">
                                <?= strtoupper(substr((string) ($user['nama'] ?? 'U'), 0, 1)) ?>
                            </span>
                            <span class="d-none d-sm-inline fw-semibold"><?= e(explode(' ', (string) ($user['nama'] ?? 'User'))[0]) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-2" style="border-radius: 20px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px);">
                            <li><h6 class="dropdown-header small fw-bold" style="color: var(--slate-tertiary);">PENGATURAN AKUN</h6></li>
                            <li><a class="dropdown-item rounded-3 py-2" href="<?= e(url('/profil')) ?>">Profil Pengguna</a></li>
                            <li><a class="dropdown-item rounded-3 py-2" href="<?= e(url('/password')) ?>">Ganti Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button type="button" class="dropdown-item rounded-3 py-2 text-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#crystalLogoutModal">
                                    Keluar Sistem
                                </button>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= e(url('/login')) ?>" class="control-capsule-btn control-capsule-dark">Masuk</a>
                <?php endif; ?>
            </div>
        </header>

        <!-- Area Konten Halaman -->
        <main class="spatial-main-body flex-grow-1">
            <div class="p-0">
                <?php foreach ($pesan as $p): ?>
                    <div class="alert alert-<?= e($p['tipe']) ?> alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 20px;">
                        <span><?= e($p['pesan']) ?></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                <?php endforeach; ?>

                <?= $isi ?? '' ?>
            </div>
        </main>

        <!-- Minimalist Glass Footer -->
        <footer class="text-center py-3 mt-4" style="color: var(--slate-tertiary); font-size: 0.76rem; border-top: 1.5px solid rgba(226, 232, 240, 0.7);">
            <span><?= e(Config::get('app.nama')) ?> &bull; Sistem Pembelajaran Digital SMAN 10 Garut &bull; PT. Vantage Sejahtera</span>
        </footer>
    </div>
</div>

<!-- ==========================================================================
     MODAL KONFIRMASI LOGOUT (PURE CRYSTAL GLASS POPUP)
     ========================================================================== -->
<div class="modal fade" id="crystalLogoutModal" tabindex="-1" aria-labelledby="crystalLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content text-center p-4" style="border-radius: 28px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(25px); border: 1.5px solid rgba(255, 255, 255, 0.9); box-shadow: 0 25px 60px rgba(15, 23, 42, 0.15);">
            <div style="width: 58px; height: 58px; border-radius: 50%; background: rgba(225, 29, 72, 0.08); border: 1.5px solid rgba(225, 29, 72, 0.25); color: var(--accent-rose); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </div>
            <h4 class="h5 fw-bold mb-2" id="crystalLogoutModalLabel" style="color: var(--slate-primary);">Akhiri Sesi Pembelajaran?</h4>
            <p class="small mb-4" style="color: var(--slate-tertiary); line-height: 1.5;">
                Anda akan keluar dari sesi akun LMS SMAN 10 Garut. Pastikan seluruh berkas atau jawaban tugas Anda telah tersimpan dengan baik.
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal" style="border-radius: 9999px; font-weight: 600;">
                    Batal
                </button>
                <button type="button" class="btn btn-danger px-4 py-2" onclick="document.getElementById('logoutFormAction').submit();" style="border-radius: 9999px; font-weight: 600; background: #0f172a; border-color: #0f172a;">
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(aset('js/app.js')) ?>?v=<?= $cssVer ?>"></script>
<?php if (!empty($skrip)): ?>
    <script src="<?= e(aset('js/' . $skrip . '.js')) ?>?v=<?= $cssVer ?>"></script>
<?php endif; ?>
</body>
</html>
