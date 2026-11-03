<?php

use App\Core\Config;
?>
<div class="card spatial-panel border-0 p-3 p-sm-4 shadow-lg" style="border-radius: 28px !important;">
    <div class="card-body p-2 p-sm-3">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3"
                 style="background: rgba(15, 23, 42, 0.05); border: 1.5px solid rgba(226, 232, 240, 0.9); box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--slate-primary);">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary); letter-spacing: -0.02em;">
                <?= e(Config::get('app.nama')) ?>
            </h1>
            <p class="small mb-0" style="color: var(--slate-tertiary);">
                Masuk dengan akun terdaftar SMAN 10 Garut
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 px-3 mb-3 small" role="alert"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('/login')) ?>" novalidate>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username / NISN / NIP</label>
                <input type="text" class="form-control" id="username" name="username"
                       value="<?= e($username ?? '') ?>" required autofocus
                       autocomplete="username" maxlength="50"
                       placeholder="Masukkan nama pengguna">
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                       required autocomplete="current-password"
                       placeholder="Masukkan kata sandi">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                Masuk ke Portal
            </button>
        </form>

        <div class="text-center mt-3 pt-3" style="border-top: 1.5px solid rgba(226, 232, 240, 0.8);">
            <span class="small" style="color: var(--slate-tertiary); font-size: 0.76rem;">
                <?= e(Config::get('app.nama')) ?> &bull; PT. Vantage Sejahtera
            </span>
        </div>
    </div>
</div>
