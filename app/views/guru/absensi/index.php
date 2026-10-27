<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 mb-1 text-white fw-bold">Presensi & Kehadiran Siswa</h1>
        <p class="text-white-50 small mb-0">Catat dan pantau kehadiran siswa per pertemuan kelas yang Anda ampu</p>
    </div>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning border-0 shadow-sm">
        Anda belum memiliki kelas pengampu.
    </div>
<?php else: ?>

<!-- Filter Pemilihan Kelas & Tanggal -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <form method="get" action="<?= e(url('/guru/absensi')) ?>" class="row g-2 align-items-center">
            <div class="col-md-5">
                <label class="form-label small text-white-50 mb-1">Mata Pelajaran & Kelas</label>
                <select name="pengampu" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($pengampu as $p): ?>
                        <option value="<?= e($p['id']) ?>" <?= ((int) ($terpilih['id'] ?? 0) === (int) $p['id']) ? 'selected' : '' ?>>
                            <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-white-50 mb-1">Tanggal Presensi</label>
                <input type="date" name="tanggal" class="form-control" value="<?= e($tanggal) ?>" onchange="this.form.submit()">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary w-100 mt-auto">Muat Presensi</button>
            </div>
        </form>
    </div>
</div>

<!-- Riwayat Tanggal yang Tercatat -->
<?php if ($tanggalAda !== []): ?>
    <div class="mb-3 d-flex flex-wrap gap-1 align-items-center">
        <span class="small text-white-50 me-2">Presensi Tercatat:</span>
        <?php foreach (array_slice($tanggalAda, 0, 7) as $tgl): ?>
            <a href="<?= e(url('/guru/absensi?pengampu=' . ($terpilih['id'] ?? '') . '&tanggal=' . $tgl)) ?>"
               class="badge text-decoration-none <?= $tgl === $tanggal ? 'bg-primary text-white' : 'bg-dark border text-white-50' ?>">
                <?= e(tanggal_id($tgl, 'd M Y')) ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Formulir Presensi Siswa -->
<form method="post" action="<?= e(url('/guru/absensi/simpan')) ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="pengampu_id" value="<?= e($terpilih['id'] ?? '') ?>">
    <input type="hidden" name="tanggal" value="<?= e($tanggal) ?>">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent py-3 border-bottom border-secondary-subtle d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <span class="fw-bold text-white"><?= e($terpilih['kelas_nama'] ?? '') ?> &bull; <?= e($terpilih['mapel_nama'] ?? '') ?></span>
                <span class="text-white-50 small ms-2">(<?= tanggal_id($tanggal, 'd M Y') ?>)</span>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-xs btn-outline-secondary" onclick="setSemua('hadir')">Semua Hadir</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Siswa</th>
                        <th style="width: 320px;">Status Kehadiran</th>
                        <th>Keterangan (Opsional)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($siswa === []): ?>
                        <tr><td colspan="4" class="text-center text-body-secondary py-4">
                            Belum ada siswa aktif terdaftar di kelas ini.
                        </td></tr>
                    <?php endif; ?>

                    <?php $no = 1; foreach ($siswa as $s): ?>
                        <?php $currStatus = $s['status'] ?? 'hadir'; ?>
                        <tr>
                            <td class="text-white-50 small"><?= $no++ ?></td>
                            <td>
                                <div class="fw-semibold text-white"><?= e($s['nama']) ?></div>
                                <div class="small text-white-50"><?= e($s['username']) ?></div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm w-100" role="group" aria-label="Status Kehadiran">
                                    <input type="radio" class="btn-check radio-status radio-hadir" name="status[<?= e($s['siswa_id']) ?>]"
                                           id="h_<?= e($s['siswa_id']) ?>" value="hadir" <?= $currStatus === 'hadir' ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-success" for="h_<?= e($s['siswa_id']) ?>">Hadir</label>

                                    <input type="radio" class="btn-check radio-status" name="status[<?= e($s['siswa_id']) ?>]"
                                           id="s_<?= e($s['siswa_id']) ?>" value="sakit" <?= $currStatus === 'sakit' ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-warning" for="s_<?= e($s['siswa_id']) ?>">Sakit</label>

                                    <input type="radio" class="btn-check radio-status" name="status[<?= e($s['siswa_id']) ?>]"
                                           id="i_<?= e($s['siswa_id']) ?>" value="izin" <?= $currStatus === 'izin' ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-info" for="i_<?= e($s['siswa_id']) ?>">Izin</label>

                                    <input type="radio" class="btn-check radio-status" name="status[<?= e($s['siswa_id']) ?>]"
                                           id="a_<?= e($s['siswa_id']) ?>" value="alpha" <?= $currStatus === 'alpha' ? 'checked' : '' ?>>
                                    <label class="btn btn-outline-danger" for="a_<?= e($s['siswa_id']) ?>">Alpha</label>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="keterangan[<?= e($s['siswa_id']) ?>]"
                                       value="<?= e($s['keterangan'] ?? '') ?>" placeholder="Catatan...">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($siswa !== []): ?>
            <div class="card-footer bg-transparent py-3 border-top border-secondary-subtle d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">
                    Simpan Presensi Kehadiran
                </button>
            </div>
        <?php endif; ?>
    </div>
</form>

<script>
function setSemua(status) {
    document.querySelectorAll('.radio-' + status).forEach(el => {
        el.checked = true;
    });
}
</script>

<?php endif; ?>
