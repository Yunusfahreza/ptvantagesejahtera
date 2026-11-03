<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Kalender Akademik</span>
            <span>&bull;</span>
            <span>Periode Pembelajaran</span>
        </div>
        <h1 class="page-title">Tahun Ajaran & Semester</h1>
        <p class="page-subtitle">Atur periode tahun ajaran aktif untuk sinkronisasi kurikulum, jadwal, dan asesmen</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Tambah / Ubah Tahun Ajaran -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 5rem; z-index: 10;">
            <div class="card-header py-3">
                <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">
                    <?= $edit ? 'Ubah Tahun Ajaran' : 'Tambah Periode Baru' ?>
                </span>
            </div>
            <div class="card-body p-4">
                <form method="post" action="<?= e(url('/admin/tahun/simpan')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= e($edit['id'] ?? '0') ?>">

                    <div class="mb-3">
                        <label for="nama" class="form-label fw-bold">Tahun Akademik <span class="text-danger">*</span></label>
                        <input type="text" class="form-control py-2" id="nama" name="nama"
                               placeholder="Contoh: 2026/2027" required maxlength="20"
                               value="<?= e($edit['nama'] ?? '') ?>">
                        <div class="form-text small" style="color: var(--slate-muted);">Format standar penulisan: <code>2026/2027</code></div>
                    </div>

                    <div class="mb-4">
                        <label for="semester" class="form-label fw-bold">Semester Pembelajaran <span class="text-danger">*</span></label>
                        <select class="form-select py-2" id="semester" name="semester" required>
                            <?php foreach (['ganjil', 'genap'] as $s): ?>
                                <option value="<?= e($s) ?>"
                                    <?= ($edit['semester'] ?? '') === $s ? 'selected' : '' ?>>
                                    Semester <?= e(ucfirst($s)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex gap-2 pt-2 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <button type="submit" class="btn btn-primary px-3 py-1.5 fw-bold shadow-sm">
                            <?= $edit ? 'Simpan Perubahan' : 'Tambahkan Periode' ?>
                        </button>
                        <?php if ($edit): ?>
                            <a class="btn btn-outline-secondary px-3 py-1.5" href="<?= e(url('/admin/tahun')) ?>">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Tahun Ajaran -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Tahun Akademik</th>
                            <th>Semester</th>
                            <th class="text-center">Jumlah Rombel</th>
                            <th>Status Aktif</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data === []): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color: var(--slate-muted);">
                                    Belum ada tahun ajaran terdaftar.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($data as $t): ?>
                            <tr>
                                <td>
                                    <span class="fw-bold fs-6" style="color: var(--slate-primary);"><?= e($t['nama']) ?></span>
                                </td>
                                <td>
                                    <span class="badge px-2.5 py-1 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border-radius: 6px;">
                                        Semester <?= e(ucfirst($t['semester'])) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge px-2.5 py-1 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border-radius: 6px; font-size: 0.74rem;">
                                        <?= e($t['jumlah_kelas']) ?> Rombel
                                    </span>
                                </td>
                                <td>
                                    <?php if ((int) $t['aktif'] === 1): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fw-bold">
                                            â—  Aktif Sekarang
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle px-2 py-0.5">
                                            Nonaktif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1.5">
                                        <a class="btn btn-sm btn-outline-primary px-2.5 py-1"
                                           href="<?= e(url('/admin/tahun?edit=' . $t['id'])) ?>">Ubah</a>

                                        <?php if ((int) $t['aktif'] !== 1): ?>
                                            <form method="post" action="<?= e(url('/admin/tahun/aktifkan')) ?>"
                                                  data-konfirmasi="Aktifkan tahun ajaran ini? Tahun ajaran lain akan otomatis dinonaktifkan oleh sistem.">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= e($t['id']) ?>">
                                                <button class="btn btn-sm btn-outline-success px-2.5 py-1">Aktifkan</button>
                                            </form>

                                            <form method="post" action="<?= e(url('/admin/tahun/hapus')) ?>"
                                                  data-konfirmasi="Hapus tahun ajaran ini?">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= e($t['id']) ?>">
                                                <button class="btn btn-sm btn-outline-danger px-2.5 py-1">Hapus</button>
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
