<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Kurikulum Sekolah</span>
            <span>&bull;</span>
            <span>Mata Pelajaran</span>
        </div>
        <h1 class="page-title">Master Mata Pelajaran</h1>
        <p class="page-subtitle">Daftarkan kode mata pelajaran dan struktur kurikulum yang diajarkan di SMAN 10 Garut</p>
    </div>
</div>

<div class="row g-4">
    <!-- Form Input / Edit Mapel -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 5rem; z-index: 10;">
            <div class="card-header py-3">
                <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">
                    <?= $edit ? 'Ubah Mata Pelajaran' : 'Tambah Mapel Baru' ?>
                </span>
            </div>
            <div class="card-body p-4">
                <form method="post" action="<?= e(url('/admin/mapel/simpan')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= e($edit['id'] ?? '0') ?>">

                    <div class="mb-3">
                        <label for="kode" class="form-label fw-bold">Kode Mapel <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase py-2" id="kode" name="kode"
                               placeholder="Contoh: MTK atau FIS" required maxlength="20"
                               value="<?= e($edit['kode'] ?? '') ?>">
                        <div class="form-text small" style="color: var(--slate-muted);">Kode unik pengenal mata pelajaran (2-20 karakter).</div>
                    </div>

                    <div class="mb-4">
                        <label for="nama" class="form-label fw-bold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control py-2" id="nama" name="nama"
                               placeholder="Contoh: Matematika Peminatan" required maxlength="100"
                               value="<?= e($edit['nama'] ?? '') ?>">
                    </div>

                    <div class="d-flex gap-2 pt-2 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <button type="submit" class="btn btn-primary px-3 py-1.5 fw-bold shadow-sm">
                            <?= $edit ? 'Simpan Perubahan' : 'Tambahkan Mapel' ?>
                        </button>
                        <?php if ($edit): ?>
                            <a class="btn btn-outline-secondary px-3 py-1.5" href="<?= e(url('/admin/mapel')) ?>">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Mapel -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 120px;">Kode</th>
                            <th>Nama Mata Pelajaran</th>
                            <th class="text-center" style="width: 140px;">Rombel Diampu</th>
                            <th class="text-end" style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data === []): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color: var(--slate-muted);">
                                    Belum ada mata pelajaran terdaftar.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($data as $m): ?>
                            <tr>
                                <td>
                                    <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 8px;">
                                        <?= e($m['kode']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold fs-6" style="color: var(--slate-primary);"><?= e($m['nama']) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge px-2.5 py-1 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-secondary); border-radius: 6px; font-size: 0.74rem;">
                                        <?= e($m['jumlah_pengampu']) ?> Rombel
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1.5">
                                        <a class="btn btn-sm btn-outline-primary px-2.5 py-1"
                                           href="<?= e(url('/admin/mapel?edit=' . $m['id'])) ?>">Ubah</a>
                                        <form method="post" action="<?= e(url('/admin/mapel/hapus')) ?>"
                                              data-konfirmasi="Hapus mata pelajaran ini?">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e($m['id']) ?>">
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
