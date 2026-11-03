<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Manajemen Jadwal</span>
            <span>&bull;</span>
            <span>Plotting Waktu Belajar</span>
        </div>
        <h1 class="page-title">Kelola Jadwal Pelajaran</h1>
        <p class="page-subtitle">Atur jadwal pertemuan mata pelajaran mingguan, alokasi jam belajar, dan ruang kelas</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="search-pill-wrapper d-none d-md-block" style="max-width: 250px;">
            <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="search-pill-input" placeholder="Cari jadwal..." data-instant-filter="#tabelJadwalAdmin">
        </div>
        <button class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tambah Jadwal Baru</span>
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 100px;">Hari</th>
                    <th>Waktu (Mulai &ndash; Selesai)</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru Pengampu</th>
                    <th>Ruangan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelJadwalAdmin">
                <?php if ($data === []): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5" style="color: var(--slate-muted);">
                            <div class="mb-2">ðŸ“…</div>
                            Belum ada jadwal pelajaran yang diatur. Klik "Tambah Jadwal Baru" untuk memulai.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $j): ?>
                    <tr>
                        <td>
                            <span class="badge px-2.5 py-1 fw-bold text-capitalize" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 6px; font-size: 0.72rem;">
                                <?= e($j['hari']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.88rem;">
                                <?= e(substr((string) $j['jam_mulai'], 0, 5)) ?> &ndash; <?= e(substr((string) $j['jam_selesai'], 0, 5)) ?> WIB
                            </span>
                        </td>
                        <td>
                            <span class="badge fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border-radius: 6px;">
                                Kelas <?= e($j['kelas_nama']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($j['mapel_nama']) ?></div>
                            <div class="small" style="color: var(--slate-muted); font-size: 0.74rem;"><?= e($j['mapel_kode']) ?></div>
                        </td>
                        <td>
                            <span class="small fw-semibold" style="color: var(--slate-secondary);"><?= e($j['guru_nama']) ?></span>
                        </td>
                        <td>
                            <span class="small fw-semibold" style="color: var(--accent-emerald);">
                                <?= e($j['ruangan'] ?? 'â€”') ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <form method="post" action="<?= e(url('/admin/jadwal/hapus')) ?>" data-konfirmasi="Hapus jadwal pelajaran ini?">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= e($j['id']) ?>">
                                <button class="btn btn-sm btn-outline-danger px-2.5 py-1">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold" id="modalTambahJadwalLabel" style="color: var(--slate-primary);">Tambah Jadwal Pelajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="post" action="<?= e(url('/admin/jadwal/simpan')) ?>">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="pengampu_id" class="form-label fw-bold">Guru Pengampu (Kelas & Mapel) <span class="text-danger">*</span></label>
                        <select class="form-select py-2" id="pengampu_id" name="pengampu_id" required>
                            <option value="">-- Pilih Guru Pengampu --</option>
                            <?php foreach ($pengampu as $p): ?>
                                <option value="<?= e($p['id']) ?>">
                                    Kelas <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?> (<?= e($p['guru_nama']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="hari" class="form-label fw-bold">Hari Pembelajaran <span class="text-danger">*</span></label>
                        <select class="form-select py-2" id="hari" name="hari" required>
                            <option value="senin">Senin</option>
                            <option value="selasa">Selasa</option>
                            <option value="rabu">Rabu</option>
                            <option value="kamis">Kamis</option>
                            <option value="jumat">Jumat</option>
                            <option value="sabtu">Sabtu</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="jam_mulai" class="form-label fw-bold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control py-2" id="jam_mulai" name="jam_mulai" required>
                        </div>
                        <div class="col-6">
                            <label for="jam_selesai" class="form-label fw-bold">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control py-2" id="jam_selesai" name="jam_selesai" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ruangan" class="form-label fw-bold">Ruangan / Laboratorium <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="text" class="form-control py-2" id="ruangan" name="ruangan" placeholder="Contoh: R. Teori 12 atau Lab Komputer">
                    </div>
                </div>
                <div class="modal-footer py-2.5">
                    <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
