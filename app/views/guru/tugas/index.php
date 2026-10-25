<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="h4 mb-0">Tugas</h1>
    <?php if ($pengampu !== []): ?>
        <a class="btn btn-primary btn-sm" href="<?= e(url('/guru/tugas/form')) ?>">Buat Tugas</a>
    <?php endif; ?>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun.
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Tenggat</th>
                    <th class="text-center">Terkumpul</th>
                    <th class="text-center">Belum dinilai</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data === []): ?>
                    <tr><td colspan="7" class="text-center text-body-secondary py-4">
                        Belum ada tugas.
                    </td></tr>
                <?php endif; ?>

                <?php foreach ($data as $t): ?>
                    <?php $lewat = strtotime((string) $t['tenggat']) < time(); ?>
                    <tr>
                        <td class="fw-semibold"><?= e($t['judul']) ?></td>
                        <td><?= e($t['kelas_nama']) ?></td>
                        <td>
                            <span class="badge text-bg-light border"><?= e($t['mapel_kode']) ?></span>
                        </td>
                        <td class="small">
                            <?= e(tanggal_id($t['tenggat'])) ?>
                            <?php if ($lewat): ?>
                                <span class="badge text-bg-secondary">lewat</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?= e($t['jumlah_kumpul']) ?> / <?= e($t['jumlah_siswa']) ?>
                        </td>
                        <td class="text-center">
                            <?php if ((int) $t['belum_dinilai'] > 0): ?>
                                <span class="badge text-bg-warning"><?= e($t['belum_dinilai']) ?></span>
                            <?php else: ?>
                                <span class="text-body-secondary">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap gap-1 justify-content-end">
                                <a class="btn btn-sm btn-primary"
                                   href="<?= e(url('/guru/tugas/nilai/' . $t['id'])) ?>">Nilai</a>
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= e(url('/guru/tugas/form/' . $t['id'])) ?>">Ubah</a>
                                <form method="post" action="<?= e(url('/guru/tugas/hapus')) ?>"
                                      data-konfirmasi="Hapus tugas ini?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($t['id']) ?>">
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
