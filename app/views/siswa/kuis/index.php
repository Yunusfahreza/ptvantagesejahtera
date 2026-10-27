<h1 class="h4 mb-3">Kuis</h1>

<?php if ($data === []): ?>
    <div class="alert alert-info">Belum ada kuis untuk kelas Anda.</div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($data as $q): ?>
        <?php
        $sudahSelesai   = $q['selesai_at'] !== null;
        $sedangKerjakan = $q['hasil_id'] !== null && !$sudahSelesai;
        $dibuka         = (int) $q['sedang_dibuka'] === 1;
        $belumDibuka    = (int) $q['belum_dibuka'] === 1;
        ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h2 class="h6 mb-0"><?= e($q['judul']) ?></h2>
                        <?php if ($sudahSelesai): ?>
                            <span class="badge text-bg-primary">selesai</span>
                        <?php elseif ($dibuka): ?>
                            <span class="badge text-bg-success">dibuka</span>
                        <?php elseif ($belumDibuka): ?>
                            <span class="badge text-bg-secondary">belum dibuka</span>
                        <?php else: ?>
                            <span class="badge text-bg-dark">ditutup</span>
                        <?php endif; ?>
                    </div>

                    <p class="small text-body-secondary mb-2">
                        <span class="badge text-bg-light border"><?= e($q['mapel_kode']) ?></span>
                        <?= e($q['mapel_nama']) ?>
                    </p>

                    <dl class="row small mb-0">
                        <dt class="col-5">Dibuka</dt>
                        <dd class="col-7"><?= e(tanggal_id($q['mulai'])) ?></dd>

                        <dt class="col-5">Ditutup</dt>
                        <dd class="col-7"><?= e(tanggal_id($q['selesai'])) ?></dd>

                        <dt class="col-5">Durasi</dt>
                        <dd class="col-7"><?= e($q['durasi_menit']) ?> menit</dd>

                        <dt class="col-5">Jumlah soal</dt>
                        <dd class="col-7"><?= e($q['jumlah_soal']) ?></dd>

                        <?php if ($sudahSelesai): ?>
                            <dt class="col-5">Nilai</dt>
                            <dd class="col-7 fw-semibold"><?= e(nilai_tampil($q['nilai'])) ?></dd>
                        <?php endif; ?>
                    </dl>
                </div>

                <div class="card-footer bg-transparent">
                    <?php if ($sudahSelesai): ?>
                        <a class="btn btn-sm btn-outline-primary w-100"
                           href="<?= e(url('/siswa/kuis/hasil/' . $q['id'])) ?>">Lihat Hasil</a>
                    <?php elseif ($sedangKerjakan && $dibuka): ?>
                        <a class="btn btn-sm btn-warning w-100"
                           href="<?= e(url('/siswa/kuis/kerjakan/' . $q['id'])) ?>">Lanjutkan</a>
                    <?php elseif ($sedangKerjakan): ?>
                        <a class="btn btn-sm btn-outline-primary w-100"
                           href="<?= e(url('/siswa/kuis/hasil/' . $q['id'])) ?>">Lihat Hasil</a>
                    <?php elseif ($dibuka && (int) $q['jumlah_soal'] > 0): ?>
                        <form method="post" action="<?= e(url('/siswa/kuis/mulai')) ?>"
                              data-konfirmasi="Mulai kuis sekarang? Anda hanya punya satu kesempatan dan waktu langsung berjalan.">
                            <?= csrf_field() ?>
                            <input type="hidden" name="kuis_id" value="<?= e($q['id']) ?>">
                            <button class="btn btn-sm btn-primary w-100">Mulai Kerjakan</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-sm btn-secondary w-100" disabled>
                            <?= (int) $q['jumlah_soal'] === 0 ? 'Belum ada soal' : 'Tidak tersedia' ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
