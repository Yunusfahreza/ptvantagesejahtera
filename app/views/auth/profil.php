<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <!-- Top Header Bar -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge rounded-pill" style="background: rgba(37, 99, 235, 0.1); color: #2563eb; font-weight: 600; font-size: 0.75rem;">
                        PENGATURAN AKUN
                    </span>
                    <span class="small" style="color: var(--slate-tertiary);">&bull; Identitas Pengguna</span>
                </div>
                <h1 class="h3 fw-bold mb-1" style="color: var(--slate-primary); letter-spacing: -0.02em;">Profil Pengguna</h1>
                <p class="small mb-0" style="color: var(--slate-secondary);">Kelola data pribadi, informasi kontak, dan foto profil Anda</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-secondary d-inline-flex align-items-center gap-2 px-3 py-2" href="<?= e(url('/password')) ?>" style="border-radius: 12px; font-weight: 600;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Ganti Password
                </a>
            </div>
        </div>

        <!-- Main Profile Card -->
        <div class="card spatial-panel border-0 shadow-sm mb-4" style="border-radius: 24px;">
            <div class="card-body p-4 p-md-5">
                <form method="post" action="<?= e(url('/profil')) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- Avatar & Identity Strip -->
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4 mb-4 pb-4" style="border-bottom: 1.5px solid rgba(226, 232, 240, 0.8);">
                        <div class="position-relative">
                            <?php if (!empty($user['foto'])): ?>
                                <img id="avatar-preview" src="<?= e(url('/profil/foto/' . $user['id'])) ?>" alt="Foto Profil"
                                     class="rounded-circle shadow-sm"
                                     style="width: 108px; height: 108px; object-fit: cover; border: 3px solid #ffffff; box-shadow: 0 8px 24px rgba(37, 99, 235, 0.15);">
                            <?php else: ?>
                                <div id="avatar-preview" class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                     style="width: 108px; height: 108px; font-size: 2.75rem; font-weight: 700; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb; border: 3px solid #ffffff; box-shadow: 0 8px 24px rgba(37, 99, 235, 0.12);">
                                    <?= strtoupper(substr((string) ($user['nama'] ?? 'U'), 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            
                            <label for="foto" class="position-absolute bottom-0 end-0 p-2 rounded-circle bg-primary text-white shadow-sm" 
                                   style="cursor: pointer; transform: translate(5px, 5px); transition: transform 0.2s ease;"
                                   title="Ubah Foto Profil">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                    <circle cx="12" cy="13" r="4"/>
                                </svg>
                            </label>
                            <input type="file" id="foto" name="foto" class="d-none" accept="image/jpeg,image/png,image/webp" onchange="previewImg(this)">
                        </div>

                        <div class="flex-grow-1 text-center text-md-start">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
                                <h3 class="h4 fw-bold mb-0" style="color: var(--slate-primary);"><?= e($user['nama'] ?? '-') ?></h3>
                                <?php
                                $roleBadge = match ($user['role'] ?? '') {
                                    'admin' => 'background: rgba(147, 51, 234, 0.12); color: #7e22ce;',
                                    'guru'  => 'background: rgba(16, 185, 129, 0.12); color: #047857;',
                                    default => 'background: rgba(37, 99, 235, 0.12); color: #1d4ed8;',
                                };
                                ?>
                                <span class="badge rounded-pill text-uppercase px-3 py-1" style="<?= $roleBadge ?> font-weight: 700; font-size: 0.72rem;">
                                    <?= e($user['role'] ?? 'USER') ?>
                                </span>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start align-items-center mb-3">
                                <span class="badge bg-white border px-2.5 py-1.5" style="color: var(--slate-secondary); font-size: 0.78rem; border-color: rgba(226, 232, 240, 0.9) !important;">
                                    <span style="color: var(--slate-tertiary);">Username:</span> <?= e($user['username'] ?? '-') ?>
                                </span>
                                <?php if (!empty($user['kelas_nama'])): ?>
                                    <span class="badge bg-white border px-2.5 py-1.5" style="color: #0284c7; border-color: rgba(186, 230, 253, 0.9) !important; background: rgba(240, 249, 255, 0.8) !important;">
                                        Kelas <?= e($user['kelas_nama']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-3">
                                <label for="foto" class="btn btn-sm btn-outline-primary px-3 py-1.5" style="border-radius: 10px; cursor: pointer; font-size: 0.8rem; font-weight: 600;">
                                    Pilih Berkas Foto
                                </label>
                                <span class="small" style="color: var(--slate-tertiary); font-size: 0.75rem;">
                                    JPG, PNG, atau WebP (Maks. 2MB)
                                </span>
                            </div>
                            <div id="foto-info" class="small fw-semibold mt-2" style="color: #059669;"></div>
                        </div>
                    </div>

                    <!-- Detail Data Form -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="squircle-icon-blue d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; border-radius: 8px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <h2 class="h6 fw-bold mb-0" style="color: var(--slate-primary);">Data Identitas & Kontak</h2>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="nomor_induk" class="form-label small fw-semibold" style="color: var(--slate-secondary);">
                                    <?= ($user['role'] ?? '') === 'guru' ? 'NIP / NUPTK' : 'NIS / NISN' ?>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-color: rgba(226, 232, 240, 0.9); color: var(--slate-tertiary);">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                                            <line x1="7" y1="8" x2="17" y2="8"/>
                                            <line x1="7" y1="12" x2="13" y2="12"/>
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="nomor_induk" name="nomor_induk"
                                           value="<?= e($user['nomor_induk'] ?? '') ?>" placeholder="Nomor Induk...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="no_hp" class="form-label small fw-semibold" style="color: var(--slate-secondary);">No. WhatsApp / HP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-color: rgba(226, 232, 240, 0.9); color: var(--slate-tertiary);">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="no_hp" name="no_hp"
                                           value="<?= e($user['no_hp'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="tempat_lahir" class="form-label small fw-semibold" style="color: var(--slate-secondary);">Tempat Lahir</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-color: rgba(226, 232, 240, 0.9); color: var(--slate-tertiary);">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="tempat_lahir" name="tempat_lahir"
                                           value="<?= e($user['tempat_lahir'] ?? '') ?>" placeholder="Kota / Kabupaten">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_lahir" class="form-label small fw-semibold" style="color: var(--slate-secondary);">Tanggal Lahir</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-color: rgba(226, 232, 240, 0.9); color: var(--slate-tertiary);">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                    </span>
                                    <input type="date" class="form-control border-start-0 ps-0" id="tanggal_lahir" name="tanggal_lahir"
                                           value="<?= e($user['tanggal_lahir'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <?php if (($user['role'] ?? '') === 'siswa'): ?>
                            <div class="mb-3">
                                <label for="no_hp_ortu" class="form-label small fw-semibold" style="color: var(--slate-secondary);">No. HP / WhatsApp Orang Tua / Wali</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-color: rgba(226, 232, 240, 0.9); color: var(--slate-tertiary);">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="no_hp_ortu" name="no_hp_ortu"
                                           value="<?= e($user['no_hp_ortu'] ?? '') ?>" placeholder="08xxxxxxxxxx (Nomor Kontak Wali)">
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-4">
                            <label for="alamat" class="form-label small fw-semibold" style="color: var(--slate-secondary);">Alamat Tempat Tinggal</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                      placeholder="Alamat lengkap domisili..."><?= e($user['alamat'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Bottom Buttons -->
                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3" style="border-top: 1.5px solid rgba(226, 232, 240, 0.8);">
                        <a href="<?= e(url(App\Core\Auth::dashboardPath())) ?>" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 12px; font-weight: 600;">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-2" style="border-radius: 12px; font-weight: 600;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImg(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const infoEl = document.getElementById('foto-info');
        if (infoEl) {
            infoEl.textContent = 'Berkas dipilih: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewEl = document.getElementById('avatar-preview');
            if (previewEl) {
                if (previewEl.tagName.toLowerCase() === 'img') {
                    previewEl.src = e.target.result;
                } else {
                    const newImg = document.createElement('img');
                    newImg.id = 'avatar-preview';
                    newImg.src = e.target.result;
                    newImg.alt = 'Foto Profil';
                    newImg.className = 'rounded-circle shadow-sm';
                    newImg.style.cssText = 'width: 108px; height: 108px; object-fit: cover; border: 3px solid #ffffff; box-shadow: 0 8px 24px rgba(37, 99, 235, 0.15);';
                    previewEl.parentNode.replaceChild(newImg, previewEl);
                }
            }
        };
        reader.readAsDataURL(file);
    }
}
</script>
