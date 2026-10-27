<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 mb-1 text-white fw-bold">Perpustakaan Digital SMAN 10 Garut</h1>
        <p class="text-white-50 small mb-0">Koleksi e-book, buku sekolah elektronik (BSE), modul ajar, dan literatur referensi</p>
    </div>
    <?php if (in_array($peran, ['admin', 'guru'], true)): ?>
        <a class="btn btn-primary d-inline-flex align-items-center gap-2" href="<?= e(url('/perpustakaan/form')) ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tambah Koleksi Buku</span>
        </a>
    <?php endif; ?>
</div>

<!-- Form Pencarian & Filter -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <form method="get" action="<?= e(url('/perpustakaan')) ?>" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" class="form-control" name="cari" value="<?= e($filter['cari'] ?? '') ?>"
                       placeholder="Cari judul buku atau penulis...">
            </div>
            <div class="col-md-3">
                <select name="mapel" class="form-select">
                    <option value="">Semua Mata Pelajaran</option>
                    <?php foreach ($mapel as $m): ?>
                        <option value="<?= e($m['id']) ?>" <?= ((int) ($filter['mapel'] ?? 0) === (int) $m['id']) ? 'selected' : '' ?>>
                            <?= e($m['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="tingkat" class="form-select">
                    <option value="">Semua Tingkat</option>
                    <option value="X" <?= ($filter['tingkat'] ?? '') === 'X' ? 'selected' : '' ?>>Kelas X</option>
                    <option value="XI" <?= ($filter['tingkat'] ?? '') === 'XI' ? 'selected' : '' ?>>Kelas XI</option>
                    <option value="XII" <?= ($filter['tingkat'] ?? '') === 'XII' ? 'selected' : '' ?>>Kelas XII</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <?php if (($filter['cari'] ?? '') !== '' || ($filter['mapel'] ?? null) !== null || ($filter['tingkat'] ?? '') !== ''): ?>
                    <a href="<?= e(url('/perpustakaan')) ?>" class="btn btn-outline-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Kartu Buku -->
<div class="row g-4">
    <?php if ($data === []): ?>
        <div class="col-12">
            <div class="card shadow-sm border-0 text-center py-5">
                <div class="text-body-secondary">
                    <div class="fs-1 mb-2">📖</div>
                    <p class="mb-0">Tidak ada buku atau modul yang sesuai dengan kriteria pencarian Anda.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($data as $b): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 d-flex flex-column overflow-hidden transition-hover">
                <!-- Cover Buku -->
                <div class="bg-dark text-center py-3 position-relative" style="min-height: 180px; background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);">
                    <?php if (($b['cover'] ?? '') !== ''): ?>
                        <img src="<?= e(url('/perpustakaan/cover/' . $b['id'])) ?>" alt="<?= e($b['judul']) ?>"
                             style="max-height: 160px; max-width: 85%; object-fit: contain; box-shadow: 0 4px 15px rgba(0,0,0,0.5); border-radius: 4px;">
                    <?php else: ?>
                        <div class="d-flex flex-column justify-content-center align-items-center h-100 py-4">
                            <span class="fs-1">📚</span>
                            <span class="badge bg-secondary-subtle text-white-50 mt-2">Buku Digital</span>
                        </div>
                    <?php endif; ?>

                    <span class="position-absolute top-0 end-0 m-2 badge <?= $b['kategori'] === 'wajib' ? 'bg-danger' : 'bg-primary' ?>">
                        <?= $b['kategori'] === 'wajib' ? 'Buku Wajib' : 'Pendukung' ?>
                    </span>
                </div>

                <div class="card-body p-3 d-flex flex-column flex-grow-1">
                    <div class="mb-1">
                        <span class="badge text-bg-secondary small" style="font-size: 0.7rem;">
                            <?= e($b['mapel_nama'] ?? 'Umum') ?>
                        </span>
                        <span class="badge text-bg-dark border small" style="font-size: 0.7rem;">
                            Kelas <?= e($b['tingkat'] === 'semua' ? 'Semua' : $b['tingkat']) ?>
                        </span>
                    </div>

                    <h6 class="text-white fw-bold mb-1 line-clamp-2" title="<?= e($b['judul']) ?>">
                        <?= e($b['judul']) ?>
                    </h6>

                    <?php if (($b['penulis'] ?? '') !== ''): ?>
                        <div class="small text-white-50 mb-2">Penulis: <?= e($b['penulis']) ?></div>
                    <?php endif; ?>

                    <?php if (($b['deskripsi'] ?? '') !== ''): ?>
                        <p class="small text-white-50 mb-3 flex-grow-1" style="font-size: 0.8rem;">
                            <?= e(mb_strimwidth((string) $b['deskripsi'], 0, 80, '...')) ?>
                        </p>
                    <?php else: ?>
                        <div class="flex-grow-1"></div>
                    <?php endif; ?>

                    <div class="pt-2 border-top border-secondary-subtle d-flex flex-column gap-2 mt-auto">
                        <div class="d-flex gap-1">
                            <a href="<?= e(url('/perpustakaan/baca/' . $b['id'])) ?>" target="_blank"
                               class="btn btn-sm btn-primary flex-grow-1 d-inline-flex justify-content-center align-items-center gap-1">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span>Baca</span>
                            </a>
                            <a href="<?= e(url('/perpustakaan/unduh/' . $b['id'])) ?>"
                               class="btn btn-sm btn-outline-secondary" title="Unduh File">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            </a>
                        </div>

                        <?php if ($peran === 'admin' || (int) ($b['uploader_id'] ?? 0) === (int) \App\Core\Auth::id()): ?>
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="<?= e(url('/perpustakaan/form/' . $b['id'])) ?>" class="btn btn-xs btn-outline-warning py-0 px-2" style="font-size: 0.75rem;">Ubah</a>
                                <form method="post" action="<?= e(url('/perpustakaan/hapus')) ?>" data-konfirmasi="Hapus buku ini dari perpustakaan?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($b['id']) ?>">
                                    <button class="btn btn-xs btn-outline-danger py-0 px-2" style="font-size: 0.75rem;">Hapus</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
