<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 mb-1 text-white fw-bold">Kalender Akademik Sekolah</h1>
        <p class="text-white-50 small mb-0">Tahun Ajaran Aktif: <strong class="text-white"><?= e($tahunAktif['nama']) ?></strong> &bull; SMAN 10 Garut</p>
    </div>
    <?php if ($peran === 'admin'): ?>
        <button class="btn btn-primary d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahAgenda">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tambah Agenda Sekolah</span>
        </button>
    <?php endif; ?>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Agenda Mendatang -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-transparent py-3 border-bottom border-secondary-subtle">
                <h6 class="mb-0 text-white fw-bold d-flex align-items-center gap-2">
                    <span>📌</span>
                    <span>Agenda Mendatang</span>
                </h6>
            </div>
            <div class="card-body p-3">
                <?php if ($mendatang === []): ?>
                    <div class="text-center text-white-50 py-4 small fst-italic">
                        Tidak ada agenda terdekat dalam waktu dekat.
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($mendatang as $m): ?>
                            <?php
                            $badgeTipe = match ($m['tipe']) {
                                'ujian'    => 'bg-danger-subtle text-danger border border-danger-subtle',
                                'libur'    => 'bg-warning-subtle text-warning border border-warning-subtle',
                                'kegiatan' => 'bg-info-subtle text-info border border-info-subtle',
                                default    => 'bg-secondary-subtle text-white-50 border border-secondary-subtle',
                            };
                            ?>
                            <div class="p-3 rounded bg-body-tertiary border border-secondary-subtle">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="badge <?= $badgeTipe ?> small text-capitalize px-2 py-1">
                                        <?= e($m['tipe']) ?>
                                    </span>
                                </div>
                                <div class="fw-bold text-white mb-1"><?= e($m['judul']) ?></div>
                                <div class="small text-info-emphasis mb-1">
                                    🗓️ <?= e(tanggal_id($m['tanggal_mulai'])) ?>
                                    <?php if ($m['tanggal_mulai'] !== $m['tanggal_selesai']): ?>
                                        &ndash; <?= e(tanggal_id($m['tanggal_selesai'])) ?>
                                    <?php endif; ?>
                                </div>
                                <?php if (($m['deskripsi'] ?? '') !== ''): ?>
                                    <div class="small text-white-50"><?= e($m['deskripsi']) ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Seluruh Jadwal Agenda -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent py-3 border-bottom border-secondary-subtle d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-white fw-bold d-flex align-items-center gap-2">
                    <span>📅</span>
                    <span>Seluruh Agenda Akademik (<?= count($events) ?> Kegiatan)</span>
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kegiatan / Agenda</th>
                            <th>Kategori</th>
                            <th>Periode Pelaksanaan</th>
                            <?php if ($peran === 'admin'): ?>
                                <th class="text-end">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($events === []): ?>
                            <tr><td colspan="<?= $peran === 'admin' ? 4 : 3 ?>" class="text-center text-body-secondary py-5">
                                Belum ada agenda kegiatan yang dicatat untuk tahun ajaran ini.
                            </td></tr>
                        <?php endif; ?>

                        <?php foreach ($events as $ev): ?>
                            <?php
                            $badgeTipe = match ($ev['tipe']) {
                                'ujian'    => 'bg-danger-subtle text-danger border border-danger-subtle',
                                'libur'    => 'bg-warning-subtle text-warning border border-warning-subtle',
                                'kegiatan' => 'bg-info-subtle text-info border border-info-subtle',
                                default    => 'bg-secondary-subtle text-white-50 border border-secondary-subtle',
                            };
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-semibold text-white"><?= e($ev['judul']) ?></div>
                                    <?php if (($ev['deskripsi'] ?? '') !== ''): ?>
                                        <div class="small text-white-50"><?= e($ev['deskripsi']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $badgeTipe ?> text-capitalize px-2 py-1">
                                        <?= e($ev['tipe']) ?>
                                    </span>
                                </td>
                                <td class="small text-white">
                                    <div><?= e(tanggal_id($ev['tanggal_mulai'])) ?></div>
                                    <?php if ($ev['tanggal_mulai'] !== $ev['tanggal_selesai']): ?>
                                        <div class="text-white-50">s/d <?= e(tanggal_id($ev['tanggal_selesai'])) ?></div>
                                    <?php endif; ?>
                                </td>
                                <?php if ($peran === 'admin'): ?>
                                    <td class="text-end">
                                        <form method="post" action="<?= e(url('/kalender/hapus')) ?>" data-konfirmasi="Hapus agenda ini?">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e($ev['id']) ?>">
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if ($peran === 'admin'): ?>
<!-- Modal Tambah Agenda -->
<div class="modal fade" id="modalTambahAgenda" tabindex="-1" aria-labelledby="modalTambahAgendaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTambahAgendaLabel">Tambah Agenda Kalender Akademik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="post" action="<?= e(url('/kalender/simpan')) ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="tahun_ajaran_id" value="<?= e($tahunAktif['id']) ?>">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul" class="form-label small fw-semibold">Nama Kegiatan / Agenda <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="judul" name="judul" required placeholder="Contoh: Penilaian Akhir Semester Ganjil">
                    </div>

                    <div class="mb-3">
                        <label for="tipe" class="form-label small fw-semibold">Jenis Agenda <span class="text-danger">*</span></label>
                        <select class="form-select" id="tipe" name="tipe" required>
                            <option value="ujian">Ujian / Asesmen</option>
                            <option value="libur">Hari Libur / Cuti Bersama</option>
                            <option value="kegiatan" selected>Kegiatan Sekolah / Ekstrakurikuler</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="tanggal_mulai" class="form-label small fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                        </div>
                        <div class="col-6">
                            <label for="tanggal_selesai" class="form-label small fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label small fw-semibold">Keterangan / Catatan</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Agenda</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
