<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="h4 mb-0">Materi</h1>
    <?php if ($pengampu !== []): ?>
        <a class="btn btn-primary btn-sm" href="<?= e(url('/guru/materi/form')) ?>">Unggah Materi</a>
    <?php endif; ?>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun.
        Hubungi administrator sekolah.
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Berkas</th>
                    <th>Diunggah</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data === []): ?>
                    <tr><td colspan="6" class="text-center text-body-secondary py-4">
                        Belum ada materi.
                    </td></tr>
                <?php endif; ?>

                <?php foreach ($data as $mt): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?= e($mt['judul']) ?></div>
                            <?php if (($mt['deskripsi'] ?? '') !== ''): ?>
                                <div class="small text-body-secondary">
                                    <?= e(mb_strimwidth((string) $mt['deskripsi'], 0, 90, '...')) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?= e($mt['kelas_nama']) ?></td>
                        <td>
                            <span class="badge text-bg-light border"><?= e($mt['mapel_kode']) ?></span>
                            <?= e($mt['mapel_nama']) ?>
                        </td>
                        <td>
                            <a href="<?= e(url('/guru/materi/unduh/' . $mt['id'])) ?>">
                                <?= e(mb_strimwidth((string) $mt['file_asli'], 0, 28, '...')) ?>
                            </a>
                        </td>
                        <td class="small"><?= e(tanggal_id($mt['created_at'])) ?></td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= e(url('/guru/materi/form/' . $mt['id'])) ?>">Ubah</a>
                                <form method="post" action="<?= e(url('/guru/materi/hapus')) ?>"
                                      data-konfirmasi="Hapus materi ini beserta berkasnya?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($mt['id']) ?>">
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
