<?php
$daftarHari = [
    'senin'  => 'Senin',
    'selasa' => 'Selasa',
    'rabu'   => 'Rabu',
    'kamis'  => 'Kamis',
    'jumat'  => 'Jumat',
    'sabtu'  => 'Sabtu',
];
$hariIni = match ((int) date('N')) {
    1 => 'senin',
    2 => 'selasa',
    3 => 'rabu',
    4 => 'kamis',
    5 => 'jumat',
    6 => 'sabtu',
    default => 'minggu',
};
?>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Akademik</span>
            <span>&bull;</span>
            <span>Jadwal Pelajaran</span>
        </div>
        <h1 class="page-title">Jadwal Sesi Pembelajaran</h1>
        <p class="page-subtitle">Rangkaian jadwal belajar mata pelajaran kelas Anda setiap hari</p>
    </div>

    <!-- Filter Hari Navigation Pills -->
    <div class="filter-nav-group d-none d-md-inline-flex">
        <button type="button" class="filter-nav-pill active" data-filter-target="#jadwalContainer" data-filter-value="all">
            Semua Hari
        </button>
        <?php foreach ($daftarHari as $kodeHari => $namaHari): ?>
            <button type="button" class="filter-nav-pill <?= $kodeHari === $hariIni ? 'fw-bold' : '' ?>" 
                    data-filter-target="#jadwalContainer" data-filter-value="<?= e($kodeHari) ?>">
                <?= e($namaHari) ?> <?= $kodeHari === $hariIni ? 'â€¢' : '' ?>
            </button>
        <?php endforeach; ?>
    </div>
</div>

<div class="row g-4" id="jadwalContainer">
    <?php foreach ($daftarHari as $kodeHari => $namaHari): ?>
        <?php
        $list = $jadwal[$kodeHari] ?? [];
        $isHariIni = ($kodeHari === $hariIni);
        ?>
        <div class="col-12 col-md-6 col-xl-4 filterable-item" data-category="<?= e($kodeHari) ?>">
            <div class="card h-100 shadow-sm border-0 <?= $isHariIni ? 'border-primary' : '' ?>" 
                 style="<?= $isHariIni ? 'box-shadow: 0 12px 36px -4px rgba(37, 99, 235, 0.15) !important; border: 1.5px solid rgba(37, 99, 235, 0.4) !important;' : '' ?>">
                
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.95rem;"><?= e($namaHari) ?></span>
                        <?php if ($isHariIni): ?>
                            <span class="badge bg-primary text-white px-2 py-1" style="font-size: 0.68rem; border-radius: 9999px;">
                                Hari Ini
                            </span>
                        <?php endif; ?>
                    </div>
                    <span class="badge px-2 py-1" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-tertiary); font-size: 0.72rem; border-radius: 6px;">
                        <?= count($list) ?> Sesi
                    </span>
                </div>

                <div class="card-body p-3">
                    <?php if ($list === []): ?>
                        <div class="text-center py-5" style="color: var(--slate-muted);">
                            <div class="mb-2">â˜•</div>
                            <span class="small">Tidak ada jadwal pelajaran</span>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-2.5">
                            <?php foreach ($list as $item): ?>
                                <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(226, 232, 240, 0.9); box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02); transition: all 0.2s ease;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge d-inline-flex align-items-center gap-1.5 px-2.5 py-1" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.2); font-size: 0.72rem; border-radius: 6px;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                            </svg>
                                            <span><?= e(substr((string) $item['jam_mulai'], 0, 5)) ?> - <?= e(substr((string) $item['jam_selesai'], 0, 5)) ?> WIB</span>
                                        </span>

                                        <span class="badge fw-bold" style="background: rgba(15, 23, 42, 0.06); color: var(--slate-primary); font-size: 0.68rem; border-radius: 6px;">
                                            <?= e($item['mapel_kode']) ?>
                                        </span>
                                    </div>

                                    <div class="fw-bold mb-1" style="color: var(--slate-primary); font-size: 0.92rem;">
                                        <?= e($item['mapel_nama']) ?>
                                    </div>

                                    <div class="small mb-1.5" style="color: var(--slate-tertiary);">
                                        Guru: <span class="fw-semibold" style="color: var(--slate-secondary);"><?= e($item['guru_nama']) ?></span>
                                    </div>

                                    <?php if (($item['ruangan'] ?? '') !== ''): ?>
                                        <div class="small d-inline-flex align-items-center gap-1 fw-semibold" style="color: var(--accent-emerald); font-size: 0.75rem;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            <span>Ruangan: <?= e($item['ruangan']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
