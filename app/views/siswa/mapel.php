<h1 class="h4 mb-3">Mata Pelajaran</h1>

<?php if ($data === []): ?>
    <div class="alert alert-info">
        Belum ada mata pelajaran yang terdaftar untuk kelas Anda.
    </div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($data as $p): ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <span class="badge text-bg-light border mb-2"><?= e($p['mapel_kode']) ?></span>
                    <h2 class="h6 mb-1"><?= e($p['mapel_nama']) ?></h2>
                    <p class="small text-body-secondary mb-0"><?= e($p['guru_nama']) ?></p>
                </div>
                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                    <span class="small text-body-secondary"><?= e($p['jumlah_materi']) ?> materi</span>
                    <a class="btn btn-sm btn-outline-primary"
                       href="<?= e(url('/siswa/materi/' . $p['id'])) ?>">Lihat Materi</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
