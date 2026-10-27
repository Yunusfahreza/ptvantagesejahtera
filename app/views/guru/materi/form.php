<?php $adalahEdit = $materi !== null; ?>
<div class="row justify-content-center">
    <div class="col-12 col-md-9 col-lg-7">
        <h1 class="h4 mb-3"><?= $adalahEdit ? 'Ubah Materi' : 'Unggah Materi' ?></h1>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <?php if ($pengampu === []): ?>
                    <p class="mb-0 text-body-secondary">
                        Anda belum punya kelas yang diampu.
                    </p>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/guru/materi/simpan')) ?>"
                          enctype="multipart/form-data" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($materi['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="pengampu_id" class="form-label">Kelas dan mata pelajaran</label>
                            <select class="form-select" id="pengampu_id" name="pengampu_id" required>
                                <?php foreach ($pengampu as $p): ?>
                                    <option value="<?= e($p['id']) ?>"
                                        <?= (int) ($materi['pengampu_id'] ?? 0) === (int) $p['id'] ? 'selected' : '' ?>>
                                        <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" required
                                   maxlength="150" value="<?= e($materi['judul'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi <span class="text-body-secondary">(opsional)</span></label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
                                      maxlength="5000"><?= e($materi['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="berkas" class="form-label">
                                Berkas <?= $adalahEdit ? '(kosongkan bila tidak diganti)' : '' ?>
                            </label>
                            <input type="file" class="form-control" id="berkas" name="berkas"
                                   <?= $adalahEdit ? '' : 'required' ?>>
                            <div class="form-text">
                                Format: <?= e($ekstensi) ?>. Maksimal <?= e($maks) ?>.
                            </div>
                            <?php if ($adalahEdit): ?>
                                <div class="form-text">
                                    Berkas saat ini:
                                    <a href="<?= e(url('/guru/materi/unduh/' . $materi['id'])) ?>">
                                        <?= e($materi['file_asli']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a class="btn btn-outline-secondary" href="<?= e(url('/guru/materi')) ?>">Batal</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
