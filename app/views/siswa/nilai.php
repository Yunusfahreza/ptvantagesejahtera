<h1 class="h4 mb-3">Nilai Saya</h1>

<?php if ($kelompok === []): ?>
    <div class="alert alert-info">
        Belum ada nilai. Nilai muncul setelah Anda mengumpulkan tugas atau mengerjakan kuis.
    </div>
<?php endif; ?>

<?php foreach ($kelompok as $mapel => $isi): ?>
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-transparent d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="fw-semibold">
                <span class="badge text-bg-light border"><?= e($isi['kode']) ?></span>
                <?= e($mapel) ?>
            </span>
            <span class="small text-body-secondary">
                Rata-rata: <strong><?= e(nilai_tampil($isi['rata'])) ?></strong>
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Jenis</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th class="text-center">Nilai</th>
                        <th>Komentar guru</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($isi['item'] as $it): ?>
                        <tr>
                            <td>
                                <span class="badge text-bg-<?= $it['jenis'] === 'Tugas' ? 'primary' : 'info' ?>">
                                    <?= e($it['jenis']) ?>
                                </span>
                            </td>
                            <td>
                                <?= e($it['judul']) ?>
                                <div class="small text-body-secondary"><?= e(tanggal_id($it['waktu'])) ?></div>
                            </td>
                            <td>
                                <?php if ($it['status'] === 'terlambat'): ?>
                                    <span class="badge text-bg-warning">Terlambat</span>
                                <?php elseif ($it['status'] === 'tepat_waktu'): ?>
                                    <span class="badge text-bg-success">Tepat waktu</span>
                                <?php elseif ($it['status'] === 'belum_selesai'): ?>
                                    <span class="badge text-bg-secondary">Belum selesai</span>
                                <?php else: ?>
                                    <span class="badge text-bg-success">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center fw-semibold"><?= e(nilai_tampil($it['nilai'])) ?></td>
                            <td class="small"><?= $it['komentar'] === null ? '-' : nl2br(e($it['komentar'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach; ?>
