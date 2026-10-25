<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="h4 mb-0">Pengumuman</h1>
    <?php if ($pengampu !== []): ?>
        <a class="btn btn-primary btn-sm" href="<?= e(url('/guru/pengumuman/form')) ?>">Buat Pengumuman</a>
    <?php endif; ?>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun.
    </div>
<?php endif; ?>

<?php if ($data === []): ?>
    <div class="alert alert-info">Belum ada pengumuman.</div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($data as $pm): ?>
        <div class="col-12 col-lg-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <h2 class="h6 mb-0"><?= e($pm['judul']) ?></h2>
                        <span class="badge text-bg-light border text-nowrap">
                            <?= e($pm['kelas_nama']) ?>
                        </span>
                    </div>
                    <p class="small text-body-secondary mb-2">
                        <?= e($pm['mapel_nama']) ?> &middot; <?= e(tanggal_id($pm['created_at'])) ?>
                    </p>
                    <p class="mb-0"><?= nl2br(e($pm['isi'])) ?></p>
                </div>
                <div class="card-footer bg-transparent d-flex gap-1">
                    <a class="btn btn-sm btn-outline-primary"
                       href="<?= e(url('/guru/pengumuman/form/' . $pm['id'])) ?>">Ubah</a>
                    <form method="post" action="<?= e(url('/guru/pengumuman/hapus')) ?>"
                          data-konfirmasi="Hapus pengumuman ini?">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($pm['id']) ?>">
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
