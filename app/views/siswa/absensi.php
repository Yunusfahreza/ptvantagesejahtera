<?php
$totalHadir = 0;
$totalSakit = 0;
$totalIzin  = 0;
$totalAlpha = 0;
$totalSesi  = 0;

foreach ($rekap as $r) {
    $totalHadir += (int) ($r['hadir'] ?? 0);
    $totalSakit += (int) ($r['sakit'] ?? 0);
    $totalIzin  += (int) ($r['izin'] ?? 0);
    $totalAlpha += (int) ($r['alpha'] ?? 0);
    $totalSesi  += (int) ($r['total'] ?? 0);
}

$persenGlobal = $totalSesi > 0 ? round(($totalHadir / $totalSesi) * 100) : 100;
?>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Kehadiran</span>
            <span>&bull;</span>
            <span>Presensi Belajar</span>
        </div>
        <h1 class="page-title">Presensi & Kehadiran Saya</h1>
        <p class="page-subtitle">Rekapitulasi kehadiran tatap muka dan sesi daring di seluruh mata pelajaran</p>
    </div>

    <div class="d-flex align-items-center gap-2 p-2 px-3 rounded-pill" style="background: rgba(16, 185, 129, 0.08); border: 1.5px solid rgba(16, 185, 129, 0.25);">
        <span class="badge rounded-circle p-1" style="background: var(--accent-emerald); width: 8px; height: 8px;"></span>
        <span class="small fw-bold" style="color: var(--accent-emerald);">Tingkat Kehadiran: <?= $persenGlobal ?>%</span>
    </div>
</div>

<!-- 4 Stat Counters Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card h-100 shadow-sm border-0 bento-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <span class="bento-stat-label">Hadir</span>
                <div class="squircle-icon squircle-icon-green" style="width: 36px; height: 36px; border-radius: 9px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
            </div>
            <div>
                <div class="bento-stat-number stat-counter" data-counter="<?= $totalHadir ?>"><?= $totalHadir ?></div>
                <div class="bento-stat-subtext">Pertemuan dihadiri</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card h-100 shadow-sm border-0 bento-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <span class="bento-stat-label">Sakit</span>
                <div class="squircle-icon squircle-icon-amber" style="width: 36px; height: 36px; border-radius: 9px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
            </div>
            <div>
                <div class="bento-stat-number stat-counter" data-counter="<?= $totalSakit ?>"><?= $totalSakit ?></div>
                <div class="bento-stat-subtext">Surat keterangan</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card h-100 shadow-sm border-0 bento-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <span class="bento-stat-label">Izin</span>
                <div class="squircle-icon squircle-icon-blue" style="width: 36px; height: 36px; border-radius: 9px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
            </div>
            <div>
                <div class="bento-stat-number stat-counter" data-counter="<?= $totalIzin ?>"><?= $totalIzin ?></div>
                <div class="bento-stat-subtext">Disetujui sekolah</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card h-100 shadow-sm border-0 bento-stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <span class="bento-stat-label">Alpha</span>
                <div class="squircle-icon squircle-icon-rose" style="width: 36px; height: 36px; border-radius: 9px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </div>
            </div>
            <div>
                <div class="bento-stat-number stat-counter" data-counter="<?= $totalAlpha ?>"><?= $totalAlpha ?></div>
                <div class="bento-stat-subtext">Tanpa keterangan</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h2 class="h6 mb-0 fw-bold" style="color: var(--slate-primary);">Rincian Presensi Per Mata Pelajaran</h2>
        <span class="small text-muted"><?= count($rekap) ?> Mata Pelajaran Terdata</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th class="text-center" style="width: 100px;">Hadir</th>
                    <th class="text-center" style="width: 100px;">Sakit</th>
                    <th class="text-center" style="width: 100px;">Izin</th>
                    <th class="text-center" style="width: 100px;">Alpha</th>
                    <th style="min-width: 160px;">Persentase Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rekap === []): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color: var(--slate-muted);">
                            <div class="mb-2">ðŸ“‹</div>
                            Belum ada data catatan presensi yang tercatat untuk Anda.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($rekap as $r): ?>
                    <?php
                    $total = (int) ($r['total'] ?? 0);
                    $hadir = (int) ($r['hadir'] ?? 0);
                    $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;
                    $barColor = match (true) {
                        $persen >= 85 => 'var(--accent-emerald)',
                        $persen >= 70 => 'var(--accent-amber)',
                        default       => 'var(--accent-rose)',
                    };
                    ?>
                    <tr>
                        <td>
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($r['mapel_nama']) ?></div>
                            <div class="small" style="color: var(--slate-muted); font-size: 0.74rem;"><?= e($r['mapel_kode']) ?> &bull; Total <?= $total ?> Pertemuan</div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                <?= $hadir ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1">
                                <?= (int) ($r['sakit'] ?? 0) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1">
                                <?= (int) ($r['izin'] ?? 0) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1">
                                <?= (int) ($r['alpha'] ?? 0) ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 7px; background: rgba(226, 232, 240, 0.8); border-radius: 9999px;">
                                    <div class="progress-bar" style="width: <?= $persen ?>%; background-color: <?= $barColor ?>; border-radius: 9999px;"></div>
                                </div>
                                <span class="small fw-bold" style="color: var(--slate-primary); width: 38px; text-align: right;"><?= $persen ?>%</span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
