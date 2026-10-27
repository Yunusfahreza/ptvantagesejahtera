<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="<?= e(url('/siswa/mapel')) ?>" class="text-white-50">Mata Pelajaran</a></li>
            <li class="breadcrumb-item"><a href="<?= e(url('/siswa/video/' . $video['pengampu_id'])) ?>" class="text-white-50"><?= e($video['mapel_nama']) ?></a></li>
            <li class="breadcrumb-item active text-white" aria-current="page"><?= e($video['judul']) ?></li>
        </ol>
    </nav>
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h1 class="h4 mb-0 text-white fw-bold"><?= e($video['judul']) ?></h1>
        <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/siswa/video/' . $video['pengampu_id'])) ?>">Kembali ke Daftar</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-9">
        <div class="card shadow-sm border-0 overflow-hidden mb-3">
            <div class="ratio ratio-16x9 bg-black">
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
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary-subtle gap-2">
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-2">
                            <?= e($video['mapel_nama']) ?> (<?= e($video['mapel_kode']) ?>)
                        </span>
                        <span class="small text-white-50">Pengajar: <?= e($video['guru_nama']) ?></span>
                    </div>
                    <div class="small text-white-50">
                        Diunggah pada <?= e(tanggal_id($video['created_at'])) ?>
                        <?php if (($video['durasi'] ?? '') !== ''): ?>
                            &bull; ⏱️ <?= e($video['durasi']) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <h6 class="text-white fw-bold mb-2">Deskripsi & Catatan Materi</h6>
                <?php if (($video['deskripsi'] ?? '') !== ''): ?>
                    <div class="text-white-50 small lh-lg" style="white-space: pre-wrap;"><?= e($video['deskripsi']) ?></div>
                <?php else: ?>
                    <p class="text-white-50 small mb-0 fst-italic">Tidak ada deskripsi tambahan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body p-3">
                <h6 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
                    <span>💡</span> Tips Belajar Mandiri
                </h6>
                <ul class="text-white-50 small ps-3 mb-0">
                    <li class="mb-2">Gunakan headphone agar fokus mendengarkan penjelasan.</li>
                    <li class="mb-2">Pause video saat guru menulis rumus atau poin penting.</li>
                    <li class="mb-2">Buka menu <strong>Catatan Belajar</strong> untuk mencatat inti pembahasan.</li>
                    <li>Uji pemahaman dengan mengerjakan Kuis & Tugas terkait.</li>
                </ul>
            </div>
        </div>
        <a href="<?= e(url('/siswa/catatan/form')) ?>" class="btn btn-outline-info w-100 btn-sm d-flex align-items-center justify-content-center gap-2">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            <span>Tulis Catatan untuk Video Ini</span>
        </a>
    </div>
</div>
