<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Manajemen Kelas</span>
            <span>&bull;</span>
            <span>Presensi Harian</span>
        </div>
        <h1 class="page-title">Presensi & Kehadiran Siswa</h1>
        <p class="page-subtitle">Pencatatan daftar hadir siswa per sesi tatap muka dan pembelajaran daring</p>
    </div>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        Anda belum memiliki rombongan belajar yang diampu. Silakan hubungi admin kurikulum.
    </div>
<?php else: ?>

<!-- Filter Pemilihan Kelas & Tanggal -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <form method="get" action="<?= e(url('/guru/absensi')) ?>" class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label fw-bold">Mata Pelajaran & Rombel</label>
                <select name="pengampu" class="form-select py-2" onchange="this.form.submit()">
                    <?php foreach ($pengampu as $p): ?>
                        <option value="<?= e($p['id']) ?>" <?= ((int) ($terpilih['id'] ?? 0) === (int) $p['id']) ? 'selected' : '' ?>>
                            Kelas <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label fw-bold">Tanggal Pertemuan</label>
                <input type="date" name="tanggal" class="form-control py-2" value="<?= e($tanggal) ?>" onchange="this.form.submit()">
            </div>
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">Muat Lembar Presensi</button>
            </div>
        </form>
    </div>
</div>

<!-- Riwayat Tanggal yang Tercatat -->
<?php if ($tanggalAda !== []): ?>
    <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
        <span class="small fw-bold" style="color: var(--slate-tertiary);">Riwayat Tercatat:</span>
        <?php foreach (array_slice($tanggalAda, 0, 7) as $tgl): ?>
            <?php $isActiveTgl = ($tgl === $tanggal); ?>
            <a href="<?= e(url('/guru/absensi?pengampu=' . ($terpilih['id'] ?? '') . '&tanggal=' . $tgl)) ?>"
               class="badge text-decoration-none px-2.5 py-1.5 <?= $isActiveTgl ? 'bg-primary text-white' : '' ?>"
               style="<?= !$isActiveTgl ? 'background: rgba(15, 23, 42, 0.05); color: var(--slate-secondary); border: 1px solid rgba(15, 23, 42, 0.1);' : '' ?>; border-radius: 6px; font-size: 0.74rem;">
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
        <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.95rem;">
                    Kelas <?= e($terpilih['kelas_nama'] ?? '') ?> &bull; <?= e($terpilih['mapel_nama'] ?? '') ?>
                </span>
                <span class="small fw-semibold ms-2" style="color: var(--slate-tertiary);">(<?= tanggal_id($tanggal, 'd M Y') ?>)</span>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-success px-3 py-1 fw-bold" onclick="setSemua('hadir')">
                    âœ“ Tandai Semua Hadir
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="min-width: 180px;">Nama Siswa</th>
                        <th style="min-width: 280px; width: 320px;">Status Kehadiran</th>
                        <th>Keterangan / Catatan Tambahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($siswa === []): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5" style="color: var(--slate-muted);">
                                Belum ada siswa aktif terdaftar di rombel kelas ini.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php $no = 1; foreach ($siswa as $s): ?>
                        <?php $currStatus = $s['status'] ?? 'hadir'; ?>
                        <tr>
                            <td class="text-muted small"><?= $no++ ?></td>
                            <td>
                                <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($s['nama']) ?></div>
                                <div class="small" style="color: var(--slate-muted); font-size: 0.74rem;"><?= e($s['username']) ?></div>
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
                                <input type="text" class="form-control form-control-sm py-1.5" name="keterangan[<?= e($s['siswa_id']) ?>]"
                                       value="<?= e($s['keterangan'] ?? '') ?>" placeholder="Catatan khusus...">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($siswa !== []): ?>
            <div class="card-footer bg-transparent py-3 border-top d-flex justify-content-end" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                    Simpan Lembar Presensi
                </button>
            </div>
        <?php endif; ?>
    </div>
</form>

<script>
function setSemua(status) {
    document.querySelectorAll('.radio-' + status).forEach(function (el) {
        el.checked = true;
    });
}
</script>
<?php endif; ?>
