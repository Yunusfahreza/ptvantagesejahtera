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
        <h1 class="h4 mb-3"><?= $adalahEdit ? 'Ubah Kuis' : 'Buat Kuis' ?></h1>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <?php if ($pengampu === []): ?>
                    <p class="mb-0 text-body-secondary">Anda belum punya kelas yang diampu.</p>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/guru/kuis/simpan')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= e($kuis['id'] ?? '0') ?>">

                        <div class="mb-3">
                            <label for="pengampu_id" class="form-label">Kelas dan mata pelajaran</label>
                            <select class="form-select" id="pengampu_id" name="pengampu_id" required>
                                <?php foreach ($pengampu as $p): ?>
                                    <option value="<?= e($p['id']) ?>"
                                        <?= (int) ($kuis['pengampu_id'] ?? 0) === (int) $p['id'] ? 'selected' : '' ?>>
                                        <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul kuis</label>
                            <input type="text" class="form-control" id="judul" name="judul" required
                                   maxlength="150" value="<?= e($kuis['judul'] ?? '') ?>">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-sm-4">
                                <label for="durasi_menit" class="form-label">Durasi (menit)</label>
                                <input type="number" class="form-control" id="durasi_menit"
                                       name="durasi_menit" min="1" max="<?= e($durasi_maks) ?>" required
                                       value="<?= e($kuis['durasi_menit'] ?? 30) ?>">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="mulai" class="form-label">Dibuka</label>
                                <input type="datetime-local" class="form-control" id="mulai"
                                       name="mulai" required value="<?= e($mulaiNilai) ?>">
                            </div>

                            <div class="col-12 col-sm-4">
                                <label for="selesai" class="form-label">Ditutup</label>
                                <input type="datetime-local" class="form-control" id="selesai"
                                       name="selesai" required value="<?= e($selesaiNilai) ?>">
                            </div>
                        </div>

                        <div class="alert alert-light border small">
                            Batas pengerjaan setiap siswa adalah yang lebih awal antara
                            <strong>waktu mulai siswa + durasi</strong> dan <strong>waktu ditutup</strong>.
                            Perhitungan dilakukan di server.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a class="btn btn-outline-secondary" href="<?= e(url('/guru/kuis')) ?>">Batal</a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
