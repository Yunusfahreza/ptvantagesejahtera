<?php
$adalahEdit = $tugas !== null;
$tenggatNilai = $adalahEdit
    ? date('Y-m-d\TH:i', strtotime((string) $tugas['tenggat']))
    : date('Y-m-d\TH:i', strtotime('+7 days 23:59'));
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-9 col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= e(url('/guru/tugas')) ?>">Daftar Tugas</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= $adalahEdit ? 'Edit Tugas' : 'Buat Baru' ?></li>
                    </ol>
                </nav>
                <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary);"><?= $adalahEdit ? 'Ubah Rincian Tugas' : 'Buat Penugasan Baru' ?></h1>
                <p class="small mb-0" style="color: var(--slate-tertiary);">Tentukan instruksi pengerjaan, batas akhir tenggat, dan dokumen lampiran</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/guru/tugas')) ?>">
                &larr; Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <?php if ($pengampu === []): ?>
                    <div class="alert alert-warning mb-0 border-0">
                        Anda belum memiliki kelas yang diampu.
                    </div>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/guru/tugas/simpan')) ?>"
                          enctype="multipart/form-data" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($tugas['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="pengampu_id" class="form-label fw-bold">Kelas & Mata Pelajaran <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="pengampu_id" name="pengampu_id" required>
                                <?php foreach ($pengampu as $p): ?>
                                    <option value="<?= e($p['id']) ?>"
                                        <?= (int) ($tugas['pengampu_id'] ?? 0) === (int) $p['id'] ? 'selected' : '' ?>>
                                        Kelas <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="judul" class="form-label fw-bold">Judul Tugas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2" id="judul" name="judul" required
                                   maxlength="150" value="<?= e($tugas['judul'] ?? '') ?>"
                                   placeholder="Contoh: Tugas Mandiri: Analisis Struktur Senyawa Hidrokarbon">
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-bold">Petunjuk & Instruksi Pengerjaan</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5"
                                      maxlength="5000" placeholder="Jelaskan format pengerjaan, ketentuan laporan, dan kriteria penilaian..."><?= e($tugas['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="tenggat" class="form-label fw-bold">Batas Waktu Tenggat <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control py-2" id="tenggat" name="tenggat"
                                   required value="<?= e($tenggatNilai) ?>">
                            <div class="form-text small" style="color: var(--slate-muted);">
                                Pengumpulan setelah waktu tenggat tetap diterima namun sistem otomatis menandai status 'Terlambat'.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="berkas" class="form-label fw-bold">
                                Berkas Lampiran Soal / Lembar Kerja <span class="text-muted fw-normal">(opsional)</span>
                            </label>
                            <input type="file" class="form-control" id="berkas" name="berkas">
                            <div class="d-flex justify-content-between align-items-center mt-1.5">
                                <span class="form-text small" style="color: var(--slate-muted); font-size: 0.74rem;">Format: <?= e($ekstensi) ?> &bull; Maksimal: <?= e($maks) ?></span>
                                <span class="small fw-semibold" data-nama-file></span>
                            </div>
                            <?php if ($adalahEdit && $tugas['lampiran'] !== null): ?>
                                <div class="mt-2 p-2 rounded-2 small" style="background: rgba(248, 250, 252, 0.9); border: 1px solid rgba(226, 232, 240, 0.8);">
                                    <span style="color: var(--slate-muted);">Lampiran saat ini:</span>
                                    <a class="fw-semibold text-decoration-none ms-1" style="color: var(--accent-sapphire);" 
                                       href="<?= e(url('/guru/tugas/lampiran/' . $tugas['id'])) ?>">
                                        <?= e($tugas['lampiran_asli']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <a class="btn btn-outline-secondary px-4 py-2" href="<?= e(url('/guru/tugas')) ?>">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                                <?= $adalahEdit ? 'Simpan Perubahan' : 'Terbitkan Tugas' ?>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
