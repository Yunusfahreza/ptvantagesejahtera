<?php
$isUbah = $catatan !== null;
$aksi   = url('/siswa/catatan/simpan');
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h4 mb-1 text-white fw-bold"><?= e($judul) ?></h1>
                <p class="text-white-50 small mb-0">Tulis ringkasan rumus, poin materi, atau to-do belajar pribadi</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/siswa/catatan')) ?>">Kembali</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="post" action="<?= e($aksi) ?>">
                    <?= csrf_field() ?>
                    <?php if ($isUbah): ?>
                        <input type="hidden" name="id" value="<?= e($catatan['id']) ?>">
                    <?php endif; ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label for="judul" class="form-label text-white-50 small fw-semibold">Judul Catatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul" name="judul" maxlength="200" required
                                   value="<?= e($catatan['judul'] ?? '') ?>" placeholder="Contoh: Rangkuman Reaksi Redoks & Elektrokimia">
                        </div>
                        <div class="col-md-4">
                            <label for="mapel_id" class="form-label text-white-50 small fw-semibold">Mata Pelajaran (Opsional)</label>
                            <select class="form-select" id="mapel_id" name="mapel_id">
                                <option value="">-- Catatan Umum --</option>
                                <?php foreach ($mapel as $m): ?>
                                    <option value="<?= e($m['id']) ?>" <?= ((int) ($catatan['mapel_id'] ?? 0) === (int) $m['id']) ? 'selected' : '' ?>>
                                        <?= e($m['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="isi" class="form-label text-white-50 small fw-semibold">Isi Catatan <span class="text-danger">*</span></label>
                        <textarea class="form-control font-monospace" id="isi" name="isi" rows="12" required
                                  placeholder="Tuliskan catatan materi Anda di sini..."><?= e($catatan['isi'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= e(url('/siswa/catatan')) ?>" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <?= $isUbah ? 'Simpan Perubahan' : 'Simpan Catatan' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
