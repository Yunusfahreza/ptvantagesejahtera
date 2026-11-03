<div class="row justify-content-center">
    <div class="col-12 col-md-9 col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= e(url('/guru/pengumuman')) ?>">Pengumuman</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= $pengumuman ? 'Edit Pengumuman' : 'Buat Baru' ?></li>
                    </ol>
                </nav>
                <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary);"><?= $pengumuman ? 'Ubah Warta Pengumuman' : 'Rilis Pengumuman Baru' ?></h1>
                <p class="small mb-0" style="color: var(--slate-tertiary);">Kirimkan pemberitahuan kepada seluruh siswa dalam rombel yang Anda ampu</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/guru/pengumuman')) ?>">
                &larr; Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <?php if ($pengampu === []): ?>
                    <div class="alert alert-warning mb-0 border-0">
                        Anda belum memiliki kelas yang diampu.
                    </div>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/guru/pengumuman/simpan')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($pengumuman['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="pengampu_id" class="form-label fw-bold">Target Rombel & Mata Pelajaran <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="pengampu_id" name="pengampu_id" required>
                                <?php foreach ($pengampu as $p): ?>
                                    <option value="<?= e($p['id']) ?>"
                                        <?= (int) ($pengumuman['pengampu_id'] ?? 0) === (int) $p['id'] ? 'selected' : '' ?>>
                                        Kelas <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="judul" class="form-label fw-bold">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2" id="judul" name="judul" required
                                   maxlength="150" value="<?= e($pengumuman['judul'] ?? '') ?>"
                                   placeholder="Contoh: Informasi Jadwal Remedial & Penyerahan Tugas Akhir">
                        </div>

                        <div class="mb-4">
                            <label for="isi" class="form-label fw-bold">Isi Pesan Pengumuman <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi" name="isi" rows="6" required
                                      maxlength="5000" placeholder="Tuliskan isi pengumuman atau instruksi pembelajaran secara lengkap..."><?= e($pengumuman['isi'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <a class="btn btn-outline-secondary px-4 py-2" href="<?= e(url('/guru/pengumuman')) ?>">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                                <?= $pengumuman ? 'Simpan Perubahan' : 'Siarkan Pengumuman' ?>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
