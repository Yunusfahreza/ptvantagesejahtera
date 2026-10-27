<h1 class="h4 mb-3">Pengumuman</h1>

<?php if ($data === []): ?>
    <div class="alert alert-info">Belum ada pengumuman untuk kelas Anda.</div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <?php foreach ($data as $pm): ?>
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <h2 class="h6 mb-0"><?= e($pm['judul']) ?></h2>
                        <span class="badge text-bg-light border text-nowrap">
                            <?= e($pm['mapel_kode']) ?>
                        </span>
                    </div>
                    <p class="small text-body-secondary mb-2">
                        <?= e($pm['mapel_nama']) ?> &middot; <?= e($pm['guru_nama']) ?>
                        &middot; <?= e(tanggal_id($pm['created_at'])) ?>
                    </p>
                    <p class="mb-0"><?= nl2br(e($pm['isi'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
