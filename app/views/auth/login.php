<?php

use App\Core\Config;
?>
<div class="card spatial-panel border-0 p-3 p-sm-4 shadow-lg">
    <div class="card-body p-2 p-sm-3">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3"
                 style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                <span class="spatial-brand-dot" style="width: 12px; height: 12px;"></span>
            </div>
            <h1 class="h4 font-medium mb-1 text-white tracking-tight" style="letter-spacing: -0.03em;">
                <?= e(Config::get('app.nama')) ?>
            </h1>
            <p class="small mb-0" style="color: rgba(255, 255, 255, 0.5);">
                Masuk dengan akun yang diberikan sekolah
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 px-3 mb-3 small" role="alert"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= e(url('/login')) ?>" novalidate>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                       value="<?= e($username ?? '') ?>" required autofocus
                       autocomplete="username" maxlength="50"
                       placeholder="Nama pengguna">
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                       required autocomplete="current-password"
                       placeholder="Kata sandi">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mb-3">Masuk ke Portal</button>
        </form>

        <div class="text-center mt-3 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.06);">
            <span class="small" style="color: rgba(255, 255, 255, 0.35); font-size: 0.78rem;">
                <?= e(Config::get('app.nama')) ?> &middot; PT. Vantage Sejahtera
            </span>
        </div>
    </div>
</div>
