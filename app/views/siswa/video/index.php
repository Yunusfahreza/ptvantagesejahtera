<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?= e(url('/siswa/mapel')) ?>" class="text-white-50">Mata Pelajaran</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= e($pengampu['mapel_nama']) ?></li>
            </ol>
        </nav>
        <h1 class="h4 mb-1 text-white fw-bold">Video Pembelajaran</h1>
        <p class="text-white-50 small mb-0">Guru Pengampu: <?= e($pengampu['guru_nama']) ?> &bull; Kelas <?= e($pengampu['kelas_nama']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/siswa/materi/' . $pengampu['id'])) ?>">
            Lihat Modul / PDF
        </a>
    </div>
</div>

<div class="row g-3">
    <?php if ($data === []): ?>
        <div class="col-12">
            <div class="card shadow-sm border-0 text-center py-5">
                <div class="text-body-secondary">
                    <div class="fs-1 mb-2">🎬</div>
                    <p class="mb-0">Belum ada video pembelajaran untuk mata pelajaran ini.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($data as $v): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 transition-hover">
                <div class="card-body d-flex flex-column p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            <?= $v['tipe'] === 'youtube' ? 'YouTube' : ($v['tipe'] === 'gdrive' ? 'Google Drive' : 'MP4') ?>
                        </span>
                        <?php if (($v['durasi'] ?? '') !== ''): ?>
                            <span class="small text-white-50">⏱️ <?= e($v['durasi']) ?></span>
                        <?php endif; ?>
                    </div>
                    <h5 class="card-title text-white fw-bold mb-2"><?= e($v['judul']) ?></h5>
                    <?php if (($v['deskripsi'] ?? '') !== ''): ?>
                        <p class="card-text text-white-50 small flex-grow-1">
                            <?= e(mb_strimwidth((string) $v['deskripsi'], 0, 110, '...')) ?>
                        </p>
                    <?php else: ?>
                        <div class="flex-grow-1"></div>
                    <?php endif; ?>
                    <div class="pt-3 border-top border-secondary-subtle d-flex justify-content-between align-items-center mt-3">
                        <span class="small text-white-50"><?= e(tanggal_id($v['created_at'])) ?></span>
                        <a href="<?= e(url('/siswa/video/tonton/' . $v['id'])) ?>" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            <span>Tonton</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
