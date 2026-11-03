<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Pusat Literasi Digital</span>
            <span>&bull;</span>
            <span>E-Perpustakaan</span>
        </div>
        <h1 class="page-title">Perpustakaan Digital SMAN 10 Garut</h1>
        <p class="page-subtitle">Koleksi buku sekolah elektronik (BSE), modul ajar digital, buku pengayaan, dan literatur ilmiah</p>
    </div>
    <?php if (in_array($peran, ['admin', 'guru'], true)): ?>
        <a class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm" href="<?= e(url('/perpustakaan/form')) ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tambah Koleksi Buku</span>
        </a>
    <?php endif; ?>
</div>

<!-- Search & Filtering Box -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <form method="get" action="<?= e(url('/perpustakaan')) ?>" class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label fw-bold">Pencarian Judul / Penulis</label>
                <input type="text" class="form-control py-2" name="cari" value="<?= e($filter['cari'] ?? '') ?>"
                       placeholder="Ketik judul buku atau nama penulis...">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label fw-bold">Mata Pelajaran</label>
                <select name="mapel" class="form-select py-2">
                    <option value="">Semua Mata Pelajaran</option>
                    <?php foreach ($mapel as $m): ?>
                        <option value="<?= e($m['id']) ?>" <?= ((int) ($filter['mapel'] ?? 0) === (int) $m['id']) ? 'selected' : '' ?>>
                            <?= e($m['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label fw-bold">Tingkat Kelas</label>
                <select name="tingkat" class="form-select py-2">
                    <option value="">Semua Tingkat</option>
                    <option value="X" <?= ($filter['tingkat'] ?? '') === 'X' ? 'selected' : '' ?>>Kelas X</option>
                    <option value="XI" <?= ($filter['tingkat'] ?? '') === 'XI' ? 'selected' : '' ?>>Kelas XI</option>
                    <option value="XII" <?= ($filter['tingkat'] ?? '') === 'XII' ? 'selected' : '' ?>>Kelas XII</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">Filter</button>
                <?php if (($filter['cari'] ?? '') !== '' || ($filter['mapel'] ?? null) !== null || ($filter['tingkat'] ?? '') !== ''): ?>
                    <a href="<?= e(url('/perpustakaan')) ?>" class="btn btn-outline-secondary px-3 py-2">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Kartu Buku -->
<div class="row g-4">
    <?php if ($data === []): ?>
        <div class="col-12">
            <div class="empty-state-box">
                <div class="empty-state-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                    </svg>
                </div>
                <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Koleksi Tidak Ditemukan</h3>
                <p class="small text-muted mb-0">Tidak ada buku atau modul yang sesuai dengan kriteria filter pencarian Anda.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($data as $b): ?>
        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <div class="card h-100 shadow-sm border-0 d-flex flex-column overflow-hidden">
                <!-- Cover Buku -->
                <div class="text-center py-4 position-relative d-flex align-items-center justify-content-center" 
                     style="min-height: 200px; background: linear-gradient(135deg, rgba(241, 245, 249, 0.9) 0%, rgba(226, 232, 240, 0.95) 100%); border-bottom: 1px solid rgba(226, 232, 240, 0.9);">
                    
                    <?php if (($b['cover'] ?? '') !== ''): ?>
                        <img src="<?= e(url('/perpustakaan/cover/' . $b['id'])) ?>" alt="<?= e($b['judul']) ?>"
                             style="max-height: 170px; max-width: 82%; object-fit: contain; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.15); border-radius: 6px;">
                    <?php else: ?>
                        <div class="d-flex flex-column justify-content-center align-items-center h-100 py-3">
                            <div class="squircle-icon squircle-icon-blue mb-2" style="width: 52px; height: 52px; border-radius: 14px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                </svg>
                            </div>
                            <span class="badge px-2 py-1" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-secondary); font-size: 0.72rem;">Buku Digital</span>
                        </div>
                    <?php endif; ?>

                    <span class="position-absolute top-0 end-0 m-2.5 badge <?= $b['kategori'] === 'wajib' ? 'bg-danger text-white' : 'bg-primary text-white' ?> px-2 py-1 shadow-sm" style="font-size: 0.68rem; border-radius: 6px;">
                        <?= $b['kategori'] === 'wajib' ? 'Buku Paket Wajib' : 'Referensi Pendukung' ?>
                    </span>
                </div>

                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                    <div class="mb-2 d-flex flex-wrap gap-1.5 align-items-center">
                        <span class="badge px-2 py-0.5 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); font-size: 0.7rem; border-radius: 6px;">
                            <?= e($b['mapel_nama'] ?? 'Umum') ?>
                        </span>
                        <span class="badge px-2 py-0.5 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); font-size: 0.7rem; border-radius: 6px;">
                            Kelas <?= e($b['tingkat'] === 'semua' ? 'Semua Jenjang' : $b['tingkat']) ?>
                        </span>
                    </div>

                    <h2 class="h6 fw-bold mb-1 line-clamp-2" style="color: var(--slate-primary); font-size: 0.94rem; line-height: 1.4;" title="<?= e($b['judul']) ?>">
                        <?= e($b['judul']) ?>
                    </h2>

                    <?php if (($b['penulis'] ?? '') !== ''): ?>
                        <div class="small fw-semibold mb-2" style="color: var(--slate-tertiary); font-size: 0.76rem;">Penulis: <?= e($b['penulis']) ?></div>
                    <?php endif; ?>

                    <?php if (($b['deskripsi'] ?? '') !== ''): ?>
                        <p class="small flex-grow-1 mb-3" style="color: var(--slate-secondary); font-size: 0.8rem; line-height: 1.5;">
                            <?= e(mb_strimwidth((string) $b['deskripsi'], 0, 75, '...')) ?>
                        </p>
                    <?php else: ?>
                        <div class="flex-grow-1"></div>
                    <?php endif; ?>

                    <div class="pt-3 border-top d-flex flex-column gap-2 mt-auto" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <div class="d-flex gap-1.5">
                            <a href="<?= e(url('/perpustakaan/baca/' . $b['id'])) ?>" target="_blank"
                               class="btn btn-sm btn-primary flex-grow-1 d-inline-flex justify-content-center align-items-center gap-1.5 py-1.5 fw-bold shadow-sm">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span>Baca Online</span>
                            </a>
                            <a href="<?= e(url('/perpustakaan/unduh/' . $b['id'])) ?>"
                               class="btn btn-sm btn-outline-secondary px-2.5 py-1.5" title="Unduh Berkas Buku">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            </a>
                        </div>

                        <?php if ($peran === 'admin' || (int) ($b['uploader_id'] ?? 0) === (int) \App\Core\Auth::id()): ?>
                            <div class="d-flex gap-1 justify-content-end pt-1">
                                <a href="<?= e(url('/perpustakaan/form/' . $b['id'])) ?>" class="btn btn-sm btn-outline-primary py-0.5 px-2" style="font-size: 0.72rem;">Ubah</a>
                                <form method="post" action="<?= e(url('/perpustakaan/hapus')) ?>" data-konfirmasi="Hapus buku ini dari perpustakaan digital?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($b['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger py-0.5 px-2" style="font-size: 0.72rem;">Hapus</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
