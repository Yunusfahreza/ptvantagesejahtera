<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 mb-1 text-white fw-bold">Kelola Jadwal Pelajaran</h1>
        <p class="text-white-50 small mb-0">Atur jadwal pertemuan mata pelajaran mingguan per kelas</p>
    </div>
    <button class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        <span>Tambah Jadwal</span>
    </button>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Hari</th>
                    <th>Waktu (Mulai - Selesai)</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru Pengampu</th>
                    <th>Ruangan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data === []): ?>
                    <tr><td colspan="7" class="text-center text-body-secondary py-5">
                        <div class="mb-2">📅</div>
                        Belum ada jadwal pelajaran yang diatur. Klik "Tambah Jadwal" untuk memulai.
                    </td></tr>
                <?php endif; ?>

                <?php foreach ($data as $j): ?>
                    <tr>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle text-capitalize px-2 py-1">
                                <?= e($j['hari']) ?>
                            </span>
                        </td>
                        <td class="fw-semibold text-white">
                            <?= e(substr((string) $j['jam_mulai'], 0, 5)) ?> &ndash; <?= e(substr((string) $j['jam_selesai'], 0, 5)) ?> WIB
                        </td>
                        <td><span class="badge text-bg-light border"><?= e($j['kelas_nama']) ?></span></td>
                        <td>
                            <div class="fw-semibold text-white"><?= e($j['mapel_nama']) ?></div>
                            <div class="small text-white-50"><?= e($j['mapel_kode']) ?></div>
                        </td>
                        <td><?= e($j['guru_nama']) ?></td>
                        <td><?= e($j['ruangan'] ?? '—') ?></td>
                        <td class="text-end">
                            <form method="post" action="<?= e(url('/admin/jadwal/hapus')) ?>" data-konfirmasi="Hapus jadwal ini?">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= e($j['id']) ?>">
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTambahJadwalLabel">Tambah Jadwal Pelajaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="post" action="<?= e(url('/admin/jadwal/simpan')) ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pengampu_id" class="form-label small fw-semibold">Guru Pengampu (Kelas & Mapel) <span class="text-danger">*</span></label>
                        <select class="form-select" id="pengampu_id" name="pengampu_id" required>
                            <option value="">-- Pilih Pengampu --</option>
                            <?php foreach ($pengampu as $p): ?>
                                <option value="<?= e($p['id']) ?>">
                                    <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?> (<?= e($p['guru_nama']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="hari" class="form-label small fw-semibold">Hari <span class="text-danger">*</span></label>
                        <select class="form-select" id="hari" name="hari" required>
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
                            <label for="jam_mulai" class="form-label small fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                        </div>
                        <div class="col-6">
                            <label for="jam_selesai" class="form-label small fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ruangan" class="form-label small fw-semibold">Ruangan / Laboratorium (Opsional)</label>
                        <input type="text" class="form-control" id="ruangan" name="ruangan" placeholder="Contoh: R. Teori 12 / Lab Komputer 2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
