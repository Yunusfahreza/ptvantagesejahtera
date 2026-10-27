<nav aria-label="breadcrumb">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= e(url('/guru/tugas')) ?>">Tugas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Penilaian</li>
    </ol>
</nav>

<h1 class="h4 mb-1"><?= e($tugas['judul']) ?></h1>
<p class="text-body-secondary">
    <?= e($tugas['kelas_nama']) ?> &middot; <?= e($tugas['mapel_nama']) ?>
    &middot; tenggat <?= e(tanggal_id($tugas['tenggat'])) ?>
</p>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Siswa</th>
                    <th>Dikumpulkan</th>
                    <th>Status</th>
                    <th>Berkas</th>
                    <th style="min-width:22rem">Nilai dan komentar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($siswa as $s): ?>
                    <tr>
                        <td class="fw-semibold"><?= e($s['siswa_nama']) ?></td>
                        <td class="small"><?= e(tanggal_id($s['dikumpulkan_at'])) ?></td>
                        <td>
                            <?php if ($s['pengumpulan_id'] === null): ?>
                                <span class="badge text-bg-secondary">Belum mengumpulkan</span>
                            <?php elseif ($s['status'] === 'terlambat'): ?>
                                <span class="badge text-bg-warning">Terlambat</span>
                            <?php else: ?>
                                <span class="badge text-bg-success">Tepat waktu</span>
                            <?php endif; ?>

                            <?php if ($s['nilai'] !== null): ?>
                                <span class="badge text-bg-primary">Sudah dinilai</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($s['pengumpulan_id'] !== null): ?>
                                <a href="<?= e(url('/guru/tugas/pengumpulan/' . $s['pengumpulan_id'])) ?>">
                                    <?= e(mb_strimwidth((string) $s['file_asli'], 0, 24, '...')) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-body-secondary">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($s['pengumpulan_id'] === null): ?>
                                <span class="text-body-secondary small">
                                    Nilai bisa diisi setelah siswa mengumpulkan.
                                </span>
                            <?php else: ?>
                                <form method="post" action="<?= e(url('/guru/tugas/nilai')) ?>"
                                      class="row g-1 align-items-start">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="pengumpulan_id"
                                           value="<?= e($s['pengumpulan_id']) ?>">

                                    <div class="col-4 col-sm-3">
                                        <label class="visually-hidden"
                                               for="nilai<?= e($s['pengumpulan_id']) ?>">Nilai</label>
                                        <input type="number" class="form-control form-control-sm"
                                               id="nilai<?= e($s['pengumpulan_id']) ?>"
                                               name="nilai" min="0" max="100" step="0.01" required
                                               placeholder="0-100"
                                               value="<?= e(nilai_input($s['nilai'])) ?>">
                                    </div>

                                    <div class="col">
                                        <label class="visually-hidden"
                                               for="komentar<?= e($s['pengumpulan_id']) ?>">Komentar</label>
                                        <input type="text" class="form-control form-control-sm"
                                               id="komentar<?= e($s['pengumpulan_id']) ?>"
                                               name="komentar" maxlength="2000"
                                               placeholder="Komentar (opsional)"
                                               value="<?= e($s['komentar'] ?? '') ?>">
                                    </div>

                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-primary">Simpan</button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
