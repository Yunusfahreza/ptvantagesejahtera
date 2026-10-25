<?php $huruf = ['a', 'b', 'c', 'd', 'e']; ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= e(url('/guru/kuis')) ?>">Kuis</a></li>
        <li class="breadcrumb-item active" aria-current="page">Soal</li>
    </ol>
</nav>

<h1 class="h4 mb-1"><?= e($kuis['judul']) ?></h1>
<p class="text-body-secondary">
    <?= e($kuis['kelas_nama']) ?> &middot; <?= e($kuis['mapel_nama']) ?>
    &middot; <?= e(count($data)) ?> soal &middot; durasi <?= e($kuis['durasi_menit']) ?> menit
</p>

<?php if ($terkunci): ?>
    <div class="alert alert-warning">
        Kuis ini sudah dikerjakan <?= e($kuis['jumlah_peserta']) ?> siswa,
        jadi soal tidak bisa ditambah, diubah, atau dihapus lagi.
    </div>
<?php endif; ?>

<div class="row g-3">
    <?php if (!$terkunci): ?>
        <div class="col-12 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent fw-semibold">
                    <?= $edit ? 'Ubah Soal' : 'Tambah Soal' ?>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= e(url('/guru/kuis/soal/simpan')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="kuis_id" value="<?= e($kuis['id']) ?>">
                        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="pertanyaan" class="form-label">Pertanyaan</label>
                            <textarea class="form-control" id="pertanyaan" name="pertanyaan" rows="3"
                                      maxlength="2000" required><?= e($edit['pertanyaan'] ?? '') ?></textarea>
                        </div>

                        <?php foreach ($huruf as $h): ?>
                            <div class="mb-2">
                                <label for="opsi_<?= e($h) ?>" class="form-label">
                                    Opsi <?= e(strtoupper($h)) ?>
                                    <?php if (in_array($h, ['a', 'b'], true)): ?>
                                        <span class="text-danger">*</span>
                                    <?php else: ?>
                                        <span class="text-body-secondary small">(boleh kosong)</span>
                                    <?php endif; ?>
                                </label>
                                <input type="text" class="form-control" id="opsi_<?= e($h) ?>"
                                       name="opsi_<?= e($h) ?>" maxlength="255"
                                       <?= in_array($h, ['a', 'b'], true) ? 'required' : '' ?>
                                       value="<?= e($edit['opsi_' . $h] ?? '') ?>">
                            </div>
                        <?php endforeach; ?>

                        <div class="mb-4 mt-3">
                            <label for="kunci" class="form-label">Kunci jawaban</label>
                            <select class="form-select" id="kunci" name="kunci" required>
                                <?php foreach ($huruf as $h): ?>
                                    <option value="<?= e($h) ?>"
                                        <?= ($edit['kunci'] ?? 'a') === $h ? 'selected' : '' ?>>
                                        <?= e(strtoupper($h)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Kunci harus menunjuk opsi yang terisi.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan' : 'Tambah' ?></button>
                            <?php if ($edit): ?>
                                <a class="btn btn-outline-secondary"
                                   href="<?= e(url('/guru/kuis/soal/' . $kuis['id'])) ?>">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-12 <?= $terkunci ? '' : 'col-lg-7' ?>">
        <?php if ($data === []): ?>
            <div class="alert alert-info">Belum ada soal. Tambahkan minimal satu soal.</div>
        <?php endif; ?>

        <?php foreach ($data as $i => $s): ?>
            <div class="card shadow-sm border-0 soal-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h2 class="h6 mb-0">Soal <?= e($i + 1) ?></h2>
                        <?php if (!$terkunci): ?>
                            <div class="d-inline-flex gap-1">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= e(url('/guru/kuis/soal/' . $kuis['id'] . '?edit=' . $s['id'])) ?>">Ubah</a>
                                <form method="post" action="<?= e(url('/guru/kuis/soal/hapus')) ?>"
                                      data-konfirmasi="Hapus soal ini?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="kuis_id" value="<?= e($kuis['id']) ?>">
                                    <input type="hidden" name="id" value="<?= e($s['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>

                    <p class="mb-2"><?= nl2br(e($s['pertanyaan'])) ?></p>

                    <ul class="list-unstyled mb-0 small">
                        <?php foreach ($huruf as $h): ?>
                            <?php if (($s['opsi_' . $h] ?? null) === null) { continue; } ?>
                            <li class="<?= $s['kunci'] === $h ? 'fw-semibold text-success' : '' ?>">
                                <?= e(strtoupper($h)) ?>. <?= e($s['opsi_' . $h]) ?>
                                <?php if ($s['kunci'] === $h): ?>
                                    <span class="badge text-bg-success">kunci</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
