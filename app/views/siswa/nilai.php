<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <div class="page-breadcrumb">
            <span>Prestasi & Laporan</span>
            <span>&bull;</span>
            <span>Semester Ganjil 2026/2027</span>
        </div>
        <h1 class="page-title">Buku Nilai & Rangkuman Analitik</h1>
        <p class="page-subtitle">Transkrip menyeluruh, rekapitulasi rata-rata, dan visualisasi performa belajar interaktif</p>
    </div>

    <!-- Quick search -->
    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari nama mapel atau asesmen..." data-instant-filter="#daftarNilaiContainer">
    </div>
</div>

<?php if (empty($kelompok)): ?>
    <div class="empty-state-box">
        <div class="empty-state-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
        </div>
        <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Nilai Terbit</h3>
        <p class="small text-muted mb-0">Nilai akan otomatis muncul di sini setelah guru menilai tugas atau setelah Anda menyelesaikan kuis.</p>
    </div>
<?php else: ?>

    <!-- ==========================================================================
         1. BENTO STATISTIK RANGKUMAN NILAI SISWA (4 KARTU PERSPEKTIF)
         ========================================================================== -->
    <div class="row g-3 mb-4">
        <!-- BENTO 1: RERATA KUMULATIF & PREDIKAT (HIGHLIGHT DARK COCKPIT CARD) -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 p-4 text-white d-flex flex-column justify-content-between"
                 style="border-radius: 26px; background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%); box-shadow: 0 14px 35px rgba(15, 23, 42, 0.16); border: 1px solid rgba(255, 255, 255, 0.12);">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-uppercase" style="letter-spacing: 0.08em; font-size: 0.7rem; color: #94a3b8;">
                            Rerata Kumulatif
                        </span>
                        <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 0.72rem; font-weight: 700; border: 1px solid rgba(16, 185, 129, 0.3);">
                            Predikat <?= e($rangkuman['predikat_huruf'] ?? 'A') ?>
                        </span>
                    </div>

                    <div class="d-flex align-items-baseline gap-2 mb-1">
                        <span class="display-5 fw-bold mb-0" style="letter-spacing: -0.04em; color: #ffffff;">
                            <?= e(number_format((float) ($rangkuman['rerata_keseluruhan'] ?? 0), 1)) ?>
                        </span>
                        <span class="text-white-50" style="font-size: 0.95rem;">/ 100</span>
                    </div>

                    <div class="small fw-medium" style="color: #cbd5e1; font-size: 0.8rem;">
                        <?= e($rangkuman['predikat_label'] ?? 'Sangat Memuaskan') ?>
                    </div>
                </div>

                <div class="pt-3 mt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="d-flex align-items-center justify-content-between small" style="font-size: 0.74rem;">
                        <span style="color: #94a3b8;">Ketuntasan KKM (75)</span>
                        <strong style="color: #34d399;"><?= e($rangkuman['persen_tuntas'] ?? 100) ?>% Tuntas</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- BENTO 2: TOTAL ASESMEN & KOMPARASI TUGAS VS KUIS -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 p-4 d-flex flex-column justify-content-between"
                 style="border-radius: 26px; background: rgba(255, 255, 255, 0.88); backdrop-filter: blur(20px); border: 1.5px solid rgba(255, 255, 255, 0.95); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-uppercase" style="letter-spacing: 0.08em; font-size: 0.7rem; color: var(--slate-tertiary);">
                            Total Asesmen
                        </span>
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 28px; height: 28px; background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire);">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M9 11l3 3L22 4"/>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                        </span>
                    </div>

                    <div class="display-6 fw-bold mb-1" style="color: var(--slate-primary); letter-spacing: -0.03em;">
                        <?= (int) ($rangkuman['total_asesmen'] ?? 0) ?>
                        <span class="fs-6 fw-semibold text-muted">Evaluasi</span>
                    </div>

                    <div class="small" style="color: var(--slate-secondary); font-size: 0.78rem;">
                        Terkumpul dan dinilai oleh dewan guru
                    </div>
                </div>

                <div class="pt-3 mt-3 d-flex flex-column gap-1.5" style="border-top: 1.5px solid rgba(226, 232, 240, 0.8);">
                    <div class="d-flex justify-content-between align-items-center small" style="font-size: 0.74rem;">
                        <span style="color: var(--slate-secondary);">&bull; <?= (int) ($rangkuman['total_tugas'] ?? 0) ?> Tugas Selesai</span>
                        <strong style="color: var(--accent-sapphire);"><?= e(number_format((float) ($rangkuman['rerata_tugas'] ?? 0), 1)) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small" style="font-size: 0.74rem;">
                        <span style="color: var(--slate-secondary);">&bull; <?= (int) ($rangkuman['total_kuis'] ?? 0) ?> Kuis Selesai</span>
                        <strong style="color: #059669;"><?= e(number_format((float) ($rangkuman['rerata_kuis'] ?? 0), 1)) ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- BENTO 3: MAPEL TERTINGGI & PERFORMA PUNCAK -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 p-4 d-flex flex-column justify-content-between"
                 style="border-radius: 26px; background: rgba(255, 255, 255, 0.88); backdrop-filter: blur(20px); border: 1.5px solid rgba(255, 255, 255, 0.95); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-uppercase" style="letter-spacing: 0.08em; font-size: 0.7rem; color: var(--slate-tertiary);">
                            Mapel Unggulan
                        </span>
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 28px; height: 28px; background: rgba(245, 158, 11, 0.1); color: #d97706;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                        </span>
                    </div>

                    <?php if (!empty($rangkuman['mapel_tertinggi'])): ?>
                        <div class="fw-bold text-truncate mb-1" style="color: var(--slate-primary); font-size: 1.12rem;">
                            <?= e($rangkuman['mapel_tertinggi']['nama']) ?>
                        </div>
                        <div class="small fw-semibold" style="color: #d97706; font-size: 0.8rem;">
                            Rerata: <?= e(number_format((float) $rangkuman['mapel_tertinggi']['rata'], 1)) ?> &bull; Kode: <?= e($rangkuman['mapel_tertinggi']['kode']) ?>
                        </div>
                    <?php else: ?>
                        <div class="fw-bold text-muted mb-1">&mdash;</div>
                    <?php endif; ?>
                </div>

                <div class="pt-3 mt-3" style="border-top: 1.5px solid rgba(226, 232, 240, 0.8);">
                    <div class="d-flex justify-content-between align-items-center small" style="font-size: 0.74rem;">
                        <span style="color: var(--slate-secondary);">Rentang Perolehan</span>
                        <strong style="color: var(--slate-primary);">
                            <?= e($rangkuman['nilai_terendah'] ?? 0) ?> &ndash; <?= e($rangkuman['nilai_tertinggi'] ?? 100) ?>
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- BENTO 4: DISTRIBUSI PREDIKAT SKOR -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 border-0 p-4 d-flex flex-column justify-content-between"
                 style="border-radius: 26px; background: rgba(255, 255, 255, 0.88); backdrop-filter: blur(20px); border: 1.5px solid rgba(255, 255, 255, 0.95); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-uppercase" style="letter-spacing: 0.08em; font-size: 0.7rem; color: var(--slate-tertiary);">
                            Distribusi Nilai
                        </span>
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 28px; height: 28px; background: rgba(16, 185, 129, 0.1); color: #059669;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                        </span>
                    </div>

                    <!-- Mini Segmented Bar Distribution -->
                    <?php
                    $distA = (int) ($rangkuman['distribusi']['A'] ?? 0);
                    $distB = (int) ($rangkuman['distribusi']['B'] ?? 0);
                    $distC = (int) ($rangkuman['distribusi']['C'] ?? 0);
                    $distD = (int) ($rangkuman['distribusi']['D'] ?? 0);
                    $tot = max(1, $distA + $distB + $distC + $distD);
                    $pctA = round(($distA / $tot) * 100);
                    $pctB = round(($distB / $tot) * 100);
                    $pctC = round(($distC / $tot) * 100);
                    $pctD = 100 - ($pctA + $pctB + $pctC);
                    ?>

                    <div class="d-flex rounded-pill overflow-hidden my-2" style="height: 10px; background: #e2e8f0;">
                        <?php if ($pctA > 0): ?><div style="width: <?= $pctA ?>%; background: #0f172a;" title="A: <?= $distA ?>"></div><?php endif; ?>
                        <?php if ($pctB > 0): ?><div style="width: <?= $pctB ?>%; background: #2563eb;" title="B: <?= $distB ?>"></div><?php endif; ?>
                        <?php if ($pctC > 0): ?><div style="width: <?= $pctC ?>%; background: #f59e0b;" title="C: <?= $distC ?>"></div><?php endif; ?>
                        <?php if ($pctD > 0): ?><div style="width: <?= $pctD ?>%; background: #ef4444;" title="D: <?= $distD ?>"></div><?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between text-center pt-1" style="font-size: 0.72rem;">
                        <div>
                            <span class="d-block fw-bold" style="color: #0f172a;"><?= $distA ?></span>
                            <span class="text-muted" style="font-size: 0.68rem;">A (&ge;90)</span>
                        </div>
                        <div>
                            <span class="d-block fw-bold" style="color: #2563eb;"><?= $distB ?></span>
                            <span class="text-muted" style="font-size: 0.68rem;">B (80-89)</span>
                        </div>
                        <div>
                            <span class="d-block fw-bold" style="color: #d97706;"><?= $distC ?></span>
                            <span class="text-muted" style="font-size: 0.68rem;">C (75-79)</span>
                        </div>
                        <div>
                            <span class="d-block fw-bold" style="color: #dc2626;"><?= $distD ?></span>
                            <span class="text-muted" style="font-size: 0.68rem;">D (&lt;75)</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 mt-3" style="border-top: 1.5px solid rgba(226, 232, 240, 0.8);">
                    <div class="d-flex justify-content-between align-items-center small" style="font-size: 0.74rem;">
                        <span style="color: var(--slate-secondary);">Mayoritas Nilai</span>
                        <strong style="color: #0f172a;"><?= $distA >= $distB ? 'Grade A (' . $pctA . '%)' : 'Grade B (' . $pctB . '%)' ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         2. FLOATING GLASS ISLAND: GRAFIK INTERAKTIF RANGKUMAN NILAI
         ========================================================================== -->
    <div class="card border-0 mb-5 p-4"
         style="border-radius: 28px; background: rgba(255, 255, 255, 0.88); backdrop-filter: blur(25px); border: 1.5px solid rgba(255, 255, 255, 0.95); box-shadow: 0 12px 35px rgba(15, 23, 42, 0.05);">
        
        <!-- Header & Segmented Pill Controls -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 30px; height: 30px; background: #0f172a; color: #ffffff;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </span>
                    <h3 class="h6 fw-bold mb-0" style="color: var(--slate-primary); font-size: 1.05rem;">
                        Visualisasi & Analisis Grafik Interaktif
                    </h3>
                </div>
                <p class="small text-muted mb-0" style="font-size: 0.78rem;">
                    Eksplorasi performa belajar per mata pelajaran, tren progres semester berjalan, dan perbandingan tugas vs kuis
                </p>
            </div>

            <!-- Segmented Pill Buttons (Matching macOS / VisionOS Reference Style) -->
            <div class="d-inline-flex p-1 rounded-pill" style="background: rgba(15, 23, 42, 0.06); border: 1px solid rgba(15, 23, 42, 0.08);">
                <button type="button" class="btn btn-sm btn-chart-tab active" data-chart-mode="mapel" onclick="switchChartMode('mapel')">
                    Performa Mapel
                </button>
                <button type="button" class="btn btn-sm btn-chart-tab" data-chart-mode="linimasa" onclick="switchChartMode('linimasa')">
                    Tren Linimasa
                </button>
                <button type="button" class="btn btn-sm btn-chart-tab" data-chart-mode="komparasi" onclick="switchChartMode('komparasi')">
                    Tugas vs Kuis
                </button>
            </div>
        </div>

        <!-- Canvas Container with Interactive Chart -->
        <div class="position-relative w-100" style="min-height: 290px;">
            <canvas id="interactiveNilaiChart" style="max-height: 310px;"></canvas>
        </div>

        <!-- Footer Meta & Interactive Insights Bar -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3 mt-3"
             style="border-top: 1.5px solid rgba(226, 232, 240, 0.8); font-size: 0.76rem; color: var(--slate-secondary);">
            <div class="d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center gap-1.5">
                    <span class="rounded-circle" style="width: 8px; height: 8px; background: #0f172a;"></span>
                    <span>Rata-Rata Aktual</span>
                </span>
                <span class="d-inline-flex align-items-center gap-1.5">
                    <span class="rounded-pill" style="width: 14px; height: 2px; background: #ef4444; border-top: 1px dashed #ef4444;"></span>
                    <span>Batas KKM (75.0)</span>
                </span>
            </div>
            <div class="text-muted">
                *Klik atau sorot titik grafik untuk melihat rincian asesmen dan skor terperinci
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         3. DAFTAR RINCIAN NILAI PER MATA PELAJARAN (TABEL LENGKAP)
         ========================================================================== -->
    <div class="d-flex align-items-center justify-content-between mb-3 px-1">
        <h3 class="h6 fw-bold mb-0" style="color: var(--slate-primary); font-size: 1rem;">
            Rincian Asesmen Per Mata Pelajaran
        </h3>
        <span class="small text-muted" style="font-size: 0.78rem;">
            <?= count($kelompok) ?> Mata Pelajaran Aktif
        </span>
    </div>

    <div id="daftarNilaiContainer" class="d-flex flex-column gap-4">
        <?php foreach ($kelompok as $mapel => $isi): ?>
            <?php
            $rata = (float) ($isi['rata'] ?? 0);
            $badgeRata = match (true) {
                $rata >= 85 => 'squircle-icon-green',
                $rata >= 75 => 'squircle-icon-blue',
                default     => 'squircle-icon-amber',
            };
            ?>
            <div class="card shadow-sm border-0 filterable-item"
                 style="border-radius: 22px; background: rgba(255, 255, 255, 0.88); backdrop-filter: blur(18px); border: 1.5px solid rgba(255, 255, 255, 0.95); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04); overflow: hidden;">
                <div class="card-header py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2"
                     style="background: rgba(248, 250, 252, 0.65); border-bottom: 1.5px solid rgba(226, 232, 240, 0.7);">
                    <div class="d-flex align-items-center gap-2.5">
                        <span class="badge px-2.5 py-1.5 fw-bold" style="background: #0f172a; color: #ffffff; border-radius: 8px; font-size: 0.72rem; letter-spacing: 0.04em;">
                            <?= e($isi['kode']) ?>
                        </span>
                        <h2 class="h6 mb-0 fw-bold" style="color: var(--slate-primary);">
                            <?= e($mapel) ?>
                        </h2>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-semibold" style="color: var(--slate-tertiary); font-size: 0.78rem;">Rata-rata:</span>
                        <span class="badge px-3 py-1.5 fs-6 fw-bold" style="background: rgba(15, 23, 42, 0.08); color: #0f172a; border: 1px solid rgba(15, 23, 42, 0.15); border-radius: 10px;">
                            <?= e(nilai_tampil($isi['rata'])) ?>
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: rgba(241, 245, 249, 0.4); font-size: 0.76rem; color: var(--slate-tertiary); text-transform: uppercase; letter-spacing: 0.04em;">
                            <tr>
                                <th style="width: 120px;" class="ps-4 py-3">Jenis</th>
                                <th class="py-3">Judul Asesmen</th>
                                <th class="py-3">Status Pengerjaan</th>
                                <th class="text-center py-3" style="width: 120px;">Nilai</th>
                                <th class="pe-4 py-3">Catatan Guru</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($isi['item'] as $it): ?>
                                <tr>
                                    <td class="ps-4">
                                        <?php if ($it['jenis'] === 'Tugas'): ?>
                                            <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(37, 99, 235, 0.1); color: var(--accent-sapphire); font-weight: 700; font-size: 0.72rem; border: 1px solid rgba(37, 99, 235, 0.2);">
                                                Tugas
                                            </span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-weight: 700; font-size: 0.72rem; border: 1px solid rgba(16, 185, 129, 0.2);">
                                                Kuis
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.88rem;"><?= e($it['judul']) ?></div>
                                        <div class="small" style="color: var(--slate-muted); font-size: 0.72rem;"><?= e(tanggal_id($it['waktu'])) ?></div>
                                    </td>
                                    <td>
                                        <?php if ($it['status'] === 'terlambat'): ?>
                                            <span class="badge rounded-pill px-2.5 py-1 bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 0.7rem; font-weight: 600;">Terlambat</span>
                                        <?php elseif ($it['status'] === 'tepat_waktu'): ?>
                                            <span class="badge rounded-pill px-2.5 py-1 bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem; font-weight: 600;">Tepat Waktu</span>
                                        <?php elseif ($it['status'] === 'belum_selesai'): ?>
                                            <span class="badge rounded-pill px-2.5 py-1 bg-secondary-subtle text-muted border border-secondary-subtle" style="font-size: 0.7rem; font-weight: 600;">Belum Selesai</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill px-2.5 py-1 bg-success-subtle text-success border border-success-subtle" style="font-size: 0.7rem; font-weight: 600;">Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($it['nilai'] !== null): ?>
                                            <span class="fw-bold px-3 py-1 rounded-pill"
                                                  style="background: #0f172a; color: #ffffff; font-size: 0.88rem; letter-spacing: -0.01em;">
                                                <?= e(nilai_tampil($it['nilai'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1" style="font-size: 0.75rem;">
                                                Menunggu
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small pe-4" style="color: var(--slate-secondary); font-size: 0.78rem;">
                                        <?= $it['komentar'] === null ? '<span class="text-muted">&mdash;</span>' : nl2br(e($it['komentar'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ==========================================================================
         4. SCRIPT CHART.JS & INISIALISASI GRAFIK INTERAKTIF
         ========================================================================== -->
    <script src="<?= e(aset('js/chart.umd.min.js')) ?>"></script>
    <script>
    (function() {
        // Data Mentah dari Controller
        const rawMapel = <?= json_encode($rangkuman['grafik_mapel'] ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
        const rawLinimasa = <?= json_encode($rangkuman['linimasa'] ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

        const ctx = document.getElementById('interactiveNilaiChart');
        if (!ctx) return;

        let chartInstance = null;
        let currentMode = 'mapel';

        // Konfigurasi Global Font
        Chart.defaults.font.family = "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif";
        Chart.defaults.color = '#64748b';

        // Custom Tooltip Styling matching Dark Capsule VisionOS
        const customTooltip = {
            backgroundColor: 'rgba(15, 23, 42, 0.94)',
            titleColor: '#ffffff',
            bodyColor: '#e2e8f0',
            borderColor: 'rgba(255, 255, 255, 0.15)',
            borderWidth: 1,
            padding: 12,
            boxPadding: 6,
            cornerRadius: 12,
            titleFont: { weight: 'bold', size: 13 },
            bodyFont: { size: 12 },
            displayColors: false,
        };

        // Buat Chart Sesuai Mode
        function renderChart(mode) {
            if (chartInstance) {
                chartInstance.destroy();
            }

            if (mode === 'mapel') {
                // Mode 1: Performa Nilai Rerata Per Mapel (Bar Chart Modern dengan Garis KKM)
                chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: rawMapel.labels || [],
                        datasets: [
                            {
                                label: 'Rata-Rata Mapel',
                                data: rawMapel.rerata || [],
                                backgroundColor: (context) => {
                                    const val = context.raw;
                                    return val >= 90 ? 'rgba(15, 23, 42, 0.9)' :
                                           val >= 80 ? 'rgba(37, 99, 235, 0.85)' : 'rgba(245, 158, 11, 0.85)';
                                },
                                borderRadius: 10,
                                borderSkipped: false,
                                maxBarThickness: 38,
                            },
                            {
                                type: 'line',
                                label: 'Standar KKM (75.0)',
                                data: Array(rawMapel.labels.length).fill(75.0),
                                borderColor: '#ef4444',
                                borderDash: [6, 6],
                                borderWidth: 2,
                                pointRadius: 0,
                                fill: false,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 600, easing: 'easeOutQuart' },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'end',
                                labels: { boxWidth: 12, boxHeight: 12, borderRadius: 3, usePointStyle: true }
                            },
                            tooltip: {
                                ...customTooltip,
                                callbacks: {
                                    title: (items) => items[0].label,
                                    label: (item) => {
                                        if (item.datasetIndex === 1) return 'Standar KKM: 75.0';
                                        return `Rerata Nilai: ${item.raw.toFixed(1)} / 100`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                min: 50,
                                max: 100,
                                grid: { color: 'rgba(226, 232, 240, 0.6)' },
                                ticks: { stepSize: 10 }
                            },
                            x: {
                                grid: { display: false },
                                ticks: {
                                    callback: function(val, index) {
                                        const kode = rawMapel.kodes ? rawMapel.kodes[index] : null;
                                        return kode || this.getLabelForValue(val);
                                    }
                                }
                            }
                        }
                    }
                });
            } else if (mode === 'linimasa') {
                // Mode 2: Tren Progres Linimasa dari Awal Semester ke Sekarang (Smooth Spline Area)
                const labels = rawLinimasa.map(item => item.label);
                const scores = rawLinimasa.map(item => item.nilai);
                const runningAvgs = rawLinimasa.map(item => item.kumulatif);

                chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Rerata Kumulatif Berjalan',
                                data: runningAvgs,
                                borderColor: '#0f172a',
                                backgroundColor: 'rgba(15, 23, 42, 0.08)',
                                fill: true,
                                tension: 0.35,
                                borderWidth: 3,
                                pointBackgroundColor: '#0f172a',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 7,
                            },
                            {
                                label: 'Skor Per Asesmen',
                                data: scores,
                                borderColor: 'rgba(37, 99, 235, 0.45)',
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                tension: 0.25,
                                borderWidth: 1.5,
                                pointBackgroundColor: '#2563eb',
                                pointRadius: 3,
                                pointHoverRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 600, easing: 'easeOutQuart' },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'end',
                                labels: { boxWidth: 12, usePointStyle: true }
                            },
                            tooltip: {
                                ...customTooltip,
                                callbacks: {
                                    title: (items) => {
                                        const idx = items[0].dataIndex;
                                        const item = rawLinimasa[idx];
                                        return `[${item.jenis}] ${item.mapel}`;
                                    },
                                    beforeBody: (items) => {
                                        const idx = items[0].dataIndex;
                                        return rawLinimasa[idx].judul;
                                    },
                                    label: (item) => {
                                        return `${item.dataset.label}: ${item.raw.toFixed(1)}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                min: 60,
                                max: 100,
                                grid: { color: 'rgba(226, 232, 240, 0.6)' },
                                ticks: { stepSize: 10 }
                            },
                            x: {
                                grid: { display: false },
                                ticks: {
                                    maxTicksLimit: 10
                                }
                            }
                        }
                    }
                });
            } else if (mode === 'komparasi') {
                // Mode 3: Komparasi Rerata Tugas vs Rerata Kuis per Mapel
                chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: rawMapel.labels || [],
                        datasets: [
                            {
                                label: 'Rerata Tugas',
                                data: rawMapel.rerata_tugas || [],
                                backgroundColor: '#0f172a',
                                borderRadius: 8,
                                maxBarThickness: 24,
                            },
                            {
                                label: 'Rerata Kuis',
                                data: rawMapel.rerata_kuis || [],
                                backgroundColor: '#2563eb',
                                borderRadius: 8,
                                maxBarThickness: 24,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 600, easing: 'easeOutQuart' },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'end',
                                labels: { boxWidth: 12, boxHeight: 12, borderRadius: 3 }
                            },
                            tooltip: {
                                ...customTooltip,
                                callbacks: {
                                    label: (item) => `${item.dataset.label}: ${item.raw ? item.raw.toFixed(1) : '-'}`
                                }
                            }
                        },
                        scales: {
                            y: {
                                min: 50,
                                max: 100,
                                grid: { color: 'rgba(226, 232, 240, 0.6)' },
                                ticks: { stepSize: 10 }
                            },
                            x: {
                                grid: { display: false },
                                ticks: {
                                    callback: function(val, index) {
                                        const kode = rawMapel.kodes ? rawMapel.kodes[index] : null;
                                        return kode || this.getLabelForValue(val);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Terapkan switch mode tombol tab
        window.switchChartMode = function(mode) {
            currentMode = mode;
            document.querySelectorAll('.btn-chart-tab').forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-chart-mode') === mode);
            });
            renderChart(mode);
        };

        // Render perdana mode mapel
        renderChart('mapel');
    })();
    </script>

    <style>
    .btn-chart-tab {
        border: none;
        background: transparent;
        color: var(--slate-tertiary);
        font-weight: 700;
        font-size: 0.76rem;
        padding: 0.38rem 0.95rem;
        border-radius: 9999px;
        transition: all 0.2s ease;
    }
    .btn-chart-tab:hover {
        color: var(--slate-primary);
        background: rgba(255, 255, 255, 0.6);
    }
    .btn-chart-tab.active {
        background: #0f172a !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.18);
    }
    </style>
<?php endif; ?>
