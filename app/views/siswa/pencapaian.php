<?php
/**
 * Halaman Pencapaian & Papan Peringkat (Leaderboard) Siswa
 * Desain Pure Crystal Glassmorphism dengan ikon SVG bersih tanpa karakter rusak.
 */
?>
<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Motivasi & Gamifikasi</span>
            <span>&bull;</span>
            <span>Apresiasi Siswa</span>
        </div>
        <h1 class="page-title">Pencapaian & Papan Peringkat</h1>
        <p class="page-subtitle">Koleksi lencana prestasi belajar dan pantau performa akademik Anda di kelas</p>
    </div>

    <div class="d-flex align-items-center gap-2 p-2 px-3.5 rounded-pill" style="background: rgba(37, 99, 235, 0.08); border: 1.5px solid rgba(37, 99, 235, 0.25);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--accent-sapphire);">
            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/>
            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
        </svg>
        <span class="small fw-bold" style="color: var(--accent-sapphire);"><?= count($badgeSaya) ?> Lencana Diraih</span>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Koleksi Lencana Prestasi -->
    <div class="col-12 col-lg-5">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="squircle-icon squircle-icon-amber" style="width: 32px; height: 32px; border-radius: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                        </svg>
                    </div>
                    <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">Lencana Prestasi Saya</span>
                </div>
                <span class="badge bg-primary text-white px-2.5 py-1" style="font-size: 0.72rem; border-radius: 9999px;">
                    <?= count($badgeSaya) ?> Diraih
                </span>
            </div>

            <div class="card-body p-4">
                <?php if ($badgeSaya === []): ?>
                    <div class="empty-state-box p-4 text-center">
                        <div class="empty-state-icon mx-auto" style="width: 48px; height: 48px; margin-bottom: 0.75rem;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>
                            </svg>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Lencana Terbuka</h6>
                        <p class="small text-muted mb-0">
                            Kumpulkan tugas tepat waktu dan raih nilai tinggi di kuis untuk mengklaim lencana pertama Anda!
                        </p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($badgeSaya as $b): ?>
                            <div class="p-3 rounded-4 d-flex align-items-center gap-3" 
                                 style="background: rgba(255, 255, 255, 0.75); border: 1.5px solid rgba(226, 232, 240, 0.85); box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);">
                                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                     style="width: 44px; height: 44px; background: rgba(37, 99, 235, 0.1); color: var(--accent-sapphire);">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>
                                    </svg>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.95rem;"><?= e($b['label']) ?></div>
                                    <div class="small" style="color: var(--slate-secondary); font-size: 0.82rem;"><?= e($b['deskripsi']) ?></div>
                                    <div class="small mt-1 fw-semibold" style="color: var(--accent-sapphire); font-size: 0.74rem;">
                                        Diraih pada <?= e(tanggal_id($b['diperoleh_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Panduan Membuka Lencana -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h6 class="fw-bold text-uppercase tracking-wider mb-3" style="color: var(--slate-muted); font-size: 0.72rem;">Katalog Lencana Belajar</h6>
                <div class="d-flex flex-column gap-2.5 small" style="color: var(--slate-secondary);">
                    <div class="d-flex align-items-start gap-2.5 p-2.5 rounded-3" style="background: rgba(248, 250, 252, 0.8); border: 1px solid rgba(226, 232, 240, 0.8);">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle p-1" style="background: rgba(37, 99, 235, 0.1); color: var(--accent-sapphire); width: 28px; height: 28px; flex-shrink: 0;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </span>
                        <div><strong>Pengumpul Tepat Waktu:</strong> Kumpulkan minimal 3 tugas sebelum tenggat waktu.</div>
                    </div>
                    <div class="d-flex align-items-start gap-2.5 p-2.5 rounded-3" style="background: rgba(248, 250, 252, 0.8); border: 1px solid rgba(226, 232, 240, 0.8);">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle p-1" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-emerald); width: 28px; height: 28px; flex-shrink: 0;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                        </span>
                        <div><strong>Nilai Sempurna:</strong> Raih nilai sempurna 100 pada tugas atau kuis.</div>
                    </div>
                    <div class="d-flex align-items-start gap-2.5 p-2.5 rounded-3" style="background: rgba(248, 250, 252, 0.8); border: 1px solid rgba(226, 232, 240, 0.8);">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle p-1" style="background: rgba(245, 158, 11, 0.1); color: var(--accent-amber); width: 28px; height: 28px; flex-shrink: 0;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </span>
                        <div><strong>Rajin Latihan:</strong> Selesaikan 5 latihan kuis online tanpa terlewat.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Leaderboard Kelas -->
    <div class="col-12 col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="squircle-icon squircle-icon-green" style="width: 32px; height: 32px; border-radius: 8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/>
                            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
                            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
                            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                        </svg>
                    </div>
                    <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">Papan Peringkat Kelas (Leaderboard)</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 75px;" class="text-center">Rank</th>
                            <th>Nama Siswa</th>
                            <th class="text-center" style="width: 110px;">Lencana</th>
                            <th class="text-end" style="width: 130px;">Rerata Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($leaderboard === []): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color: var(--slate-muted);">
                                    Belum ada data nilai yang terakumulasi di kelas ini.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php $rank = 1; foreach ($leaderboard as $m): ?>
                            <?php
                            $isSaya = (int) $m['id'] === (int) $siswaId;
                            $medalPill = match ($rank) {
                                1 => '<span class="badge rounded-pill" style="background: rgba(234, 179, 8, 0.15); color: #b45309; border: 1.5px solid rgba(234, 179, 8, 0.4); font-size: 0.76rem; font-weight: 700;">Juara 1</span>',
                                2 => '<span class="badge rounded-pill" style="background: rgba(148, 163, 184, 0.2); color: #334155; border: 1.5px solid rgba(148, 163, 184, 0.4); font-size: 0.76rem; font-weight: 700;">Juara 2</span>',
                                3 => '<span class="badge rounded-pill" style="background: rgba(180, 83, 9, 0.15); color: #9a3412; border: 1.5px solid rgba(180, 83, 9, 0.3); font-size: 0.76rem; font-weight: 700;">Juara 3</span>',
                                default => '<span class="fw-bold" style="color: var(--slate-muted); font-size: 0.85rem;">#' . $rank . '</span>',
                            };
                            ?>
                            <tr style="<?= $isSaya ? 'background: rgba(37, 99, 235, 0.05) !important;' : '' ?>">
                                <td class="text-center">
                                    <?= $medalPill ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;">
                                            <?= e($m['nama']) ?>
                                        </div>
                                        <?php if ($isSaya): ?>
                                            <span class="badge bg-primary text-white" style="font-size: 0.65rem; border-radius: 9999px;">
                                                Kamu
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="small" style="color: var(--slate-muted); font-size: 0.74rem;"><?= e($m['username']) ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill px-2.5 py-1 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-secondary); font-size: 0.74rem; border: 1px solid rgba(226, 232, 240, 0.8);">
                                        <?= (int) ($m['total_badge'] ?? 0) ?> Lencana
                                    </span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold fs-6" style="color: var(--accent-sapphire); font-family: 'Space Grotesk', sans-serif;">
                                        <?= number_format((float) ($m['nilai_rata'] ?? 0), 1) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php $rank++; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
