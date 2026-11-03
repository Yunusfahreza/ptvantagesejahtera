<?php
$adalahEdit = $kuis !== null;
$mulaiNilai = $adalahEdit
    ? date('Y-m-d\TH:i', strtotime((string) $kuis['mulai']))
    : date('Y-m-d\TH:i', strtotime('+1 day 07:00'));
$selesaiNilai = $adalahEdit
    ? date('Y-m-d\TH:i', strtotime((string) $kuis['selesai']))
    : date('Y-m-d\TH:i', strtotime('+1 day 09:00'));
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-9 col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= e(url('/guru/kuis')) ?>">Kuis & Asesmen</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= $adalahEdit ? 'Edit Kuis' : 'Buat Baru' ?></li>
                    </ol>
                </nav>
                <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary);"><?= $adalahEdit ? 'Ubah Rincian Kuis' : 'Buat Kuis Baru' ?></h1>
                <p class="small mb-0" style="color: var(--slate-tertiary);">Tentukan durasi timer, periode jendela pengerjaan, dan rombel tujuan</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/guru/kuis')) ?>">
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
                    <form method="post" action="<?= e(url('/guru/kuis/simpan')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($kuis['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="pengampu_id" class="form-label fw-bold">Kelas & Mata Pelajaran <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="pengampu_id" name="pengampu_id" required>
                                <?php foreach ($pengampu as $p): ?>
                                    <option value="<?= e($p['id']) ?>"
                                        <?= (int) ($kuis['pengampu_id'] ?? 0) === (int) $p['id'] ? 'selected' : '' ?>>
                                        Kelas <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="judul" class="form-label fw-bold">Judul Kuis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control py-2" id="judul" name="judul" required
                                   maxlength="150" value="<?= e($kuis['judul'] ?? '') ?>"
                                   placeholder="Contoh: Kuis Harian: Struktur Atom & Tabel Periodik">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="durasi_menit" class="form-label fw-bold">Durasi Timer (Menit) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control py-2" id="durasi_menit"
                                       name="durasi_menit" min="1" max="<?= e($durasi_maks) ?>" required
                                       value="<?= e($kuis['durasi_menit'] ?? 30) ?>">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="mulai" class="form-label fw-bold">Waktu Dibuka <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control py-2" id="mulai"
                                       name="mulai" required value="<?= e($mulaiNilai) ?>">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="selesai" class="form-label fw-bold">Waktu Ditutup <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control py-2" id="selesai"
                                       name="selesai" required value="<?= e($selesaiNilai) ?>">
                            </div>
                        </div>

                        <div class="alert alert-info border-0 shadow-sm small mb-4" style="border-radius: var(--radius-md);">
                            Batas waktu pengerjaan setiap siswa dihitung otomatis dari waktu pertama siswa memulai + durasi, dan tidak dapat melebihi batas waktu kuis ditutup.
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <a class="btn btn-outline-secondary px-4 py-2" href="<?= e(url('/guru/kuis')) ?>">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                                <?= $adalahEdit ? 'Simpan Perubahan' : 'Simpan & Lanjut ke Soal' ?>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
