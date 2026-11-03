<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Data Pokok Pendidikan</span>
            <span>&bull;</span>
            <span>Rombongan Belajar</span>
        </div>
        <h1 class="page-title">Manajemen Kelas (Rombel)</h1>
        <p class="page-subtitle">Bentuk dan kelola rombongan belajar siswa per tingkat dan tahun ajaran aktif</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Tambah / Ubah Kelas -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 5rem; z-index: 10;">
            <div class="card-header py-3">
                <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">
                    <?= $edit ? 'Ubah Rincian Kelas' : 'Tambah Rombel Kelas Baru' ?>
                </span>
            </div>
            <div class="card-body p-4">
                <?php if ($tahun === []): ?>
                    <div class="alert alert-warning border-0 small mb-0">
                        Buat <a class="fw-bold text-decoration-none" href="<?= e(url('/admin/tahun')) ?>">tahun ajaran</a> terlebih dahulu sebelum menambah kelas.
                    </div>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/admin/kelas/simpan')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2" id="nama" name="nama"
                                   placeholder="Contoh: XI-IPA 1 atau X-4" required maxlength="30"
                                   value="<?= e($edit['nama'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="tingkat" class="form-label fw-bold">Tingkat Jenjang <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="tingkat" name="tingkat" required>
                                <?php foreach (['X', 'XI', 'XII'] as $t): ?>
                                    <option value="<?= e($t) ?>"
                                        <?= ($edit['tingkat'] ?? '') === $t ? 'selected' : '' ?>>
                                        Kelas <?= e($t) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tahun_ajaran_id" class="form-label fw-bold">Tahun Ajaran <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="tahun_ajaran_id" name="tahun_ajaran_id" required>
                                <?php foreach ($tahun as $t): ?>
                                    <option value="<?= e($t['id']) ?>"
                                        <?php
                                        $terpilih = isset($edit)
                                            ? (int) ($edit['tahun_ajaran_id'] ?? 0) === (int) $t['id']
                                            : (int) $t['aktif'] === 1;
                                        echo $terpilih ? 'selected' : '';
                                        ?>>
                                        <?= e($t['nama']) ?> &middot; <?= e($t['semester']) ?>
                                        <?= (int) $t['aktif'] === 1 ? '(Aktif)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex gap-2 pt-2 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <button type="submit" class="btn btn-primary px-3 py-1.5 fw-bold shadow-sm">
                                <?= $edit ? 'Simpan Perubahan' : 'Tambahkan Kelas' ?>
                            </button>
                            <?php if ($edit): ?>
                                <a class="btn btn-outline-secondary px-3 py-1.5" href="<?= e(url('/admin/kelas')) ?>">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kelas -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Tahun Ajaran</th>
                            <th class="text-center">Siswa Terdaftar</th>
                            <th class="text-center">Mapel Diampu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data === []): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color: var(--slate-muted);">
                                    Belum ada rombel kelas yang dibentuk.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($data as $k): ?>
                            <tr>
                                <td>
                                    <span class="fw-bold fs-6" style="color: var(--slate-primary);"><?= e($k['nama']) ?></span>
                                </td>
                                <td>
                                    <span class="badge px-2 py-1 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border-radius: 6px;">
                                        Kelas <?= e($k['tingkat']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="small fw-semibold" style="color: var(--slate-secondary);"><?= e($k['tahun_nama']) ?> &bull; <?= e($k['semester']) ?></span>
                                    <?php if ((int) $k['tahun_aktif'] === 1): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-1.5 py-0.5 ms-1" style="font-size: 0.65rem;">Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge px-2.5 py-1 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border-radius: 6px; font-size: 0.76rem;">
                                        <?= e($k['jumlah_siswa']) ?> Siswa
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge px-2.5 py-1 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-secondary); border-radius: 6px; font-size: 0.76rem;">
                                        <?= e($k['jumlah_pengampu']) ?> Mapel
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1.5">
                                        <a class="btn btn-sm btn-outline-primary px-2.5 py-1"
                                           href="<?= e(url('/admin/kelas?edit=' . $k['id'])) ?>">Ubah</a>
                                        <form method="post" action="<?= e(url('/admin/kelas/hapus')) ?>"
                                              data-konfirmasi="Hapus kelas ini? Data penugasan siswa di kelas ini akan terpengaruh.">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e($k['id']) ?>">
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
