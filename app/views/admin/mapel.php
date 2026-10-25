<h1 class="h4 mb-3">Mata Pelajaran</h1>

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent fw-semibold">
                <?= $edit ? 'Ubah Mata Pelajaran' : 'Tambah Mata Pelajaran' ?>
            </div>
            <div class="card-body">
                <form method="post" action="<?= e(url('/admin/mapel/simpan')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= e($edit['id'] ?? '0') ?>">

                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode</label>
                        <input type="text" class="form-control text-uppercase" id="kode" name="kode"
                               placeholder="MTK" required maxlength="20"
                               value="<?= e($edit['kode'] ?? '') ?>">
                        <div class="form-text">Huruf dan angka, 2 sampai 20 karakter. Harus unik.</div>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama"
                               placeholder="Matematika" required maxlength="100"
                               value="<?= e($edit['nama'] ?? '') ?>">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan' : 'Tambah' ?></button>
                        <?php if ($edit): ?>
                            <a class="btn btn-outline-secondary" href="<?= e(url('/admin/mapel')) ?>">Batal</a>
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
                            <th>Kode</th>
                            <th>Nama</th>
                            <th class="text-center">Dipakai</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data === []): ?>
                            <tr><td colspan="4" class="text-center text-body-secondary py-4">
                                Belum ada mata pelajaran.
                            </td></tr>
                        <?php endif; ?>

                        <?php foreach ($data as $m): ?>
                            <tr>
                                <td><span class="badge text-bg-light border"><?= e($m['kode']) ?></span></td>
                                <td class="fw-semibold"><?= e($m['nama']) ?></td>
                                <td class="text-center"><?= e($m['jumlah_pengampu']) ?> kelas</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a class="btn btn-sm btn-outline-primary"
                                           href="<?= e(url('/admin/mapel?edit=' . $m['id'])) ?>">Ubah</a>
                                        <form method="post" action="<?= e(url('/admin/mapel/hapus')) ?>"
                                              data-konfirmasi="Hapus mata pelajaran ini?">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e($m['id']) ?>">
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
