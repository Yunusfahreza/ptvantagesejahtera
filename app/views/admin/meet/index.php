<?php

use App\Models\GoogleMeet;

$jumlahLive = count(array_filter($data, static fn(array $r): bool => $r['status'] === 'berlangsung'));
$jumlahTerjadwal = count(array_filter($data, static fn(array $r): bool => $r['status'] === 'terjadwal'));
?>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Monitoring Kurikulum</span>
            <span>&bull;</span>
            <span>Google Meet Sekolah</span>
        </div>
        <h1 class="page-title">Monitoring Google Meet</h1>
        <p class="page-subtitle">Pantau seluruh sesi kelas tatap muka daring Google Meet lintas kelas dan guru pengampu</p>
    </div>

    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari kelas, guru, atau mapel..." data-instant-filter="#tabelMeetAdmin">
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="card bento-stat-card border-0">
            <span class="bento-stat-label">Total Sesi</span>
            <div class="bento-stat-number stat-counter" data-counter="<?= count($data) ?>"><?= count($data) ?></div>
            <div class="bento-stat-subtext">Terdata di sistem</div>
        </div>
    </div>
    <div class="col-4">
        <div class="card bento-stat-card border-0">
            <span class="bento-stat-label">Sedang Live</span>
            <div class="bento-stat-number text-success stat-counter" data-counter="<?= $jumlahLive ?>"><?= $jumlahLive ?></div>
            <div class="bento-stat-subtext">Ruang aktif sekarang</div>
        </div>
    </div>
    <div class="col-4">
        <div class="card bento-stat-card border-0">
            <span class="bento-stat-label">Terjadwal</span>
            <div class="bento-stat-number text-primary stat-counter" data-counter="<?= $jumlahTerjadwal ?>"><?= $jumlahTerjadwal ?></div>
            <div class="bento-stat-subtext">Agenda mendatang</div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="min-width: 220px;">Topik Pertemuan</th>
                    <th>Kelas & Mapel</th>
                    <th>Guru Pengampu</th>
                    <th>Waktu</th>
                    <th>Ruang Meet</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelMeetAdmin">
                <?php if ($data === []): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5" style="color: var(--slate-muted);">
                            Belum ada sesi Google Meet yang dibuat oleh guru pengampu.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $gm): ?>
                    <tr>
                        <td class="col-wrap">
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.9rem;"><?= e($gm['judul']) ?></div>
                        </td>
                        <td>
                            <div class="fw-semibold" style="color: var(--slate-primary); font-size: 0.86rem;"><?= e($gm['kelas_nama']) ?></div>
                            <div class="small" style="color: var(--slate-muted); font-size: 0.74rem;"><?= e($gm['mapel_kode']) ?> &bull; <?= e($gm['mapel_nama']) ?></div>
                        </td>
                        <td class="small fw-semibold" style="color: var(--slate-secondary);"><?= e($gm['guru_nama']) ?></td>
                        <td class="small">
                            <div class="fw-semibold"><?= e(tanggal_id($gm['tanggal'], 'd M Y')) ?></div>
                            <div style="color: var(--slate-muted);"><?= e(substr((string) $gm['jam_mulai'], 0, 5)) ?>&ndash;<?= e(substr((string) $gm['jam_selesai'], 0, 5)) ?> WIB</div>
                        </td>
                        <td>
                            <a href="<?= e($gm['link_meet']) ?>" target="_blank" rel="noopener noreferrer"
                               class="badge px-2.5 py-1 text-decoration-none"
                               style="background: rgba(5, 150, 105, 0.08); color: #047857; border: 1px solid rgba(5, 150, 105, 0.25);">
                                <?= e(GoogleMeet::ekstrakKode($gm['link_meet'])) ?> &nearr;
                            </a>
                        </td>
                        <td>
                            <?php if ($gm['status'] === 'berlangsung'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">Sedang Live</span>
                            <?php elseif ($gm['status'] === 'terjadwal'): ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">Terjadwal</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle px-2.5 py-1">Selesai</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <form method="post" action="<?= e(url('/admin/meet/hapus')) ?>" data-konfirmasi="Hapus jadwal Google Meet ini?">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= e($gm['id']) ?>">
                                <button class="btn btn-sm btn-outline-danger px-2.5 py-1">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
