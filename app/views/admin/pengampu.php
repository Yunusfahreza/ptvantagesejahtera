<h1 class="h4 mb-3">Guru Pengampu</h1>
<p class="text-body-secondary">
    Penugasan ini menjadi konteks seluruh konten. Satu mata pelajaran di satu kelas
    hanya boleh dipegang satu guru.
</p>

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent fw-semibold">
                <?= $edit ? 'Ubah Penugasan' : 'Tambah Penugasan' ?>
            </div>
            <div class="card-body">
                <?php if ($guru === [] || $kelas === [] || $mapel === []): ?>
                    <p class="text-body-secondary mb-0">
                        Lengkapi dulu data
                        <a href="<?= e(url('/admin/pengguna?role=guru')) ?>">guru</a>,
                        <a href="<?= e(url('/admin/kelas')) ?>">kelas</a>, dan
                        <a href="<?= e(url('/admin/mapel')) ?>">mata pelajaran</a>.
                    </p>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/admin/pengampu/simpan')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="guru_id" class="form-label">Guru</label>
                            <select class="form-select" id="guru_id" name="guru_id" required>
                                <option value="0">-- pilih guru --</option>
                                <?php foreach ($guru as $g): ?>
                                    <option value="<?= e($g['id']) ?>"
                                        <?= (int) ($edit['guru_id'] ?? 0) === (int) $g['id'] ? 'selected' : '' ?>>
                                        <?= e($g['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select class="form-select" id="kelas_id" name="kelas_id" required>
                                <option value="0">-- pilih kelas --</option>
                                <?php foreach ($kelas as $k): ?>
                                    <option value="<?= e($k['id']) ?>"
                                        <?= (int) ($edit['kelas_id'] ?? 0) === (int) $k['id'] ? 'selected' : '' ?>>
                                        <?= e($k['nama']) ?> &middot; <?= e($k['tahun_nama']) ?>
                                        <?= (int) $k['tahun_aktif'] === 1 ? '(aktif)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="mapel_id" class="form-label">Mata pelajaran</label>
                            <select class="form-select" id="mapel_id" name="mapel_id" required>
                                <option value="0">-- pilih mapel --</option>
                                <?php foreach ($mapel as $m): ?>
                                    <option value="<?= e($m['id']) ?>"
                                        <?= (int) ($edit['mapel_id'] ?? 0) === (int) $m['id'] ? 'selected' : '' ?>>
                                        <?= e($m['kode']) ?> &mdash; <?= e($m['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan' : 'Tambah' ?></button>
                            <?php if ($edit): ?>
                                <a class="btn btn-outline-secondary" href="<?= e(url('/admin/pengampu')) ?>">Batal</a>
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
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Tahun Ajaran</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data === []): ?>
                            <tr><td colspan="5" class="text-center text-body-secondary py-4">
                                Belum ada penugasan guru pengampu.
                            </td></tr>
                        <?php endif; ?>

                        <?php foreach ($data as $p): ?>
                            <tr>
                                <td class="fw-semibold"><?= e($p['kelas_nama']) ?></td>
                                <td>
                                    <span class="badge text-bg-light border"><?= e($p['mapel_kode']) ?></span>
                                    <?= e($p['mapel_nama']) ?>
                                </td>
                                <td><?= e($p['guru_nama']) ?></td>
                                <td>
                                    <?= e($p['tahun_nama']) ?>
                                    <?php if ((int) $p['tahun_aktif'] === 1): ?>
                                        <span class="badge text-bg-success">aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a class="btn btn-sm btn-outline-primary"
                                           href="<?= e(url('/admin/pengampu?edit=' . $p['id'])) ?>">Ubah</a>
                                        <form method="post" action="<?= e(url('/admin/pengampu/hapus')) ?>"
                                              data-konfirmasi="Hapus penugasan ini?">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e($p['id']) ?>">
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
