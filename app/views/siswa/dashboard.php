<?php

use App\Core\Auth;

$user = Auth::user();
?>
<h1 class="h4 mb-1">Dashboard</h1>
<p class="text-body-secondary">
    <?= e($user['nama'] ?? '') ?>
    <?php if (!empty($mapel[0]['kelas_nama'])): ?>
        &middot; Kelas <?= e($mapel[0]['kelas_nama']) ?>
    <?php endif; ?>
</p>

<div class="row g-3 mb-4">
    <div class="col-4">
        <a class="text-decoration-none" href="<?= e(url('/siswa/mapel')) ?>">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <p class="display-6 fw-bold mb-0 text-primary"><?= e(count($mapel)) ?></p>
                    <p class="small text-body-secondary mb-0">Mata pelajaran</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center">
                <p class="display-6 fw-bold mb-0 text-primary"><?= e($jumlah_materi) ?></p>
                <p class="small text-body-secondary mb-0">Materi tersedia</p>
            </div>
        </div>
    </div>
    <div class="col-4">
        <a class="text-decoration-none" href="<?= e(url('/siswa/tugas')) ?>">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center">
                    <p class="display-6 fw-bold mb-0 text-warning"><?= e(count($tugas_mendekat)) ?></p>
                    <p class="small text-body-secondary mb-0">Tugas belum dikumpulkan</p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent fw-semibold">Tenggat mendekat</div>
            <ul class="list-group list-group-flush">
                <?php if ($tugas_mendekat === []): ?>
                    <li class="list-group-item small text-body-secondary">
                        Tidak ada tugas yang menunggu dikumpulkan.
                    </li>
                <?php endif; ?>

                <?php foreach ($tugas_mendekat as $t): ?>
                    <li class="list-group-item">
                        <a class="fw-semibold text-decoration-none"
                           href="<?= e(url('/siswa/tugas/detail/' . $t['id'])) ?>"><?= e($t['judul']) ?></a>
                        <div class="small text-body-secondary">
                            <?= e($t['mapel_nama']) ?> &middot; tenggat <?= e(tanggal_id($t['tenggat'])) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent fw-semibold">Kuis sedang dibuka</div>
            <ul class="list-group list-group-flush">
                <?php if ($kuis_terbuka === []): ?>
                    <li class="list-group-item small text-body-secondary">
                        Tidak ada kuis yang sedang dibuka.
                    </li>
                <?php endif; ?>

                <?php foreach ($kuis_terbuka as $q): ?>
                    <li class="list-group-item">
                        <a class="fw-semibold text-decoration-none"
                           href="<?= e(url('/siswa/kuis')) ?>"><?= e($q['judul']) ?></a>
                        <div class="small text-body-secondary">
                            <?= e($q['mapel_nama']) ?> &middot; ditutup <?= e(tanggal_id($q['selesai'])) ?>
                            <?php if ($q['selesai_at'] !== null): ?>
                                <span class="badge text-bg-success">sudah dikerjakan</span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-transparent fw-semibold">Pengumuman terbaru</div>
            <ul class="list-group list-group-flush">
                <?php if ($pengumuman === []): ?>
                    <li class="list-group-item small text-body-secondary">Belum ada pengumuman.</li>
                <?php endif; ?>

                <?php foreach ($pengumuman as $pm): ?>
                    <li class="list-group-item">
                        <a class="fw-semibold text-decoration-none"
                           href="<?= e(url('/siswa/pengumuman')) ?>"><?= e($pm['judul']) ?></a>
                        <div class="small text-body-secondary">
                            <?= e($pm['mapel_nama']) ?> &middot; <?= e(tanggal_id($pm['created_at'])) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
