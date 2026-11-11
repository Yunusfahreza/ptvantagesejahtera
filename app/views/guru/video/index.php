<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Media Pembelajaran</span>
            <span>&bull;</span>
            <span>Video & Multimedia</span>
        </div>
        <h1 class="page-title">Video Pembelajaran</h1>
        <p class="page-subtitle">Kelola tayangan video modul interaktif via YouTube, Google Drive, atau berkas MP4</p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="search-pill-wrapper d-none d-md-block" style="max-width: 250px;">
            <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="search-pill-input" placeholder="Cari judul video..." data-instant-filter="#tabelVideoGuru">
        </div>
        <?php if ($pengampu !== []): ?>
            <a class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm" href="<?= e(url('/guru/video/form')) ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                <span>Tambah Video Baru</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun. Silakan hubungi administrator sekolah.
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="min-width: 220px;">Judul Video</th>
                    <th>Sumber Tayang</th>
                    <th>Kelas & Mata Pelajaran</th>
                    <th>Durasi</th>
                    <th>Waktu Unggah</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelVideoGuru">
                <?php if ($data === []): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color: var(--slate-muted);">
                            <div class="mb-2 opacity-50">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                                </svg>
                            </div>
                            Belum ada video pembelajaran. Klik tombol "Tambah Video Baru" untuk mengunggah materi pertama Anda.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $v): ?>
                    <tr>
                        <td>
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($v['judul']) ?></div>
                            <?php if (($v['deskripsi'] ?? '') !== ''): ?>
                                <div class="small" style="color: var(--slate-muted); font-size: 0.76rem;">
                                    <?= e(mb_strimwidth((string) $v['deskripsi'], 0, 75, '...')) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($v['tipe'] === 'youtube'): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1">
                                    YouTube
                                </span>
                            <?php elseif ($v['tipe'] === 'gdrive'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                    Google Drive
                                </span>
                            <?php else: ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">
                                    Berkas MP4
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-semibold" style="color: var(--slate-primary); font-size: 0.88rem;"><?= e($v['kelas_nama']) ?></div>
                            <div class="small" style="color: var(--slate-muted); font-size: 0.74rem;"><?= e($v['mapel_nama']) ?></div>
                        </td>
                        <td>
                            <span class="small fw-semibold d-inline-flex align-items-center gap-1" style="color: var(--slate-secondary);">
                                <?php if (($v['durasi'] ?? '') !== ''): ?>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    <?= e($v['durasi']) ?>
                                <?php else: ?>
                                    <span class="text-muted">&mdash;</span>
                                <?php endif; ?>
                            </span>
                        </td>
                        <td class="small" style="color: var(--slate-muted); font-size: 0.74rem;">
                            <?= e(tanggal_id($v['created_at'])) ?>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1.5">
                                <a href="<?= e(url('/guru/video/form/' . $v['id'])) ?>" class="btn btn-sm btn-outline-primary px-2.5 py-1">
                                    Ubah
                                </a>
                                <form method="post" action="<?= e(url('/guru/video/hapus')) ?>" data-konfirmasi="Hapus video ini beserta tautan/berkasnya?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($v['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger px-2.5 py-1">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
