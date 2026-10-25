<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="h4 mb-0">Tahun Ajaran</h1>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent fw-semibold">
                <?= $edit ? 'Ubah Tahun Ajaran' : 'Tambah Tahun Ajaran' ?>
            </div>
            <div class="card-body">
                <form method="post" action="<?= e(url('/admin/tahun/simpan')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= e($edit['id'] ?? '0') ?>">

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                               placeholder="2026/2027" required maxlength="20"
                               value="<?= e($edit['nama'] ?? '') ?>">
                        <div class="form-text">Format: 2026/2027</div>
                    </div>

                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <?php foreach (['ganjil', 'genap'] as $s): ?>
                                <option value="<?= e($s) ?>"
                                    <?= ($edit['semester'] ?? '') === $s ? 'selected' : '' ?>>
                                    <?= e(ucfirst($s)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan' : 'Tambah' ?></button>
                        <?php if ($edit): ?>
                            <a class="btn btn-outline-secondary" href="<?= e(url('/admin/tahun')) ?>">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Semester</th>
                            <th class="text-center">Kelas</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data === []): ?>
                            <tr><td colspan="5" class="text-center text-body-secondary py-4">
                                Belum ada tahun ajaran.
                            </td></tr>
                        <?php endif; ?>

                        <?php foreach ($data as $t): ?>
                            <tr>
                                <td class="fw-semibold"><?= e($t['nama']) ?></td>
                                <td><?= e(ucfirst($t['semester'])) ?></td>
                                <td class="text-center"><?= e($t['jumlah_kelas']) ?></td>
                                <td>
                                    <?php if ((int) $t['aktif'] === 1): ?>
                                        <span class="badge text-bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a class="btn btn-sm btn-outline-primary"
                                           href="<?= e(url('/admin/tahun?edit=' . $t['id'])) ?>">Ubah</a>

                                        <?php if ((int) $t['aktif'] !== 1): ?>
                                            <form method="post" action="<?= e(url('/admin/tahun/aktifkan')) ?>"
                                                  data-konfirmasi="Aktifkan tahun ajaran ini? Tahun ajaran lain akan dinonaktifkan.">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= e($t['id']) ?>">
                                                <button class="btn btn-sm btn-outline-success">Aktifkan</button>
                                            </form>

                                            <form method="post" action="<?= e(url('/admin/tahun/hapus')) ?>"
                                                  data-konfirmasi="Hapus tahun ajaran ini?">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= e($t['id']) ?>">
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
