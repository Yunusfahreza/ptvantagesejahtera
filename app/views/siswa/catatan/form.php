<?php
$isUbah = $catatan !== null;
$aksi   = url('/siswa/catatan/simpan');
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= e(url('/siswa/catatan')) ?>">Catatan Belajar</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= $isUbah ? 'Edit Catatan' : 'Tulis Catatan' ?></li>
                    </ol>
                </nav>
                <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary);"><?= e($judul) ?></h1>
                <p class="small mb-0" style="color: var(--slate-tertiary);">Tulis ringkasan materi, rumus, konsep esensial, atau rencana belajar</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/siswa/catatan')) ?>">
                &larr; Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <form method="post" action="<?= e($aksi) ?>">
                    <?= csrf_field() ?>
                    <?php if ($isUbah): ?>
                        <input type="hidden" name="id" value="<?= e($catatan['id']) ?>">
                    <?php endif; ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label for="judul" class="form-label fw-bold">Judul Catatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2" id="judul" name="judul" maxlength="200" required
                                   value="<?= e($catatan['judul'] ?? '') ?>" placeholder="Contoh: Rangkuman Reaksi Redoks & Elektrokimia">
                        </div>
                        <div class="col-md-4">
                            <label for="mapel_id" class="form-label fw-bold">Mata Pelajaran (Opsional)</label>
                            <select class="form-select py-2" id="mapel_id" name="mapel_id">
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
                        <label for="isi" class="form-label fw-bold">Isi Catatan Pembelajaran <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="isi" name="isi" rows="12" required
                                  style="font-family: 'Plus Jakarta Sans', monospace; line-height: 1.6;"
                                  placeholder="Tuliskan catatan, rumus, atau poin penting materi Anda di sini..."><?= e($catatan['isi'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <a href="<?= e(url('/siswa/catatan')) ?>" class="btn btn-outline-secondary px-4 py-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                            <?= $isUbah ? 'Simpan Perubahan' : 'Terbitkan Catatan' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
