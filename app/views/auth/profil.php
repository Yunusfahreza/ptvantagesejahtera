<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <h1 class="h4 mb-3">Profil</h1>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nama</dt>
                    <dd class="col-sm-8"><?= e($user['nama'] ?? '-') ?></dd>

                    <dt class="col-sm-4">Username</dt>
                    <dd class="col-sm-8"><?= e($user['username'] ?? '-') ?></dd>

                    <dt class="col-sm-4">Peran</dt>
                    <dd class="col-sm-8"><span class="badge text-bg-primary"><?= e($user['role'] ?? '-') ?></span></dd>

                    <?php if (!empty($user['kelas_nama'])): ?>
                        <dt class="col-sm-4">Kelas</dt>
                        <dd class="col-sm-8"><?= e($user['kelas_nama']) ?></dd>
                    <?php endif; ?>

                    <dt class="col-sm-4">Terdaftar</dt>
                    <dd class="col-sm-8 mb-0"><?= e(tanggal_id($user['created_at'] ?? null)) ?></dd>
                </dl>
            </div>
            <div class="card-footer bg-transparent">
                <a class="btn btn-outline-primary btn-sm" href="<?= e(url('/password')) ?>">Ganti Password</a>
            </div>
        </div>
    </div>
</div>
