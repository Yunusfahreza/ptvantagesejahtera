<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Manajemen Kurikulum</span>
            <span>&bull;</span>
            <span>Plotting Tenaga Pendidik</span>
        </div>
        <h1 class="page-title">Penugasan Guru Pengampu</h1>
        <p class="page-subtitle">Hubungkan tenaga pendidik ke rombongan belajar dan mata pelajaran yang diampu</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Tambah / Edit Pengampu -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 5rem; z-index: 10;">
            <div class="card-header py-3">
                <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">
                    <?= $edit ? 'Ubah Penugasan Guru' : 'Tambah Plotting Pengampu' ?>
                </span>
            </div>
            <div class="card-body p-4">
                <?php if ($guru === [] || $kelas === [] || $mapel === []): ?>
                    <div class="alert alert-warning border-0 small mb-0">
                        Pastikan data
                        <a class="fw-bold text-decoration-none" href="<?= e(url('/admin/pengguna?role=guru')) ?>">guru</a>,
                        <a class="fw-bold text-decoration-none" href="<?= e(url('/admin/kelas')) ?>">kelas</a>, dan
                        <a class="fw-bold text-decoration-none" href="<?= e(url('/admin/mapel')) ?>">mata pelajaran</a> sudah terdaftar di sistem.
                    </div>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/admin/pengampu/simpan')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="guru_id" class="form-label fw-bold">Tenaga Pendidik (Guru) <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="guru_id" name="guru_id" required>
                                <option value="0">-- Pilih Guru --</option>
                                <?php foreach ($guru as $g): ?>
                                    <option value="<?= e($g['id']) ?>"
                                        <?= (int) ($edit['guru_id'] ?? 0) === (int) $g['id'] ? 'selected' : '' ?>>
                                        <?= e($g['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kelas_id" class="form-label fw-bold">Rombel Kelas <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="kelas_id" name="kelas_id" required>
                                <option value="0">-- Pilih Kelas --</option>
                                <?php foreach ($kelas as $k): ?>
                                    <option value="<?= e($k['id']) ?>"
                                        <?= (int) ($edit['kelas_id'] ?? 0) === (int) $k['id'] ? 'selected' : '' ?>>
                                        Kelas <?= e($k['nama']) ?> &middot; <?= e($k['tahun_nama']) ?>
                                        <?= (int) $k['tahun_aktif'] === 1 ? '(Aktif)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="mapel_id" class="form-label fw-bold">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="mapel_id" name="mapel_id" required>
                                <option value="0">-- Pilih Mapel --</option>
                                <?php foreach ($mapel as $m): ?>
                                    <option value="<?= e($m['id']) ?>"
                                        <?= (int) ($edit['mapel_id'] ?? 0) === (int) $m['id'] ? 'selected' : '' ?>>
                                        <?= e($m['kode']) ?> &mdash; <?= e($m['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex gap-2 pt-2 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <button type="submit" class="btn btn-primary px-3 py-1.5 fw-bold shadow-sm">
                                <?= $edit ? 'Simpan Perubahan' : 'Tetapkan Pengampu' ?>
                            </button>
                            <?php if ($edit): ?>
                                <a class="btn btn-outline-secondary px-3 py-1.5" href="<?= e(url('/admin/pengampu')) ?>">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tabel Plotting Guru Pengampu -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th>Tahun Ajaran</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data === []): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color: var(--slate-muted);">
                                    Belum ada penugasan guru pengampu yang diatur.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($data as $p): ?>
                            <tr>
                                <td>
                                    <span class="fw-bold fs-6" style="color: var(--slate-primary);"><?= e($p['kelas_nama']) ?></span>
                                </td>
                                <td>
                                    <span class="badge px-2.5 py-1 fw-bold me-1" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 6px; font-size: 0.72rem;">
                                        <?= e($p['mapel_kode']) ?>
                                    </span>
                                    <span class="fw-semibold" style="color: var(--slate-secondary); font-size: 0.88rem;"><?= e($p['mapel_nama']) ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($p['guru_nama']) ?></div>
                                </td>
                                <td>
                                    <span class="small fw-semibold" style="color: var(--slate-secondary);"><?= e($p['tahun_nama']) ?></span>
                                    <?php if ((int) $p['tahun_aktif'] === 1): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-1.5 py-0.5 ms-1" style="font-size: 0.65rem;">Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1.5">
                                        <a class="btn btn-sm btn-outline-primary px-2.5 py-1"
                                           href="<?= e(url('/admin/pengampu?edit=' . $p['id'])) ?>">Ubah</a>
                                        <form method="post" action="<?= e(url('/admin/pengampu/hapus')) ?>"
                                              data-konfirmasi="Hapus penugasan pengampu ini?">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e($p['id']) ?>">
                                            <button class="btn btn-sm btn-outline-danger px-2.5 py-1">Hapus</button>
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
