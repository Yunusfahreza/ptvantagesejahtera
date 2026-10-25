<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">
        <h1 class="h4 mb-3">Ganti Password</h1>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="post" action="<?= e(url('/password')) ?>" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="password_lama" class="form-label">Password lama</label>
                        <input type="password" class="form-control" id="password_lama"
                               name="password_lama" required autocomplete="current-password">
                    </div>

                    <div class="mb-3">
                        <label for="password_baru" class="form-label">Password baru</label>
                        <input type="password" class="form-control" id="password_baru"
                               name="password_baru" required minlength="8" autocomplete="new-password">
                        <div class="form-text">Minimal 8 karakter.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password_konfirmasi" class="form-label">Ulangi password baru</label>
                        <input type="password" class="form-control" id="password_konfirmasi"
                               name="password_konfirmasi" required minlength="8" autocomplete="new-password">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a class="btn btn-outline-secondary" href="<?= e(url('/profil')) ?>">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
