<?php

use App\Core\Auth;

$ikon = match ($status) {
    403     => 'Akses Ditolak',
    404     => 'Halaman Tidak Ditemukan',
    500     => 'Kesalahan Server',
    default => 'Terjadi Kesalahan',
};

$badgeColor = match ($status) {
    403     => 'background: rgba(245, 158, 11, 0.12); color: #d97706; border: 1px solid rgba(251, 191, 36, 0.3);',
    404     => 'background: rgba(37, 99, 235, 0.12); color: #2563eb; border: 1px solid rgba(147, 197, 253, 0.3);',
    default => 'background: rgba(239, 68, 68, 0.12); color: #dc2626; border: 1px solid rgba(252, 165, 165, 0.3);',
};
?>
<div class="row justify-content-center align-items-center min-vh-75 py-5">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card spatial-panel border-0 shadow-lg text-center p-4 p-md-5" style="border-radius: 28px;">
            <div class="card-body p-2 p-md-3">
                <!-- Status Badge -->
                <div class="d-inline-flex align-items-center justify-content-center px-3 py-1.5 rounded-pill mb-4" style="<?= $badgeColor ?>">
                    <span class="fw-bold" style="font-size: 0.85rem; letter-spacing: 0.05em;">STATUS <?= e($status) ?></span>
                </div>

                <!-- Big Visual Indicator -->
                <div class="mb-4 position-relative">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                         style="width: 88px; height: 88px; background: rgba(15, 23, 42, 0.03); border: 2px solid rgba(226, 232, 240, 0.8); box-shadow: 0 8px 30px rgba(15, 23, 42, 0.06);">
                        <?php if ($status === 403): ?>
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        <?php elseif ($status === 404): ?>
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                        <?php else: ?>
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                </div>

                <h1 class="h3 fw-bold mb-2" style="color: var(--slate-primary); letter-spacing: -0.02em;">
                    <?= e($ikon) ?>
                </h1>
                
                <p class="small mb-4 px-md-3" style="color: var(--slate-secondary); line-height: 1.6;">
                    <?= e($pesan) ?>
                </p>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                    <a class="btn btn-primary px-4 py-2.5 d-inline-flex align-items-center justify-content-center gap-2"
                       href="<?= e(url(Auth::sudahLogin() ? Auth::dashboardPath() : '/login')) ?>"
                       style="border-radius: 12px; font-weight: 600;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        <?= Auth::sudahLogin() ? 'Kembali ke Dashboard' : 'Ke Halaman Login' ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
