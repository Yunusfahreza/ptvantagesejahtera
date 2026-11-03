<nav aria-label="breadcrumb">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="<?= e(url('/guru/rekap')) ?>">Rekap Nilai</a></li>
        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);">
            <?= e($pengampu['kelas_nama']) ?> &mdash; <?= e($pengampu['mapel_nama']) ?>
        </li>
    </ol>
</nav>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1 class="page-title">Kelas <?= e($pengampu['kelas_nama']) ?> &bull; <?= e($pengampu['mapel_nama']) ?></h1>
        <p class="page-subtitle">
            <?= e(count($rekap['baris'])) ?> Siswa &bull; <?= e(count($rekap['tugas'])) ?> Tugas &bull; <?= e(count($rekap['kuis'])) ?> Kuis &bull; Rerata Kelas: <strong style="color: var(--accent-sapphire);"><?= e(nilai_tampil($rekap['rata_kelas'])) ?></strong>
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a class="btn btn-outline-primary btn-sm px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm"
           href="<?= e(url('/guru/rekap/csv/' . $pengampu['id'])) ?>">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            <span>Ekspor Berkas CSV</span>
        </a>
    </div>
</div>

<?php if ($rekap['tugas'] === [] && $rekap['kuis'] === []): ?>
    <div class="alert alert-info border-0 shadow-sm mb-4">
        Belum ada tugas atau kuis yang diterbitkan pada kelas dan mata pelajaran ini.
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="min-width: 180px;">Nama Siswa</th>
                    <?php foreach ($rekap['tugas'] as $t): ?>
                        <th class="text-center" title="<?= e($t['judul']) ?>" style="min-width: 110px;">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-1.5 py-0.5 me-1" style="font-size: 0.65rem;">T</span>
                            <span class="small fw-semibold"><?= e(mb_strimwidth((string) $t['judul'], 0, 16, '...')) ?></span>
                        </th>
                    <?php endforeach; ?>
                    <?php foreach ($rekap['kuis'] as $q): ?>
                        <th class="text-center" title="<?= e($q['judul']) ?>" style="min-width: 110px;">
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-1.5 py-0.5 me-1" style="font-size: 0.65rem;">K</span>
                            <span class="small fw-semibold"><?= e(mb_strimwidth((string) $q['judul'], 0, 16, '...')) ?></span>
                        </th>
                    <?php endforeach; ?>
                    <th class="text-center" style="width: 110px;">Rerata Nilai</th>
                    <th style="width: 80px;" class="text-end">Rincian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rekap['baris'] === []): ?>
                    <tr>
                        <td colspan="99" class="text-center py-5" style="color: var(--slate-muted);">
                            Belum ada siswa aktif terdaftar di rombel kelas ini.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($rekap['baris'] as $b): ?>
                    <tr>
                        <td>
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($b['siswa']['nama']) ?></div>
                        </td>

                        <?php foreach ($rekap['tugas'] as $t): ?>
                            <td class="text-center">
                                <span class="small fw-semibold" style="color: var(--slate-secondary);">
                                    <?= e(nilai_tampil($b['nilai_tugas'][(int) $t['id']] ?? null)) ?>
                                </span>
                            </td>
                        <?php endforeach; ?>

                        <?php foreach ($rekap['kuis'] as $q): ?>
                            <td class="text-center">
                                <span class="small fw-semibold" style="color: var(--slate-secondary);">
                                    <?= e(nilai_tampil($b['nilai_kuis'][(int) $q['id']] ?? null)) ?>
                                </span>
                            </td>
                        <?php endforeach; ?>

                        <td class="text-center">
                            <span class="fw-bold px-2 py-0.5 rounded-2" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); font-size: 0.9rem; font-family: 'Space Grotesk', sans-serif;">
                                <?= e(nilai_tampil($b['rata'] ?? null)) ?>
                            </span>
                        </td>

                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary px-2 py-1"
                               href="<?= e(url('/guru/rekap/siswa/' . $pengampu['id'] . '/' . $b['siswa']['id'])) ?>">
                                Lihat
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
