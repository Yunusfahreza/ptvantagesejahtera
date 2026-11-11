<?php

use App\Models\GoogleMeet;

$sesiLive = array_values(array_filter($data, static fn(array $r): bool => $r['status'] === 'berlangsung'));
?>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Kelas Virtual</span>
            <span>&bull;</span>
            <span>Google Meet</span>
        </div>
        <h1 class="page-title">Google Meet & Tatap Muka Daring</h1>
        <p class="page-subtitle">Gabung langsung ke ruang kelas virtual Google Meet sesuai jadwal mata pelajaran kelas Anda</p>
    </div>

    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari mapel, guru, atau topik..." data-instant-filter="#gridMeetSiswa">
    </div>
</div>

<!-- Highlight Card untuk Sesi yang Sedang Berlangsung (LIVE) -->
<?php if ($sesiLive !== []): ?>
    <div class="card border-0 mb-4 p-4"
         style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(30, 41, 59, 0.94)); color: #ffffff; border-radius: 26px; box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill"
                 style="background: rgba(16, 185, 129, 0.18); border: 1px solid rgba(52, 211, 153, 0.4);">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #34d399; box-shadow: 0 0 10px #34d399;"></span>
                <span class="small fw-bold text-uppercase" style="color: #6ee7b7; letter-spacing: 0.06em; font-size: 0.72rem;">
                    Kelas Sedang Berlangsung (LIVE)
                </span>
            </div>
            <span class="small" style="color: #94a3b8; font-size: 0.78rem;">
                <?= count($sesiLive) ?> sesi Google Meet aktif saat ini
            </span>
        </div>

        <div class="row g-3">
            <?php foreach ($sesiLive as $live): ?>
                <div class="col-12 <?= count($sesiLive) > 1 ? 'col-lg-6' : '' ?>">
                    <div class="p-3 p-md-4 rounded-4 d-flex flex-wrap justify-content-between align-items-center gap-3"
                         style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.14);">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(96, 165, 250, 0.2); color: #93c5fd; font-size: 0.7rem;">
                                    <?= e($live['mapel_kode']) ?> &bull; <?= e($live['mapel_nama']) ?>
                                </span>
                                <span class="small" style="color: #cbd5e1; font-size: 0.76rem;">
                                    <?= e(substr((string) $live['jam_mulai'], 0, 5)) ?> &ndash; <?= e(substr((string) $live['jam_selesai'], 0, 5)) ?> WIB
                                </span>
                            </div>
                            <h2 class="h5 fw-bold mb-1" style="color: #ffffff !important;"><?= e($live['judul']) ?></h2>
                            <div class="small" style="color: #94a3b8; font-size: 0.8rem;">
                                Pengajar: <strong style="color: #e2e8f0;"><?= e($live['guru_nama']) ?></strong> &bull;
                                Kode Ruang: <code style="background: rgba(255,255,255,0.12); color: #6ee7b7;"><?= e(GoogleMeet::ekstrakKode($live['link_meet'])) ?></code>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="<?= e($live['link_meet']) ?>" target="_blank" rel="noopener noreferrer"
                               class="btn btn-success px-4 py-2 fw-bold rounded-pill d-inline-flex align-items-center gap-2 shadow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 10l4.553-2.276A1 1 0 0 1 21 8.618v6.764a1 1 0 0 1-1.447.894L15 14v-4z"/><rect x="3" y="6" width="12" height="12" rx="2" ry="2"/>
                                </svg>
                                <span>Gabung Google Meet &nearr;</span>
                            </a>
                            <button type="button" class="btn btn-outline-light px-3 py-2 rounded-pill small fw-semibold"
                                    onclick="salinMeetSiswa('<?= e($live['link_meet']) ?>', this)">
                                Salin Link
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Filter Tabs -->
<div class="filter-nav-group d-flex gap-2 mb-4">
    <button type="button" class="btn btn-sm btn-primary active px-3 py-1.5 rounded-pill fw-semibold"
            data-filter-value="all" data-filter-target="#gridMeetSiswa">Semua Jadwal</button>
    <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1.5 rounded-pill fw-semibold"
            data-filter-value="berlangsung" data-filter-target="#gridMeetSiswa">Sedang Live</button>
    <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1.5 rounded-pill fw-semibold"
            data-filter-value="terjadwal" data-filter-target="#gridMeetSiswa">Terjadwal</button>
    <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1.5 rounded-pill fw-semibold"
            data-filter-value="selesai" data-filter-target="#gridMeetSiswa">Riwayat Selesai</button>
</div>

<div class="row g-4" id="gridMeetSiswa">
    <?php if ($data === []): ?>
        <div class="col-12">
            <div class="empty-state-box">
                <div class="empty-state-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M15 10l4.553-2.276A1 1 0 0 1 21 8.618v6.764a1 1 0 0 1-1.447.894L15 14v-4z"/><rect x="3" y="6" width="12" height="12" rx="2" ry="2"/>
                    </svg>
                </div>
                <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Jadwal Google Meet</h3>
                <p class="small text-muted mb-0">Guru pengampu kelas Anda belum menjadwalkan sesi pertemuan Google Meet.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($data as $gm): ?>
        <?php
        $kode = GoogleMeet::ekstrakKode($gm['link_meet']);
        $isLive = $gm['status'] === 'berlangsung';
        $isDone = $gm['status'] === 'selesai';
        ?>
        <div class="col-12 col-md-6 col-lg-4 filterable-item" data-category="<?= e($gm['status']) ?>">
            <div class="card h-100 shadow-sm border-0 d-flex flex-column justify-content-between"
                 style="<?= $isLive ? 'border: 1.5px solid rgba(5, 150, 105, 0.35) !important;' : '' ?>">
                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
                        <span class="badge px-2.5 py-1 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 8px; font-size: 0.72rem;">
                            <?= e($gm['mapel_kode']) ?> &bull; <?= e($gm['mapel_nama']) ?>
                        </span>

                        <?php if ($isLive): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 d-inline-flex align-items-center gap-1">
                                <span style="width:6px;height:6px;border-radius:50%;background:#059669;display:inline-block;"></span>
                                LIVE
                            </span>
                        <?php elseif ($gm['status'] === 'terjadwal'): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">Terjadwal</span>
                        <?php else: ?>
                            <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle px-2.5 py-1">Selesai</span>
                        <?php endif; ?>
                    </div>

                    <h2 class="h5 fw-bold mb-2" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                        <?= e($gm['judul']) ?>
                    </h2>

                    <div class="small fw-semibold mb-2 d-flex align-items-center gap-2" style="color: var(--slate-secondary); font-size: 0.8rem;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--slate-tertiary);">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span><?= e($gm['guru_nama']) ?></span>
                    </div>

                    <?php if (($gm['deskripsi'] ?? '') !== ''): ?>
                        <p class="small flex-grow-1 mb-3" style="color: var(--slate-secondary); line-height: 1.55; font-size: 0.83rem;">
                            <?= e(mb_strimwidth((string) $gm['deskripsi'], 0, 120, '...')) ?>
                        </p>
                    <?php else: ?>
                        <div class="flex-grow-1"></div>
                    <?php endif; ?>

                    <div class="p-2.5 rounded-3 mb-3 small" style="background: rgba(248, 250, 252, 0.85); border: 1px solid rgba(226, 232, 240, 0.9);">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="color: var(--slate-tertiary);">Jadwal:</span>
                            <strong style="color: var(--slate-primary);">
                                <?= e(tanggal_id($gm['tanggal'], 'd M Y')) ?> &bull; <?= e(substr((string) $gm['jam_mulai'], 0, 5)) ?>&ndash;<?= e(substr((string) $gm['jam_selesai'], 0, 5)) ?>
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: var(--slate-tertiary);">Kode Meet:</span>
                            <code style="font-size: 0.78rem;"><?= e($kode) ?></code>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="<?= e($gm['link_meet']) ?>" target="_blank" rel="noopener noreferrer"
                           class="btn btn-sm flex-grow-1 py-2 fw-bold rounded-pill d-inline-flex align-items-center justify-content-center gap-1.5 <?= $isDone ? 'btn-outline-secondary' : ($isLive ? 'btn-success shadow-sm' : 'btn-primary shadow-sm') ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 10l4.553-2.276A1 1 0 0 1 21 8.618v6.764a1 1 0 0 1-1.447.894L15 14v-4z"/><rect x="3" y="6" width="12" height="12" rx="2" ry="2"/>
                            </svg>
                            <span><?= $isDone ? 'Tautan Ruang' : 'Masuk Google Meet' ?> &nearr;</span>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                onclick="salinMeetSiswa('<?= e($gm['link_meet']) ?>', this)" title="Salin Tautan Meet">
                            Salin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
function salinMeetSiswa(url, btn) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(function () {
            var ori = btn.textContent;
            btn.textContent = 'Disalin!';
            setTimeout(function () { btn.textContent = ori; }, 1600);
        });
    } else {
        window.prompt('Salin tautan Google Meet berikut:', url);
    }
}
</script>
