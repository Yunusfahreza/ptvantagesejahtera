<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Manajemen Pembelajaran</span>
            <span>&bull;</span>
            <span>Bahan Ajar & Modul</span>
        </div>
        <h1 class="page-title">Materi & Bahan Ajar</h1>
        <p class="page-subtitle">Unggah dan kelola modul digital, dokumen bahan tayang, dan sumber belajar untuk siswa</p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="search-pill-wrapper d-none d-md-block" style="max-width: 250px;">
            <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="search-pill-input" placeholder="Cari materi..." data-instant-filter="#tabelMateriGuru">
        </div>
        <?php if ($pengampu !== []): ?>
            <a class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm" href="<?= e(url('/guru/materi/form')) ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                <span>Unggah Materi Baru</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun. Silakan hubungi admin kurikulum sekolah.
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="min-width: 220px;">Judul Materi</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Nama Berkas</th>
                    <th>Waktu Unggah</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelMateriGuru">
                <?php if ($data === []): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color: var(--slate-muted);">
                            <div class="mb-2">ðŸ“–</div>
                            Belum ada dokumen materi yang diunggah.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $mt): ?>
                    <?php
                    $ext = strtolower(pathinfo((string) $mt['file_asli'], PATHINFO_EXTENSION));
                    $bisaPreview = in_array($ext, ['pdf', 'png', 'jpg', 'jpeg', 'webp'], true);
                    ?>
                    <tr>
                        <td>
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($mt['judul']) ?></div>
                            <?php if (($mt['deskripsi'] ?? '') !== ''): ?>
                                <div class="small" style="color: var(--slate-muted); font-size: 0.76rem;">
                                    <?= e(mb_strimwidth((string) $mt['deskripsi'], 0, 80, '...')) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border-radius: 6px;">
                                <?= e($mt['kelas_nama']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge px-2 py-1 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border-radius: 6px; font-size: 0.72rem;">
                                <?= e($mt['mapel_kode']) ?>
                            </span>
                            <span class="small fw-semibold ms-1" style="color: var(--slate-secondary);"><?= e($mt['mapel_nama']) ?></span>
                        </td>
                        <td>
                            <a class="small fw-semibold text-decoration-none d-inline-flex align-items-center gap-1" style="color: var(--accent-sapphire);" 
                               href="<?= e(url('/guru/materi/unduh/' . $mt['id'])) ?>">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                <span><?= e(mb_strimwidth((string) $mt['file_asli'], 0, 24, '...')) ?></span>
                            </a>
                        </td>
                        <td class="small" style="color: var(--slate-muted); font-size: 0.74rem;">
                            <?= e(tanggal_id($mt['created_at'])) ?>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1.5">
                                <?php if ($bisaPreview): ?>
                                    <a class="btn btn-sm btn-outline-info px-2.5 py-1" target="_blank"
                                       href="<?= e(url('/guru/materi/lihat/' . $mt['id'])) ?>">Lihat</a>
                                <?php endif; ?>
                                <a class="btn btn-sm btn-outline-primary px-2.5 py-1"
                                   href="<?= e(url('/guru/materi/form/' . $mt['id'])) ?>">Ubah</a>
                                <form method="post" action="<?= e(url('/guru/materi/hapus')) ?>"
                                      data-konfirmasi="Hapus materi ini beserta berkas fisiknya?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($mt['id']) ?>">
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
