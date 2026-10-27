<h1 class="h4 mb-3">Kelas yang Saya Ampu</h1>

<?php if ($data === []): ?>
    <div class="alert alert-warning">
        Anda belum ditetapkan sebagai guru pengampu. Hubungi administrator sekolah.
    </div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($data as $p): ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h2 class="h5 mb-0"><?= e($p['kelas_nama']) ?></h2>
                        <?php if ((int) $p['tahun_aktif'] === 1): ?>
                            <span class="badge text-bg-success">aktif</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary"><?= e($p['tahun_nama']) ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="mb-0">
                        <span class="badge text-bg-light border"><?= e($p['mapel_kode']) ?></span>
                        <?= e($p['mapel_nama']) ?>
                    </p>
                </div>
                <div class="card-footer bg-transparent d-flex flex-wrap gap-1">
                    <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/guru/materi')) ?>">Materi</a>
                    <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/guru/tugas')) ?>">Tugas</a>
                    <a class="btn btn-sm btn-outline-primary" href="<?= e(url('/guru/kuis')) ?>">Kuis</a>
                    <a class="btn btn-sm btn-outline-secondary"
                       href="<?= e(url('/guru/rekap/kelas/' . $p['id'])) ?>">Rekap</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
