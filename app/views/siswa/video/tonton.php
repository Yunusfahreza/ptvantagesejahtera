<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb page-breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="<?= e(url('/siswa/mapel')) ?>">Mata Pelajaran</a></li>
            <li class="breadcrumb-item"><a href="<?= e(url('/siswa/video/' . $video['pengampu_id'])) ?>"><?= e($video['mapel_nama']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= e($video['judul']) ?></li>
        </ol>
    </nav>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <h1 class="page-title mb-0"><?= e($video['judul']) ?></h1>
        <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/siswa/video/' . $video['pengampu_id'])) ?>">
            &larr; Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card shadow-sm border-0 overflow-hidden mb-4">
            <div class="ratio ratio-16x9" style="background: #000000; border-radius: var(--radius-lg) var(--radius-lg) 0 0;">
                <?php if ($video['tipe'] === 'youtube'): ?>
                    <iframe src="<?= e($video['url_embed']) ?>" title="<?= e($video['judul']) ?>"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                <?php elseif ($video['tipe'] === 'gdrive'): ?>
                    <iframe src="<?= e($video['url_embed']) ?>" title="<?= e($video['judul']) ?>"
                            allowfullscreen></iframe>
                <?php elseif ($video['tipe'] === 'upload'): ?>
                    <video controls controlsList="nodownload" preload="metadata" class="w-100 h-100">
                        <source src="<?= e(url('/siswa/video/stream/' . $video['id'])) ?>" type="video/mp4">
                        Peramban Anda tidak mendukung pemutar video HTML5.
                    </video>
                <?php endif; ?>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-3 border-bottom gap-2" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 8px;">
                            <?= e($video['mapel_nama']) ?> (<?= e($video['mapel_kode']) ?>)
                        </span>
                        <span class="small fw-semibold" style="color: var(--slate-secondary);">Pengajar: <?= e($video['guru_nama']) ?></span>
                    </div>
                    <div class="small fw-semibold" style="color: var(--slate-muted); font-size: 0.78rem;">
                        Diunggah: <?= e(tanggal_id($video['created_at'])) ?>
                        <?php if (($video['durasi'] ?? '') !== ''): ?>
                            &bull; â ±ï¸  <?= e($video['durasi']) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <h6 class="fw-bold mb-2" style="color: var(--slate-primary);">Deskripsi & Catatan Materi Pembelajaran</h6>
                <?php if (($video['deskripsi'] ?? '') !== ''): ?>
                    <div class="small lh-lg" style="color: var(--slate-secondary); white-space: pre-wrap; font-size: 0.88rem;"><?= e($video['deskripsi']) ?></div>
                <?php else: ?>
                    <p class="small mb-0 fst-italic" style="color: var(--slate-muted);">Tidak ada deskripsi tambahan dari pengajar.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4 col-xl-3">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="squircle-icon squircle-icon-amber" style="width: 32px; height: 32px; border-radius: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                    </div>
                    <h6 class="fw-bold mb-0" style="color: var(--slate-primary); font-size: 0.92rem;">Tips Belajar Mandiri</h6>
                </div>
                
                <ul class="small ps-3 mb-0 d-flex flex-column gap-2" style="color: var(--slate-secondary);">
                    <li>Gunakan earphone agar fokus mendengarkan pemaparan guru.</li>
                    <li>Jeda video saat guru menerangkan penurunan rumus atau konsep kunci.</li>
                    <li>Buka menu <strong style="color: var(--slate-primary);">Catatan Belajar</strong> untuk merekap intisari video.</li>
                    <li>Uji kemampuan materi ini dengan menyelesaikan kuis & tugas terkait.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
