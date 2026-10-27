<nav aria-label="breadcrumb">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= e(url('/siswa/mapel')) ?>" class="text-white-50">Mata Pelajaran</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page"><?= e($pengampu['mapel_nama']) ?></li>
    </ol>
</nav>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 mb-1 text-white fw-bold">Materi <?= e($pengampu['mapel_nama']) ?></h1>
        <p class="text-white-50 small mb-0">
            Kelas <?= e($pengampu['kelas_nama']) ?> &middot; Guru Pengampu: <?= e($pengampu['guru_nama']) ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-info btn-sm d-inline-flex align-items-center gap-1" href="<?= e(url('/siswa/video/' . $pengampu['id'])) ?>">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            <span>Tonton Video Pembelajaran</span>
        </a>
    </div>
</div>

<?php if ($data === []): ?>
    <div class="card shadow-sm border-0 text-center py-5">
        <div class="text-body-secondary">
            <div class="fs-1 mb-2">📚</div>
            <p class="mb-0">Belum ada modul atau materi berkas untuk mata pelajaran ini.</p>
        </div>
    </div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($data as $mt): ?>
        <?php
        $ext = strtolower(pathinfo((string) $mt['file_asli'], PATHINFO_EXTENSION));
        $bisaPreview = in_array($ext, ['pdf', 'png', 'jpg', 'jpeg', 'webp'], true);
        ?>
        <div class="col-12 col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-secondary-subtle text-white-50 border border-secondary-subtle text-uppercase">
                            <?= e($ext ?: 'file') ?>
                        </span>
                        <span class="small text-white-50"><?= e(tanggal_id($mt['created_at'])) ?></span>
                    </div>
                    <h2 class="h6 text-white fw-bold mb-2"><?= e($mt['judul']) ?></h2>
                    <?php if (($mt['deskripsi'] ?? '') !== ''): ?>
                        <p class="text-white-50 small mb-0"><?= nl2br(e($mt['deskripsi'])) ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent border-top border-secondary-subtle d-flex flex-wrap gap-2 align-items-center">
                    <?php if ($bisaPreview): ?>
                        <a class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" target="_blank"
                           href="<?= e(url('/siswa/materi/lihat/' . $mt['id'])) ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span>Baca Sekarang</span>
                        </a>
                    <?php endif; ?>
                    <a class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1"
                       href="<?= e(url('/siswa/materi/unduh/' . $mt['id'])) ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        <span>Unduh (<?= e(mb_strimwidth((string) $mt['file_asli'], 0, 20, '...')) ?>)</span>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
