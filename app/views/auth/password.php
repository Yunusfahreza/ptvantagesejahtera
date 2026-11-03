<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card spatial-panel border-0 shadow-sm" style="border-radius: 24px;">
            <div class="card-body p-4 p-md-5">
                <!-- Icon & Header -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                         style="width: 56px; height: 56px; background: rgba(37, 99, 235, 0.1); border: 1.5px solid rgba(191, 219, 254, 0.9);">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary); letter-spacing: -0.02em;">Ganti Password</h1>
                    <p class="small mb-0" style="color: var(--slate-secondary);">Perbarui kata sandi akun Anda untuk menjaga keamanan</p>
                </div>

                <form method="post" action="<?= e(url('/password')) ?>" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="password_lama" class="form-label small fw-semibold" style="color: var(--slate-secondary);">Password Saat Ini</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-color: rgba(226, 232, 240, 0.9); color: var(--slate-tertiary);">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <input type="password" class="form-control border-start-0 ps-0" id="password_lama"
                                   name="password_lama" required autocomplete="current-password" placeholder="Masukkan password lama">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_baru" class="form-label small fw-semibold" style="color: var(--slate-secondary);">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-color: rgba(226, 232, 240, 0.9); color: var(--slate-tertiary);">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 2l-2 2m-1-1l-3 3m-1-1l-2 2m-1-1l-2 2m-1-1l-3 3"/>
                                    <circle cx="7.5" cy="16.5" r="4.5"/>
                                </svg>
                            </span>
                            <input type="password" class="form-control border-start-0 ps-0" id="password_baru"
                                   name="password_baru" required minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter kombinasi">
                        </div>
                        <div class="form-text small" style="color: var(--slate-tertiary); font-size: 0.75rem;">
                            Gunakan kombinasi minimal 8 karakter huruf dan angka.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_konfirmasi" class="form-label small fw-semibold" style="color: var(--slate-secondary);">Ulangi Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-color: rgba(226, 232, 240, 0.9); color: var(--slate-tertiary);">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </span>
                            <input type="password" class="form-control border-start-0 ps-0" id="password_konfirmasi"
                                   name="password_konfirmasi" required minlength="8" autocomplete="new-password" placeholder="Ketik ulang password baru">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2 d-inline-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; font-weight: 600;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Simpan Password Baru
                        </button>
                        <a class="btn btn-outline-secondary py-2" href="<?= e(url('/profil')) ?>" style="border-radius: 12px; font-weight: 600;">
                            Batal & Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
