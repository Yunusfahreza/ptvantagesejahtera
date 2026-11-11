<?php

use App\Models\GoogleMeet;
?>
<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Tatap Muka Daring</span>
            <span>&bull;</span>
            <span>Integrasi Google Meet</span>
        </div>
        <h1 class="page-title">Google Meet & Kelas Live</h1>
        <p class="page-subtitle">Jadwalkan ruang rapat virtual Google Meet untuk setiap mata pelajaran dan pantau status kelas secara real-time</p>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-2">
        <div class="search-pill-wrapper d-none d-md-block" style="max-width: 240px;">
            <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="search-pill-input" placeholder="Cari topik atau kelas..." data-instant-filter="#tabelMeetGuru">
        </div>
        <?php if ($pengampu !== []): ?>
            <a class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm" href="<?= e(url('/guru/meet/form')) ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                <span>Jadwalkan Google Meet</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun. Silakan hubungi administrator sekolah.
    </div>
<?php endif; ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div class="filter-nav-group d-flex gap-2">
        <button type="button" class="btn btn-sm btn-primary active px-3 py-1.5 rounded-pill fw-semibold"
                data-filter-value="all" data-filter-target="#tabelMeetGuru">Semua Sesi</button>
        <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1.5 rounded-pill fw-semibold"
                data-filter-value="berlangsung" data-filter-target="#tabelMeetGuru">Sedang Live</button>
        <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1.5 rounded-pill fw-semibold"
                data-filter-value="terjadwal" data-filter-target="#tabelMeetGuru">Terjadwal</button>
        <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1.5 rounded-pill fw-semibold"
                data-filter-value="selesai" data-filter-target="#tabelMeetGuru">Selesai</button>
    </div>

    <a href="https://meet.google.com/new" target="_blank" rel="noopener noreferrer"
       class="btn btn-sm btn-outline-success px-3 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-1.5">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 10l4.553-2.276A1 1 0 0 1 21 8.618v6.764a1 1 0 0 1-1.447.894L15 14v-4z"/><rect x="3" y="6" width="12" height="12" rx="2" ry="2"/>
        </svg>
        <span>Buat Ruang Meet Instan &nearr;</span>
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="min-width: 220px;">Topik Pertemuan</th>
                    <th>Kelas & Mapel</th>
                    <th>Jadwal Pelaksanaan</th>
                    <th>Tautan Google Meet</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelMeetGuru">
                <?php if ($data === []): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color: var(--slate-muted);">
                            <div class="mb-2 opacity-50">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M15 10l4.553-2.276A1 1 0 0 1 21 8.618v6.764a1 1 0 0 1-1.447.894L15 14v-4z"/><rect x="3" y="6" width="12" height="12" rx="2" ry="2"/>
                                </svg>
                            </div>
                            Belum ada jadwal Google Meet. Klik tombol "Jadwalkan Google Meet" untuk membuat sesi tatap muka daring.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $gm): ?>
                    <?php
                    $kode = GoogleMeet::ekstrakKode($gm['link_meet']);
                    $badgeStatus = match ($gm['status']) {
                        'berlangsung' => '<span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 d-inline-flex align-items-center gap-1.5"><span style="width:7px;height:7px;border-radius:50%;background:#059669;display:inline-block;"></span>Sedang Live</span>',
                        'terjadwal'   => '<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">Terjadwal</span>',
                        default       => '<span class="badge bg-secondary-subtle text-muted border border-secondary-subtle px-2.5 py-1">Selesai</span>',
                    };
                    ?>
                    <tr data-category="<?= e($gm['status']) ?>">
                        <td class="col-wrap">
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($gm['judul']) ?></div>
                            <?php if (($gm['deskripsi'] ?? '') !== ''): ?>
                                <div class="small" style="color: var(--slate-muted); font-size: 0.76rem;">
                                    <?= e(mb_strimwidth((string) $gm['deskripsi'], 0, 75, '...')) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-semibold" style="color: var(--slate-primary); font-size: 0.88rem;"><?= e($gm['kelas_nama']) ?></div>
                            <div class="small" style="color: var(--slate-muted); font-size: 0.74rem;"><?= e($gm['mapel_kode']) ?> &bull; <?= e($gm['mapel_nama']) ?></div>
                        </td>
                        <td>
                            <div class="fw-semibold" style="color: var(--slate-primary); font-size: 0.84rem;">
                                <?= e(tanggal_id($gm['tanggal'], 'd M Y')) ?>
                            </div>
                            <div class="small" style="color: var(--slate-tertiary); font-size: 0.75rem;">
                                <?= e(substr((string) $gm['jam_mulai'], 0, 5)) ?> &ndash; <?= e(substr((string) $gm['jam_selesai'], 0, 5)) ?> WIB
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1.5">
                                <a href="<?= e($gm['link_meet']) ?>" target="_blank" rel="noopener noreferrer"
                                   class="badge px-2.5 py-1.5 text-decoration-none fw-semibold"
                                   style="background: rgba(5, 150, 105, 0.08); color: #047857; border: 1px solid rgba(5, 150, 105, 0.25);">
                                    <?= e($kode) ?> &nearr;
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0.5 px-2"
                                        style="font-size: 0.72rem;"
                                        onclick="salinTautanMeet('<?= e($gm['link_meet']) ?>', this)">
                                    Salin
                                </button>
                            </div>
                        </td>
                        <td><?= $badgeStatus ?></td>
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap justify-content-end gap-1.5">
                                <?php if ($gm['status'] === 'terjadwal'): ?>
                                    <form method="post" action="<?= e(url('/guru/meet/status')) ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($gm['id']) ?>">
                                        <input type="hidden" name="status" value="berlangsung">
                                        <button type="submit" class="btn btn-sm btn-success px-2.5 py-1 fw-semibold">Mulai Live</button>
                                    </form>
                                <?php elseif ($gm['status'] === 'berlangsung'): ?>
                                    <a href="<?= e($gm['link_meet']) ?>" target="_blank" rel="noopener noreferrer"
                                       class="btn btn-sm btn-success px-2.5 py-1 fw-semibold">Buka Meet &nearr;</a>
                                    <form method="post" action="<?= e(url('/guru/meet/status')) ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($gm['id']) ?>">
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary px-2.5 py-1">Akhiri</button>
                                    </form>
                                <?php endif; ?>

                                <a href="<?= e(url('/guru/meet/form/' . $gm['id'])) ?>" class="btn btn-sm btn-outline-primary px-2.5 py-1">
                                    Ubah
                                </a>
                                <form method="post" action="<?= e(url('/guru/meet/hapus')) ?>" data-konfirmasi="Hapus jadwal Google Meet ini?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($gm['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger px-2.5 py-1">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function salinTautanMeet(url, btn) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(function () {
            var ori = btn.textContent;
            btn.textContent = 'Tersalin!';
            setTimeout(function () { btn.textContent = ori; }, 1600);
        });
    } else {
        window.prompt('Salin tautan Google Meet berikut:', url);
    }
}
</script>
