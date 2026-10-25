<div class="row justify-content-center">
    <div class="col-12 col-md-9 col-lg-7">
        <h1 class="h4 mb-3"><?= $pengumuman ? 'Ubah Pengumuman' : 'Buat Pengumuman' ?></h1>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <?php if ($pengampu === []): ?>
                    <p class="mb-0 text-body-secondary">Anda belum punya kelas yang diampu.</p>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/guru/pengumuman/simpan')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($pengumuman['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="pengampu_id" class="form-label">Kelas dan mata pelajaran</label>
                            <select class="form-select" id="pengampu_id" name="pengampu_id" required>
                                <?php foreach ($pengampu as $p): ?>
                                    <option value="<?= e($p['id']) ?>"
                                        <?= (int) ($pengumuman['pengampu_id'] ?? 0) === (int) $p['id'] ? 'selected' : '' ?>>
                                        <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" required
                                   maxlength="150" value="<?= e($pengumuman['judul'] ?? '') ?>">
                        </div>

                        <div class="mb-4">
                            <label for="isi" class="form-label">Isi pengumuman</label>
                            <textarea class="form-control" id="isi" name="isi" rows="6" required
                                      maxlength="5000"><?= e($pengumuman['isi'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a class="btn btn-outline-secondary"
                               href="<?= e(url('/guru/pengumuman')) ?>">Batal</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
