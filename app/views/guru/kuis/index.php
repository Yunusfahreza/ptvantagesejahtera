<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="h4 mb-0">Kuis</h1>
    <?php if ($pengampu !== []): ?>
        <a class="btn btn-primary btn-sm" href="<?= e(url('/guru/kuis/form')) ?>">Buat Kuis</a>
    <?php endif; ?>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun.
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Jendela waktu</th>
                    <th class="text-center">Durasi</th>
                    <th class="text-center">Soal</th>
                    <th class="text-center">Peserta</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data === []): ?>
                    <tr><td colspan="8" class="text-center text-body-secondary py-4">
                        Belum ada kuis.
                    </td></tr>
                <?php endif; ?>

                <?php foreach ($data as $q): ?>
                    <?php
                    $sekarang = time();
                    $mulai    = strtotime((string) $q['mulai']);
                    $selesai  = strtotime((string) $q['selesai']);
                    ?>
                    <tr>
                        <td class="fw-semibold"><?= e($q['judul']) ?></td>
                        <td><?= e($q['kelas_nama']) ?></td>
                        <td><span class="badge text-bg-light border"><?= e($q['mapel_kode']) ?></span></td>
                        <td class="small">
                            <?= e(tanggal_id($q['mulai'])) ?><br>
                            s.d. <?= e(tanggal_id($q['selesai'])) ?><br>
                            <?php if ($sekarang < $mulai): ?>
                                <span class="badge text-bg-secondary">belum dibuka</span>
                            <?php elseif ($sekarang <= $selesai): ?>
                                <span class="badge text-bg-success">sedang dibuka</span>
                            <?php else: ?>
                                <span class="badge text-bg-dark">ditutup</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?= e($q['durasi_menit']) ?> mnt</td>
                        <td class="text-center">
                            <?php if ((int) $q['jumlah_soal'] === 0): ?>
                                <span class="badge text-bg-danger">0</span>
                            <?php else: ?>
                                <?= e($q['jumlah_soal']) ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?= e($q['jumlah_peserta']) ?></td>
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap gap-1 justify-content-end">
                                <a class="btn btn-sm btn-primary"
                                   href="<?= e(url('/guru/kuis/soal/' . $q['id'])) ?>">Soal</a>
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= e(url('/guru/kuis/hasil/' . $q['id'])) ?>">Hasil</a>
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= e(url('/guru/kuis/form/' . $q['id'])) ?>">Ubah</a>
                                <form method="post" action="<?= e(url('/guru/kuis/hapus')) ?>"
                                      data-konfirmasi="Hapus kuis ini beserta soalnya?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($q['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
