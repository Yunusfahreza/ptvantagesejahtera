<nav aria-label="breadcrumb">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= e(url('/guru/rekap')) ?>">Rekap Nilai</a></li>
        <li class="breadcrumb-item">
            <a href="<?= e(url('/guru/rekap/kelas/' . $pengampu['id'])) ?>">
                <?= e($pengampu['kelas_nama']) ?> &mdash; <?= e($pengampu['mapel_nama']) ?>
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= e($siswa['nama']) ?></li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <h1 class="h4 mb-1"><?= e($siswa['nama']) ?></h1>
        <p class="text-body-secondary">
            <?= e($pengampu['kelas_nama']) ?> &middot; <?= e($pengampu['mapel_nama']) ?>
            &middot; rata-rata <strong><?= e(nilai_tampil($baris['rata'] ?? null)) ?></strong>
        </p>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Jenis</th>
                            <th>Judul</th>
                            <th class="text-center">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($rekap['tugas'] === [] && $rekap['kuis'] === []): ?>
                            <tr><td colspan="3" class="text-center text-body-secondary py-4">
                                Belum ada tugas atau kuis.
                            </td></tr>
                        <?php endif; ?>

                        <?php foreach ($rekap['tugas'] as $t): ?>
                            <tr>
                                <td><span class="badge text-bg-primary">Tugas</span></td>
                                <td>
                                    <?= e($t['judul']) ?>
                                    <div class="small text-body-secondary">
                                        tenggat <?= e(tanggal_id($t['tenggat'])) ?>
                                    </div>
                                </td>
                                <td class="text-center fw-semibold">
                                    <?= e(nilai_tampil($baris['nilai_tugas'][(int) $t['id']] ?? null)) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($rekap['kuis'] as $q): ?>
                            <tr>
                                <td><span class="badge text-bg-info">Kuis</span></td>
                                <td>
                                    <?= e($q['judul']) ?>
                                    <div class="small text-body-secondary">
                                        dibuka <?= e(tanggal_id($q['mulai'])) ?>
                                    </div>
                                </td>
                                <td class="text-center fw-semibold">
                                    <?= e(nilai_tampil($baris['nilai_kuis'][(int) $q['id']] ?? null)) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
