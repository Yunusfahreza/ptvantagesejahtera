<?php
$adalahEdit = $tugas !== null;
$tenggatNilai = $adalahEdit
    ? date('Y-m-d\TH:i', strtotime((string) $tugas['tenggat']))
    : date('Y-m-d\TH:i', strtotime('+7 days 23:59'));
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-9 col-lg-7">
        <h1 class="h4 mb-3"><?= $adalahEdit ? 'Ubah Tugas' : 'Buat Tugas' ?></h1>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <?php if ($pengampu === []): ?>
                    <p class="mb-0 text-body-secondary">Anda belum punya kelas yang diampu.</p>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/guru/tugas/simpan')) ?>"
                          enctype="multipart/form-data" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($tugas['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="pengampu_id" class="form-label">Kelas dan mata pelajaran</label>
                            <select class="form-select" id="pengampu_id" name="pengampu_id" required>
                                <?php foreach ($pengampu as $p): ?>
                                    <option value="<?= e($p['id']) ?>"
                                        <?= (int) ($tugas['pengampu_id'] ?? 0) === (int) $p['id'] ? 'selected' : '' ?>>
                                        <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul tugas</label>
                            <input type="text" class="form-control" id="judul" name="judul" required
                                   maxlength="150" value="<?= e($tugas['judul'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Instruksi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5"
                                      maxlength="5000"><?= e($tugas['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="tenggat" class="form-label">Tenggat</label>
                            <input type="datetime-local" class="form-control" id="tenggat" name="tenggat"
                                   required value="<?= e($tenggatNilai) ?>">
                            <div class="form-text">
                                Pengumpulan setelah tenggat tetap diterima dan ditandai terlambat.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="berkas" class="form-label">
                                Lampiran <span class="text-body-secondary">(opsional)</span>
                            </label>
                            <input type="file" class="form-control" id="berkas" name="berkas">
                            <div class="form-text">Format: <?= e($ekstensi) ?>. Maksimal <?= e($maks) ?>.</div>
                            <?php if ($adalahEdit && $tugas['lampiran'] !== null): ?>
                                <div class="form-text">
                                    Lampiran saat ini:
                                    <a href="<?= e(url('/guru/tugas/lampiran/' . $tugas['id'])) ?>">
                                        <?= e($tugas['lampiran_asli']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a class="btn btn-outline-secondary" href="<?= e(url('/guru/tugas')) ?>">Batal</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
