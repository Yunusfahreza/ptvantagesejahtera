<?php

use App\Core\Auth;
use App\Core\Config;
use App\Core\Csrf;
use App\Core\Flash;

$user    = Auth::user();
$peran   = Auth::peran();
$pesan   = Flash::ambilSemua();
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$cssPath = __DIR__ . '/../../../public/assets/css/app.css';
$cssVer  = is_file($cssPath) ? filemtime($cssPath) : time();
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
    <link rel="stylesheet" href="<?= e(aset('css/app.css')) ?>?v=<?= $cssVer ?>">

    <!-- Embedded Core Layout & Sidebar CSS: Kebal dari Cache Browser -->
    <style>
        /* ── Base Layout Structure ─────────────────────────────────── */
        body {
            background-color: #070709 !important;
            color: rgba(255, 255, 255, 0.95) !important;
            font-family: -apple-system, BlinkMacSystemFont, "Inter", sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .spatial-app-shell {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        /* ── Left Sidebar ─────────────────────────────────────────── */
        .spatial-sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1050;
            background: #0c0c10 !important;
            border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 28px rgba(0, 0, 0, 0.5);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        /* Header / Brand */
        .spatial-sidebar-header {
            padding: 1.25rem 1.15rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            flex-shrink: 0;
        }

        .spatial-sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none !important;
            color: #FFFFFF !important;
        }

        .spatial-brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.45);
            flex-shrink: 0;
        }

        .spatial-brand-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #FFFFFF !important;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .spatial-brand-subtitle {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.45) !important;
            font-weight: 500;
        }

        /* User Profile Snippet in Sidebar */
        .spatial-sidebar-user {
            margin: 0.85rem 1rem;
            padding: 0.65rem 0.85rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .spatial-user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.35);
        }

        .spatial-user-details {
            overflow: hidden;
            flex-grow: 1;
        }

        .spatial-user-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.95) !important;
            line-height: 1.2;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .spatial-user-role-badge {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: rgba(255, 255, 255, 0.55);
        }

        .role-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }
        .role-dot-admin { background: #ef4444; box-shadow: 0 0 6px rgba(239, 68, 68, 0.6); }
        .role-dot-guru  { background: #10b981; box-shadow: 0 0 6px rgba(16, 185, 129, 0.6); }
        .role-dot-siswa { background: #3b82f6; box-shadow: 0 0 6px rgba(59, 130, 246, 0.6); }

        /* Navigation List */
        .spatial-sidebar-nav {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0.4rem 0.75rem;
        }

        .spatial-sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .spatial-sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .spatial-nav-section-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.35);
            font-weight: 600;
            padding: 0.5rem 0.6rem 0.3rem;
        }

        .spatial-sidebar-link {
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
            padding: 0.55rem 0.75rem !important;
            border-radius: 10px !important;
            color: rgba(255, 255, 255, 0.65) !important;
            text-decoration: none !important;
            font-size: 0.84rem !important;
            font-weight: 500 !important;
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            margin-bottom: 2px;
        }

        .spatial-sidebar-link:hover {
            color: #FFFFFF !important;
            background: rgba(255, 255, 255, 0.06) !important;
            transform: translateX(2px);
        }

        .spatial-sidebar-link.active {
            color: #FFFFFF !important;
            background: rgba(99, 102, 241, 0.16) !important;
            border: 1px solid rgba(129, 140, 248, 0.3) !important;
            font-weight: 600 !important;
        }

        .sidebar-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            color: inherit;
            flex-shrink: 0;
            opacity: 0.85;
        }

        .spatial-sidebar-link.active .sidebar-icon {
            color: #818cf8 !important;
            opacity: 1;
        }

        .sidebar-text {
            flex-grow: 1;
            line-height: 1.2;
            white-space: nowrap;
        }

        .sidebar-active-glow {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #818cf8;
            box-shadow: 0 0 8px rgba(129, 140, 248, 0.8);
        }

        /* Sidebar Footer */
        .spatial-sidebar-footer {
            padding: 0.85rem 1rem 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(0, 0, 0, 0.3);
            flex-shrink: 0;
        }

        .spatial-footer-action {
            font-size: 0.75rem;
            padding: 0.4rem 0.65rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.7) !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            text-decoration: none !important;
            transition: all 0.2s;
        }

        .spatial-footer-action:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #FFFFFF !important;
            border-color: rgba(255, 255, 255, 0.15);
        }

        .spatial-logout-btn {
            font-size: 0.75rem;
            padding: 0.42rem 0.75rem;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #f87171 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-decoration: none !important;
        }

        .spatial-logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444 !important;
            border-color: rgba(239, 68, 68, 0.4);
        }

        /* ── Right Main Area ──────────────────────────────────────── */
        .spatial-main-wrapper {
            flex-grow: 1;
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - 260px);
            background: transparent;
        }

        .spatial-main-full {
            margin-left: 0 !important;
            width: 100% !important;
        }

        /* Topbar Header */
        .spatial-topbar {
            height: 60px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(7, 7, 9, 0.85);
            backdrop-filter: blur(24px);
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
        }

        .spatial-breadcrumb-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.92);
            letter-spacing: -0.01em;
        }

        .spatial-mobile-toggle {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 0.3rem 0.55rem;
        }

        .spatial-term-pill {
            font-size: 0.74rem;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.6);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .term-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 6px rgba(16, 185, 129, 0.6);
        }

        .spatial-user-dropdown-pill {
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #FFFFFF !important;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-avatar-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: #FFFFFF;
        }

        .spatial-profile-badge {
            background: rgba(129, 140, 248, 0.18) !important;
            color: #818cf8 !important;
            border: 1px solid rgba(129, 140, 248, 0.3) !important;
            font-size: 0.68rem;
            font-weight: 600;
            padding: 0.15rem 0.45rem;
            border-radius: 9999px;
            letter-spacing: 0.03em;
        }

        .spatial-main-body {
            flex-grow: 1;
        }

        .spatial-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.35);
            background: rgba(0, 0, 0, 0.15);
        }

        /* ── Responsive Adjustments ───────────────────────────────── */
        @media (max-width: 991.98px) {
            .spatial-sidebar {
                transform: translateX(-100%);
            }

            .spatial-sidebar.show {
                transform: translateX(0);
                box-shadow: 10px 0 50px rgba(0, 0, 0, 0.85);
            }

            .spatial-main-wrapper {
                margin-left: 0;
                width: 100%;
            }

            .spatial-sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.65);
                backdrop-filter: blur(4px);
                z-index: 1040;
                display: none;
            }

            .spatial-sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
</head>
<body>

<!-- Ambient Atmospheric Orbs -->
<div class="ambient-scene" aria-hidden="true">
    <div class="ambient-orb ambient-orb-1"></div>
    <div class="ambient-orb ambient-orb-2"></div>
</div>

<div class="spatial-app-shell">
    <?php if ($user !== null): ?>
    <!-- ── LEFT SIDEBAR (Navigasi Kiri Rapi & Tertata) ──────────────────── -->
    <aside class="spatial-sidebar" id="sidebarMenu">
        <!-- Brand Sekolah -->
        <div class="spatial-sidebar-header">
            <a class="spatial-sidebar-brand" href="<?= e(url(Auth::dashboardPath())) ?>">
                <div class="spatial-brand-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div class="spatial-brand-meta">
                    <div class="spatial-brand-title">LMS SMAN 10</div>
                    <div class="spatial-brand-subtitle">Garut &bull; Sistem Belajar</div>
                </div>
            </a>
            <button class="btn btn-sm spatial-mobile-toggle d-lg-none" type="button"
                    onclick="document.getElementById('sidebarMenu').classList.remove('show'); document.getElementById('sidebarBackdrop').classList.remove('show');">
                ✕
            </button>
        </div>

        <!-- Profil Singkat Pengguna -->
        <div class="spatial-sidebar-user">
            <div class="spatial-user-avatar">
                <?= strtoupper(substr((string) ($user['nama'] ?? 'U'), 0, 1)) ?>
            </div>
            <div class="spatial-user-details">
                <div class="spatial-user-name" title="<?= e($user['nama'] ?? '') ?>">
                    <?= e($user['nama'] ?? '') ?>
                </div>
                <div class="spatial-user-role-badge">
                    <span class="role-dot role-dot-<?= e($peran ?? 'user') ?>"></span>
                    <span><?= strtoupper(e($peran ?? '')) ?></span>
                </div>
            </div>
        </div>

        <!-- Menu Navigasi Vertikal -->
        <div class="spatial-sidebar-nav">
            <div class="spatial-nav-section-label">Navigasi Utama</div>
            <ul class="nav flex-column list-unstyled m-0 p-0">
                <?php foreach (require __DIR__ . '/menu.php' as $item): ?>
                    <?php
                    $isHome = in_array($item['path'], ['/', '/admin', '/guru', '/siswa'], true);
                    $aktif = $isHome
                        ? (rtrim($uriPath, '/') === rtrim($item['path'], '/'))
                        : (str_starts_with($uriPath, $item['path']));
                    ?>
                    <li class="nav-item">
                        <a class="spatial-sidebar-link <?= $aktif ? 'active' : '' ?>" href="<?= e(url($item['path'])) ?>">
                            <span class="sidebar-icon"><?= $item['icon'] ?? '' ?></span>
                            <span class="sidebar-text"><?= e($item['label']) ?></span>
                            <?php if ($aktif): ?>
                                <span class="sidebar-active-glow"></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Footer Sidebar -->
        <div class="spatial-sidebar-footer">
            <div class="d-flex gap-2 mb-2">
                <a href="<?= e(url('/profil')) ?>" class="btn spatial-footer-action flex-grow-1" title="Profil Akun">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Profil</span>
                </a>
                <a href="<?= e(url('/password')) ?>" class="btn spatial-footer-action px-2" title="Ganti Password">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </a>
            </div>
            <form method="post" action="<?= e(url('/logout')) ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn spatial-logout-btn w-100">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Backdrop -->
    <div class="spatial-sidebar-backdrop d-lg-none" id="sidebarBackdrop"
         onclick="document.getElementById('sidebarMenu').classList.remove('show'); this.classList.remove('show');"></div>
    <?php endif; ?>

    <!-- ── RIGHT CONTENT (Area Konten & Topbar) ─────────────────────────── -->
    <div class="spatial-main-wrapper <?= $user === null ? 'spatial-main-full' : '' ?>">
        <!-- Topbar Minimalis -->
        <header class="spatial-topbar">
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
                <div class="spatial-breadcrumb-label">
                    <?= e($judul ?? Config::get('app.nama')) ?>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="spatial-term-pill d-none d-sm-inline-flex">
                    <span class="term-status-dot"></span>
                    <span>2026/2027 Ganjil</span>
                </span>

                <?php if ($user !== null): ?>
                    <div class="dropdown">
                        <button class="btn spatial-user-dropdown-pill dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="user-avatar-dot"><?= strtoupper(substr((string) ($user['nama'] ?? 'U'), 0, 1)) ?></span>
                            <span class="d-none d-md-inline fw-medium"><?= e($user['nama'] ?? '') ?></span>
                            <span class="spatial-profile-badge"><?= strtoupper(e($peran ?? '')) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-secondary-subtle">
                            <li><h6 class="dropdown-header text-white-50">Akun Terdaftar</h6></li>
                            <li><a class="dropdown-item py-2" href="<?= e(url('/profil')) ?>">Profil Pengguna</a></li>
                            <li><a class="dropdown-item py-2" href="<?= e(url('/password')) ?>">Ganti Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="post" action="<?= e(url('/logout')) ?>" class="px-2 py-1">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= e(url('/login')) ?>" class="btn btn-sm btn-primary px-3">Masuk</a>
                <?php endif; ?>
            </div>
        </header>

        <!-- Area Konten Halaman -->
        <main class="spatial-main-body">
            <div class="container-fluid px-3 px-md-4 py-4">
                <?php foreach ($pesan as $p): ?>
                    <div class="alert alert-<?= e($p['tipe']) ?> alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <span><?= e($p['pesan']) ?></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                <?php endforeach; ?>

                <?= $isi ?? '' ?>
            </div>
        </main>

        <!-- Footer -->
        <footer class="spatial-footer text-center py-3">
            <span class="text-white-50 small"><?= e(Config::get('app.nama')) ?> &bull; Sistem Pembelajaran Digital SMAN 10 Garut &bull; PT. Vantage Sejahtera</span>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(aset('js/app.js')) ?>?v=<?= $cssVer ?>"></script>
<?php if (!empty($skrip)): ?>
    <script src="<?= e(aset('js/' . $skrip . '.js')) ?>?v=<?= $cssVer ?>"></script>
<?php endif; ?>
</body>
</html>
