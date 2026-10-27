<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h4 mb-1 text-white fw-bold">Profil Pengguna</h1>
                <p class="text-white-50 small mb-0">Kelola informasi data diri, foto profil, dan kontak Anda</p>
            </div>
            <a class="btn btn-outline-primary btn-sm" href="<?= e(url('/password')) ?>">Ganti Password</a>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <form method="post" action="<?= e(url('/profil')) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="d-flex flex-column flex-sm-row align-items-center gap-4 mb-4 pb-4 border-bottom border-secondary-subtle">
                        <div class="position-relative">
                            <?php if (!empty($user['foto'])): ?>
                                <img src="<?= e(url('/profil/foto/' . $user['id'])) ?>" alt="Foto Profil"
                                     class="rounded-circle border border-primary border-2 shadow"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center border border-primary-subtle shadow"
                                     style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: bold;">
                                    <?= strtoupper(substr((string) ($user['nama'] ?? 'U'), 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1 text-center text-sm-start">
                            <h5 class="text-white fw-bold mb-1"><?= e($user['nama'] ?? '-') ?></h5>
                            <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-sm-start align-items-center mb-2">
                                <span class="badge text-bg-primary text-uppercase"><?= e($user['role'] ?? '-') ?></span>
                                <span class="badge text-bg-dark border"><?= e($user['username'] ?? '-') ?></span>
                                <?php if (!empty($user['kelas_nama'])): ?>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">Kelas <?= e($user['kelas_nama']) ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label for="foto" class="btn btn-xs btn-outline-secondary py-1 px-3 small" style="cursor: pointer;">
                                    Ganti Foto Profil
                                </label>
                                <input type="file" id="foto" name="foto" class="d-none" accept="image/jpeg,image/png,image/webp" onchange="previewImg(this)">
                                <div id="foto-info" class="small text-white-50 mt-1"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="nomor_induk" class="form-label text-white-50 small fw-semibold">
                                <?= ($user['role'] ?? '') === 'guru' ? 'NIP / NUPTK' : 'NIS / NISN' ?>
                            </label>
                            <input type="text" class="form-control" id="nomor_induk" name="nomor_induk"
                                   value="<?= e($user['nomor_induk'] ?? '') ?>" placeholder="Nomor Induk...">
                        </div>
                        <div class="col-md-6">
                            <label for="no_hp" class="form-label text-white-50 small fw-semibold">No. WhatsApp / HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp"
                                   value="<?= e($user['no_hp'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="tempat_lahir" class="form-label text-white-50 small fw-semibold">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                   value="<?= e($user['tempat_lahir'] ?? '') ?>" placeholder="Kota / Kabupaten">
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_lahir" class="form-label text-white-50 small fw-semibold">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                   value="<?= e($user['tanggal_lahir'] ?? '') ?>">
                        </div>
                    </div>

                    <?php if (($user['role'] ?? '') === 'siswa'): ?>
                        <div class="mb-3">
                            <label for="no_hp_ortu" class="form-label text-white-50 small fw-semibold">No. HP / WhatsApp Orang Tua / Wali</label>
                            <input type="text" class="form-control" id="no_hp_ortu" name="no_hp_ortu"
                                   value="<?= e($user['no_hp_ortu'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label for="alamat" class="form-label text-white-50 small fw-semibold">Alamat Tempat Tinggal</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                  placeholder="Alamat lengkap domisili..."><?= e($user['alamat'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">
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
        document.getElementById('foto-info').textContent = 'Berkas dipilih: ' + input.files[0].name;
    }
}
</script>
