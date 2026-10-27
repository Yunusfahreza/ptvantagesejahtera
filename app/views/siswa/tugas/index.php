<h1 class="h4 mb-3">Tugas</h1>

<?php if ($data === []): ?>
    <div class="alert alert-info">Belum ada tugas untuk kelas Anda.</div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Mapel</th>
                    <th>Tenggat</th>
                    <th>Status</th>
                    <th>Nilai</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $t): ?>
                    <tr>
                        <td class="fw-semibold"><?= e($t['judul']) ?></td>
                        <td><span class="badge text-bg-light border"><?= e($t['mapel_kode']) ?></span></td>
                        <td class="small"><?= e(tanggal_id($t['tenggat'])) ?></td>
                        <td>
                            <?php if ($t['pengumpulan_id'] === null): ?>
                                <?php if (strtotime((string) $t['tenggat']) < time()): ?>
                                    <span class="badge text-bg-danger">Belum dikumpulkan (lewat tenggat)</span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary">Belum dikumpulkan</span>
                                <?php endif; ?>
                            <?php elseif ($t['status'] === 'terlambat'): ?>
                                <span class="badge text-bg-warning">Terlambat</span>
                            <?php else: ?>
                                <span class="badge text-bg-success">Tepat waktu</span>
                            <?php endif; ?>

                            <?php if ($t['nilai'] !== null): ?>
                                <span class="badge text-bg-primary">Sudah dinilai</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-semibold"><?= e(nilai_tampil($t['nilai'])) ?></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary"
                               href="<?= e(url('/siswa/tugas/detail/' . $t['id'])) ?>">Buka</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
