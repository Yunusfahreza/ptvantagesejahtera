<h1 class="h4 mb-3">Kelas</h1>

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent fw-semibold">
                <?= $edit ? 'Ubah Kelas' : 'Tambah Kelas' ?>
            </div>
            <div class="card-body">
                <?php if ($tahun === []): ?>
                    <p class="text-body-secondary mb-0">
                        Buat <a href="<?= e(url('/admin/tahun')) ?>">tahun ajaran</a> terlebih dahulu.
                    </p>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/admin/kelas/simpan')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama kelas</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                   placeholder="X-1" required maxlength="30"
                                   value="<?= e($edit['nama'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="tingkat" class="form-label">Tingkat</label>
                            <select class="form-select" id="tingkat" name="tingkat" required>
                                <?php foreach (['X', 'XI', 'XII'] as $t): ?>
                                    <option value="<?= e($t) ?>"
                                        <?= ($edit['tingkat'] ?? '') === $t ? 'selected' : '' ?>>
                                        <?= e($t) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tahun_ajaran_id" class="form-label">Tahun ajaran</label>
                            <select class="form-select" id="tahun_ajaran_id" name="tahun_ajaran_id" required>
                                <?php foreach ($tahun as $t): ?>
                                    <option value="<?= e($t['id']) ?>"
                                        <?php
                                        $terpilih = isset($edit)
                                            ? (int) ($edit['tahun_ajaran_id'] ?? 0) === (int) $t['id']
                                            : (int) $t['aktif'] === 1;
                                        echo $terpilih ? 'selected' : '';
                                        ?>>
                                        <?= e($t['nama']) ?> &middot; <?= e($t['semester']) ?>
                                        <?= (int) $t['aktif'] === 1 ? '(aktif)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan' : 'Tambah' ?></button>
                            <?php if ($edit): ?>
                                <a class="btn btn-outline-secondary" href="<?= e(url('/admin/kelas')) ?>">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kelas</th>
                            <th>Tingkat</th>
                            <th>Tahun Ajaran</th>
                            <th class="text-center">Siswa</th>
                            <th class="text-center">Mapel</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data === []): ?>
                            <tr><td colspan="6" class="text-center text-body-secondary py-4">
                                Belum ada kelas.
                            </td></tr>
                        <?php endif; ?>

                        <?php foreach ($data as $k): ?>
                            <tr>
                                <td class="fw-semibold"><?= e($k['nama']) ?></td>
                                <td><?= e($k['tingkat']) ?></td>
                                <td>
                                    <?= e($k['tahun_nama']) ?> &middot; <?= e($k['semester']) ?>
                                    <?php if ((int) $k['tahun_aktif'] === 1): ?>
                                        <span class="badge text-bg-success">aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?= e($k['jumlah_siswa']) ?></td>
                                <td class="text-center"><?= e($k['jumlah_pengampu']) ?></td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a class="btn btn-sm btn-outline-primary"
                                           href="<?= e(url('/admin/kelas?edit=' . $k['id'])) ?>">Ubah</a>
                                        <form method="post" action="<?= e(url('/admin/kelas/hapus')) ?>"
                                              data-konfirmasi="Hapus kelas ini?">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e($k['id']) ?>">
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
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
