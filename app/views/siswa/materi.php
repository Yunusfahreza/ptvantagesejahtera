<nav aria-label="breadcrumb">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= e(url('/siswa/mapel')) ?>">Mata Pelajaran</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= e($pengampu['mapel_nama']) ?></li>
    </ol>
</nav>

<h1 class="h4 mb-1">Materi <?= e($pengampu['mapel_nama']) ?></h1>
<p class="text-body-secondary">
    Kelas <?= e($pengampu['kelas_nama']) ?> &middot; Guru: <?= e($pengampu['guru_nama']) ?>
</p>

<?php if ($data === []): ?>
    <div class="alert alert-info">Belum ada materi untuk mata pelajaran ini.</div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($data as $mt): ?>
        <div class="col-12 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h6 mb-1"><?= e($mt['judul']) ?></h2>
                    <p class="small text-body-secondary mb-2"><?= e(tanggal_id($mt['created_at'])) ?></p>
                    <?php if (($mt['deskripsi'] ?? '') !== ''): ?>
                        <p class="mb-0"><?= nl2br(e($mt['deskripsi'])) ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent">
                    <a class="btn btn-sm btn-primary"
                       href="<?= e(url('/siswa/materi/unduh/' . $mt['id'])) ?>">
                        Unduh <?= e(mb_strimwidth((string) $mt['file_asli'], 0, 24, '...')) ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
