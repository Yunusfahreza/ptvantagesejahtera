<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Manajemen Pengajaran</span>
            <span>&bull;</span>
            <span>Rombel & Mapel</span>
        </div>
        <h1 class="page-title">Kelas yang Saya Ampu</h1>
        <p class="page-subtitle">Daftar rombongan belajar dan penugasan kurikulum yang diampu pada tahun ajaran aktif</p>
    </div>

    <!-- Live filter -->
    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari kelas atau mapel..." data-instant-filter="#daftarKelasGrid">
    </div>
</div>

<?php if ($data === []): ?>
    <div class="empty-state-box">
        <div class="empty-state-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Penugasan Kelas</h3>
        <p class="small text-muted mb-0">Anda belum ditetapkan sebagai guru pengampu untuk kelas mana pun. Silakan hubungi operator admin kurikulum.</p>
    </div>
<?php else: ?>
    <div class="row g-3" id="daftarKelasGrid">
        <?php foreach ($data as $p): ?>
            <div class="col-12 col-sm-6 col-lg-4 filterable-item">
                <div class="card h-100 shadow-sm border-0 d-flex flex-column justify-content-between">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border: 1px solid rgba(15, 23, 42, 0.1); border-radius: 8px;">
                                <?= e($p['mapel_kode']) ?>
                            </span>

                            <?php if ((int) $p['tahun_aktif'] === 1): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                    Semester Aktif
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle px-2.5 py-1">
                                    <?= e($p['tahun_nama']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <h2 class="h5 fw-bold mb-1" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                            Kelas <?= e($p['kelas_nama']) ?>
                        </h2>

                        <p class="small fw-semibold mb-3" style="color: var(--slate-secondary);">
                            <?= e($p['mapel_nama']) ?>
                        </p>
                    </div>

                    <div class="card-footer bg-transparent p-3 pt-0 border-0 d-flex flex-wrap gap-1.5">
                        <a class="btn btn-sm btn-outline-primary px-2.5 py-1" href="<?= e(url('/guru/materi')) ?>">Materi</a>
                        <a class="btn btn-sm btn-outline-primary px-2.5 py-1" href="<?= e(url('/guru/tugas')) ?>">Tugas</a>
                        <a class="btn btn-sm btn-outline-primary px-2.5 py-1" href="<?= e(url('/guru/kuis')) ?>">Kuis</a>
                        <a class="btn btn-sm btn-outline-secondary px-2.5 py-1 ms-auto"
                           href="<?= e(url('/guru/rekap/kelas/' . $p['id'])) ?>">Rekap Nilai</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
