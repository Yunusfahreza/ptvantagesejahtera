<nav aria-label="breadcrumb">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= e(url('/guru/rekap')) ?>">Rekap Nilai</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            <?= e($pengampu['kelas_nama']) ?> &mdash; <?= e($pengampu['mapel_nama']) ?>
        </li>
    </ol>
</nav>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h4 mb-0"><?= e($pengampu['kelas_nama']) ?> &middot; <?= e($pengampu['mapel_nama']) ?></h1>
        <p class="small text-body-secondary mb-0">
            <?= e(count($rekap['baris'])) ?> siswa &middot;
            <?= e(count($rekap['tugas'])) ?> tugas &middot;
            <?= e(count($rekap['kuis'])) ?> kuis &middot;
            rata-rata kelas <strong><?= e(nilai_tampil($rekap['rata_kelas'])) ?></strong>
        </p>
    </div>
    <a class="btn btn-outline-secondary btn-sm"
       href="<?= e(url('/guru/rekap/csv/' . $pengampu['id'])) ?>">Unduh CSV</a>
</div>

<?php if ($rekap['tugas'] === [] && $rekap['kuis'] === []): ?>
    <div class="alert alert-info">
        Belum ada tugas atau kuis pada kelas dan mata pelajaran ini.
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-rekap align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="kol-nama">Nama siswa</th>
                    <?php foreach ($rekap['tugas'] as $t): ?>
                        <th class="text-center" title="<?= e($t['judul']) ?>">
                            <span class="badge text-bg-primary">T</span>
                            <?= e(mb_strimwidth((string) $t['judul'], 0, 18, '...')) ?>
                        </th>
                    <?php endforeach; ?>
                    <?php foreach ($rekap['kuis'] as $q): ?>
                        <th class="text-center" title="<?= e($q['judul']) ?>">
                            <span class="badge text-bg-info">K</span>
                            <?= e(mb_strimwidth((string) $q['judul'], 0, 18, '...')) ?>
                        </th>
                    <?php endforeach; ?>
                    <th class="text-center">Rata-rata</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rekap['baris'] === []): ?>
                    <tr>
                        <td colspan="99" class="text-center text-body-secondary py-4">
                            Belum ada siswa aktif di kelas ini.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($rekap['baris'] as $b): ?>
                    <tr>
                        <td class="kol-nama fw-semibold"><?= e($b['siswa']['nama']) ?></td>

                        <?php foreach ($rekap['tugas'] as $t): ?>
                            <td class="text-center">
                                <?= e(nilai_tampil($b['nilai_tugas'][(int) $t['id']] ?? null)) ?>
                            </td>
                        <?php endforeach; ?>

                        <?php foreach ($rekap['kuis'] as $q): ?>
                            <td class="text-center">
                                <?= e(nilai_tampil($b['nilai_kuis'][(int) $q['id']] ?? null)) ?>
                            </td>
                        <?php endforeach; ?>

                        <td class="text-center fw-semibold"><?= e(nilai_tampil($b['rata'])) ?></td>
                        <td class="text-center">
                            <a class="btn btn-sm btn-outline-primary"
                               href="<?= e(url('/guru/rekap/siswa/' . $pengampu['id'] . '/' . $b['siswa']['id'])) ?>">
                                Rincian
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-transparent small text-body-secondary">
        Tanda <strong>-</strong> berarti belum dikumpulkan atau belum dinilai.
        Nilai kosong tidak dihitung sebagai 0 pada rata-rata.
    </div>
</div>
