<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Laporan & Transkrip</span>
            <span>&bull;</span>
            <span>Rekapitulasi Nilai</span>
        </div>
        <h1 class="page-title">Buku Rekap Nilai Siswa</h1>
        <p class="page-subtitle">Rekapitulasi perolehan nilai tugas, kuis, dan rata-rata kelas per rombel yang Anda ampu</p>
    </div>

    <!-- Live filter -->
    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari rombel atau mapel..." data-instant-filter="#daftarRekapGrid">
    </div>
</div>

<?php if ($data === []): ?>
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun. Silakan hubungi admin sekolah.
    </div>
<?php endif; ?>

<div class="row g-3" id="daftarRekapGrid">
    <?php foreach ($data as $p): ?>
        <div class="col-12 col-sm-6 col-lg-4 filterable-item">
            <div class="card h-100 shadow-sm border-0 d-flex flex-column justify-content-between">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 8px;">
                            <?= e($p['mapel_kode']) ?>
                        </span>
                        <?php if ((int) $p['tahun_aktif'] === 1): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.68rem;">Semester Aktif</span>
                        <?php endif; ?>
                    </div>

                    <h2 class="h5 fw-bold mb-1" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                        Kelas <?= e($p['kelas_nama']) ?>
                    </h2>

                    <p class="small fw-semibold mb-2" style="color: var(--slate-secondary);">
                        <?= e($p['mapel_nama']) ?>
                    </p>

                    <div class="small" style="color: var(--slate-muted); font-size: 0.76rem;">
                        Tahun: <?= e($p['tahun_nama']) ?>
                    </div>
                </div>

                <div class="card-footer bg-transparent p-3 pt-0 border-0 d-flex gap-2">
                    <a class="btn btn-sm btn-primary px-3 py-1.5 fw-semibold shadow-sm flex-grow-1"
                       href="<?= e(url('/guru/rekap/kelas/' . $p['id'])) ?>">Buka Lembar Rekap</a>
                    <a class="btn btn-sm btn-outline-secondary px-3 py-1.5 d-inline-flex align-items-center gap-1"
                       href="<?= e(url('/guru/rekap/csv/' . $p['id'])) ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        <span>CSV</span>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
