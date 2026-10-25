<?php
$sudahKumpul = $tugas['pengumpulan_id'] !== null;
$lewatTenggat = strtotime((string) $tugas['tenggat']) < time();
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= e(url('/siswa/tugas')) ?>">Tugas</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= e($tugas['judul']) ?></li>
    </ol>
</nav>

<div class="row g-3">
    <div class="col-12 col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h1 class="h5 mb-1"><?= e($tugas['judul']) ?></h1>
                <p class="small text-body-secondary">
                    <?= e($tugas['mapel_nama']) ?> &middot; <?= e($tugas['guru_nama']) ?>
                    &middot; tenggat <strong><?= e(tanggal_id($tugas['tenggat'])) ?></strong>
                    <?php if ($lewatTenggat): ?>
                        <span class="badge text-bg-danger">lewat tenggat</span>
                    <?php endif; ?>
                </p>

                <?php if (($tugas['deskripsi'] ?? '') !== ''): ?>
                    <p class="mb-3"><?= nl2br(e($tugas['deskripsi'])) ?></p>
                <?php endif; ?>

                <?php if ($tugas['lampiran'] !== null): ?>
                    <a class="btn btn-sm btn-outline-primary"
                       href="<?= e(url('/siswa/tugas/lampiran/' . $tugas['id'])) ?>">
                        Unduh lampiran: <?= e($tugas['lampiran_asli']) ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent fw-semibold">Pengumpulan saya</div>
            <div class="card-body">
                <?php if ($sudahKumpul): ?>
                    <dl class="row small mb-3">
                        <dt class="col-5">Status</dt>
                        <dd class="col-7">
                            <?php if ($tugas['status'] === 'terlambat'): ?>
                                <span class="badge text-bg-warning">Terlambat</span>
                            <?php else: ?>
                                <span class="badge text-bg-success">Tepat waktu</span>
                            <?php endif; ?>
                        </dd>

                        <dt class="col-5">Waktu kumpul</dt>
                        <dd class="col-7"><?= e(tanggal_id($tugas['dikumpulkan_at'])) ?></dd>

                        <dt class="col-5">Berkas</dt>
                        <dd class="col-7">
                            <a href="<?= e(url('/siswa/tugas/berkas/' . $tugas['id'])) ?>">
                                <?= e($tugas['pengumpulan_file_asli']) ?>
                            </a>
                        </dd>

                        <dt class="col-5">Nilai</dt>
                        <dd class="col-7 fw-semibold"><?= e(nilai_tampil($tugas['nilai'])) ?></dd>

                        <?php if (($tugas['komentar'] ?? '') !== ''): ?>
                            <dt class="col-5">Komentar guru</dt>
                            <dd class="col-7"><?= nl2br(e($tugas['komentar'])) ?></dd>
                        <?php endif; ?>
                    </dl>
                <?php else: ?>
                    <p class="small text-body-secondary">
                        Anda belum mengumpulkan tugas ini.
                        <?php if ($lewatTenggat): ?>
                            Pengumpulan sekarang akan ditandai <strong>terlambat</strong>, tapi tetap diterima.
                        <?php endif; ?>
                    </p>
                <?php endif; ?>

                <?php if ($terkunci): ?>
                    <div class="alert alert-info small mb-0">
                        Tugas sudah dinilai, pengumpulan terkunci dan tidak bisa diganti.
                    </div>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/siswa/tugas/kumpul')) ?>"
                          enctype="multipart/form-data"
                          <?= $sudahKumpul ? 'data-konfirmasi="Ganti berkas pengumpulan? Waktu dan status akan dihitung ulang."' : '' ?>>
                        <?= csrf_field() ?>
                        <input type="hidden" name="tugas_id" value="<?= e($tugas['id']) ?>">

                        <div class="mb-3">
                            <label for="berkas" class="form-label">
                                <?= $sudahKumpul ? 'Ganti berkas' : 'Berkas tugas' ?>
                            </label>
                            <input type="file" class="form-control" id="berkas" name="berkas" required>
                            <div class="form-text">Format: <?= e($ekstensi) ?>. Maksimal <?= e($maks) ?>.</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <?= $sudahKumpul ? 'Kumpulkan Ulang' : 'Kumpulkan' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
