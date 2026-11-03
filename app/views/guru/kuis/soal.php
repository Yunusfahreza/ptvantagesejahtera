<?php $huruf = ['a', 'b', 'c', 'd', 'e']; ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="<?= e(url('/guru/kuis')) ?>">Kuis & Asesmen</a></li>
        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);">Kelola Butir Soal</li>
    </ol>
</nav>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1 class="page-title"><?= e($kuis['judul']) ?></h1>
        <p class="page-subtitle">
            Kelas <?= e($kuis['kelas_nama']) ?> &bull; <?= e($kuis['mapel_nama']) ?> &bull; Total: <strong><?= e(count($data)) ?> Butir Soal</strong> &bull; Durasi: <?= e($kuis['durasi_menit']) ?> Menit
        </p>
    </div>
    <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/guru/kuis')) ?>">
        &larr; Kembali ke Daftar
    </a>
</div>

<?php if ($terkunci): ?>
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-2 mb-4" style="border-radius: var(--radius-md);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
        <span>Kuis ini telah mulai dikerjakan oleh <?= e($kuis['jumlah_peserta']) ?> siswa. Penambahan atau perubahan butir soal dikunci secara otomatis oleh sistem.</span>
    </div>
<?php endif; ?>

<div class="row g-4">
    <?php if (!$terkunci): ?>
        <div class="col-12 col-lg-5">
            <div class="card shadow-sm border-0 sticky-top" style="top: 5rem; z-index: 10;">
                <div class="card-header py-3">
                    <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">
                        <?= $edit ? 'Edit Butir Soal' : 'Tambah Soal Baru' ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="<?= e(url('/guru/kuis/soal/simpan')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="kuis_id" value="<?= e($kuis['id']) ?>">
                        <input type="hidden" name="id" value="<?= e($edit['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="pertanyaan" class="form-label fw-bold">Teks Pertanyaan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="pertanyaan" name="pertanyaan" rows="3"
                                      maxlength="2000" required placeholder="Tuliskan butir soal pertanyaan..."><?= e($edit['pertanyaan'] ?? '') ?></textarea>
                        </div>

                        <?php foreach ($huruf as $h): ?>
                            <div class="mb-2.5">
                                <label for="opsi_<?= e($h) ?>" class="form-label fw-bold small mb-1" style="color: var(--slate-secondary);">
                                    Opsi <?= e(strtoupper($h)) ?>
                                    <?php if (in_array($h, ['a', 'b'], true)): ?>
                                        <span class="text-danger">*</span>
                                    <?php else: ?>
                                        <span class="text-muted fw-normal">(opsional)</span>
                                    <?php endif; ?>
                                </label>
                                <input type="text" class="form-control form-control-sm py-1.5" id="opsi_<?= e($h) ?>"
                                       name="opsi_<?= e($h) ?>" maxlength="255"
                                       <?= in_array($h, ['a', 'b'], true) ? 'required' : '' ?>
                                       value="<?= e($edit['opsi_' . $h] ?? '') ?>"
                                       placeholder="Teks pilihan <?= strtoupper($h) ?>">
                            </div>
                        <?php endforeach; ?>

                        <div class="mb-4 mt-3">
                            <label for="kunci" class="form-label fw-bold">Kunci Jawaban Benar <span class="text-danger">*</span></label>
                            <select class="form-select py-1.5" id="kunci" name="kunci" required>
                                <?php foreach ($huruf as $h): ?>
                                    <option value="<?= e($h) ?>"
                                        <?= ($edit['kunci'] ?? 'a') === $h ? 'selected' : '' ?>>
                                        Opsi <?= e(strtoupper($h)) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex gap-2 pt-2 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <button type="submit" class="btn btn-primary px-3 py-1.5 fw-bold shadow-sm">
                                <?= $edit ? 'Simpan Soal' : 'Tambahkan Butir Soal' ?>
                            </button>
                            <?php if ($edit): ?>
                                <a class="btn btn-outline-secondary px-3 py-1.5"
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
            <div class="empty-state-box">
                <div class="empty-state-icon">âœ¨</div>
                <h5 class="fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Butir Soal</h5>
                <p class="small text-muted mb-0">Silakan tambahkan minimal satu pertanyaan pilihan ganda pada form di samping.</p>
            </div>
        <?php endif; ?>

        <div class="d-flex flex-column gap-3">
            <?php foreach ($data as $i => $s): ?>
                <div class="card shadow-sm border-0 soal-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <span class="badge fw-bold px-2.5 py-1.5" style="background: var(--slate-primary); color: #ffffff; border-radius: 8px; font-size: 0.78rem;">
                                Butir Soal #<?= e($i + 1) ?>
                            </span>
                            <?php if (!$terkunci): ?>
                                <div class="d-inline-flex gap-1.5">
                                    <a class="btn btn-sm btn-outline-primary px-2.5 py-1"
                                       href="<?= e(url('/guru/kuis/soal/' . $kuis['id'] . '?edit=' . $s['id'])) ?>">Ubah</a>
                                    <form method="post" action="<?= e(url('/guru/kuis/soal/hapus')) ?>"
                                          data-konfirmasi="Hapus butir soal ini?">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="kuis_id" value="<?= e($kuis['id']) ?>">
                                        <input type="hidden" name="id" value="<?= e($s['id']) ?>">
                                        <button class="btn btn-sm btn-outline-danger px-2.5 py-1">Hapus</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="fw-semibold mb-3 mt-2" style="color: var(--slate-primary); font-size: 0.95rem; line-height: 1.55;">
                            <?= nl2br(e($s['pertanyaan'])) ?>
                        </div>

                        <div class="d-flex flex-column gap-1.5 ps-2">
                            <?php foreach ($huruf as $h): ?>
                                <?php if (($s['opsi_' . $h] ?? null) === null) { continue; } ?>
                                <?php $isKunci = ($s['kunci'] === $h); ?>
                                <div class="d-flex align-items-center gap-2 p-2 rounded-2 small" 
                                     style="background: <?= $isKunci ? 'rgba(5, 150, 105, 0.08)' : 'rgba(248, 250, 252, 0.7)' ?>; border: 1px solid <?= $isKunci ? 'rgba(5, 150, 105, 0.3)' : 'rgba(226, 232, 240, 0.7)' ?>;">
                                    <span class="badge rounded-circle p-1 fw-bold" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; background: <?= $isKunci ? 'var(--accent-emerald)' : 'rgba(15, 23, 42, 0.08)' ?>; color: <?= $isKunci ? '#ffffff' : 'var(--slate-primary)' ?>; font-size: 0.7rem;">
                                        <?= e(strtoupper($h)) ?>
                                    </span>
                                    <span style="color: <?= $isKunci ? 'var(--accent-emerald)' : 'var(--slate-secondary)' ?>; font-weight: <?= $isKunci ? '700' : '500' ?>;">
                                        <?= e($s['opsi_' . $h]) ?>
                                    </span>
                                    <?php if ($isKunci): ?>
                                        <span class="badge bg-success text-white ms-auto" style="font-size: 0.65rem;">Kunci Jawaban</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
