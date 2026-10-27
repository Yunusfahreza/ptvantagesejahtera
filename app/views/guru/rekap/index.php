<h1 class="h4 mb-3">Rekap Nilai</h1>

<?php if ($data === []): ?>
    <div class="alert alert-warning">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun.
    </div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($data as $p): ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5 mb-1"><?= e($p['kelas_nama']) ?></h2>
                    <p class="mb-0">
                        <span class="badge text-bg-light border"><?= e($p['mapel_kode']) ?></span>
                        <?= e($p['mapel_nama']) ?>
                    </p>
                    <p class="small text-body-secondary mb-0 mt-1">
                        <?= e($p['tahun_nama']) ?>
                        <?= (int) $p['tahun_aktif'] === 1 ? '(aktif)' : '' ?>
                    </p>
                </div>
                <div class="card-footer bg-transparent d-flex gap-2">
                    <a class="btn btn-sm btn-primary"
                       href="<?= e(url('/guru/rekap/kelas/' . $p['id'])) ?>">Buka Rekap</a>
                    <a class="btn btn-sm btn-outline-secondary"
                       href="<?= e(url('/guru/rekap/csv/' . $p['id'])) ?>">Unduh CSV</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
