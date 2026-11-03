<nav aria-label="breadcrumb">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="<?= e(url('/guru/rekap')) ?>">Rekap Nilai</a></li>
        <li class="breadcrumb-item">
            <a href="<?= e(url('/guru/rekap/kelas/' . $pengampu['id'])) ?>">
                Kelas <?= e($pengampu['kelas_nama']) ?> &mdash; <?= e($pengampu['mapel_nama']) ?>
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= e($siswa['nama']) ?></li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="page-title"><?= e($siswa['nama']) ?></h1>
                <p class="page-subtitle">
                    Kelas <?= e($pengampu['kelas_nama']) ?> &bull; <?= e($pengampu['mapel_nama']) ?> &bull; Rata-rata: <strong style="color: var(--accent-sapphire);"><?= e(nilai_tampil($baris['rata'] ?? null)) ?></strong>
                </p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/guru/rekap/kelas/' . $pengampu['id'])) ?>">
                &larr; Kembali ke Kelas
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 120px;">Jenis</th>
                            <th>Judul Asesmen</th>
                            <th class="text-center" style="width: 120px;">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($rekap['tugas'] === [] && $rekap['kuis'] === []): ?>
                            <tr>
                                <td colspan="3" class="text-center py-5" style="color: var(--slate-muted);">
                                    Belum ada tugas atau kuis yang terdata.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($rekap['tugas'] as $t): ?>
                            <tr>
                                <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">Tugas</span></td>
                                <td>
                                    <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($t['judul']) ?></div>
                                    <div class="small" style="color: var(--slate-muted); font-size: 0.74rem;">
                                        Tenggat: <?= e(tanggal_id($t['tenggat'])) ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold px-2.5 py-1 rounded-2" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); font-size: 0.92rem; font-family: 'Space Grotesk', sans-serif;">
                                        <?= e(nilai_tampil($baris['nilai_tugas'][(int) $t['id']] ?? null)) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($rekap['kuis'] as $q): ?>
                            <tr>
                                <td><span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">Kuis</span></td>
                                <td>
                                    <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($q['judul']) ?></div>
                                    <div class="small" style="color: var(--slate-muted); font-size: 0.74rem;">
                                        Dibuka: <?= e(tanggal_id($q['mulai'])) ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold px-2.5 py-1 rounded-2" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); font-size: 0.92rem; font-family: 'Space Grotesk', sans-serif;">
                                        <?= e(nilai_tampil($baris['nilai_kuis'][(int) $q['id']] ?? null)) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
