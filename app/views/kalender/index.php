<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Agenda & Waktu</span>
            <span>&bull;</span>
            <span>Kalender Sekolah</span>
        </div>
        <h1 class="page-title">Kalender Akademik SMAN 10 Garut</h1>
        <p class="page-subtitle">Tahun Ajaran Aktif: <strong style="color: var(--slate-primary);"><?= e($tahunAktif['nama']) ?></strong> &bull; Semester <?= e(ucfirst($tahunAktif['semester'])) ?></p>
    </div>
    <?php if ($peran === 'admin'): ?>
        <button class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAgenda">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tambah Agenda Sekolah</span>
        </button>
    <?php endif; ?>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Agenda Mendatang -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 5rem; z-index: 10;">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="squircle-icon squircle-icon-amber" style="width: 30px; height: 30px; border-radius: 8px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">Agenda Terdekat</span>
                </div>
                <span class="badge px-2 py-0.5" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-tertiary); font-size: 0.72rem; border-radius: 6px;">
                    <?= count($mendatang) ?> Kegiatan
                </span>
            </div>

            <div class="card-body p-3.5">
                <?php if ($mendatang === []): ?>
                    <div class="empty-state-box p-4 text-center">
                        <div class="empty-state-icon mx-auto" style="width: 44px; height: 44px; margin-bottom: 0.5rem;">â˜•</div>
                        <h6 class="fw-bold mb-1" style="color: var(--slate-primary);">Tidak Ada Agenda Terdekat</h6>
                        <p class="small text-muted mb-0">Belum ada agenda kegiatan penting dalam waktu dekat.</p>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2.5">
                        <?php foreach ($mendatang as $m): ?>
                            <?php
                            $badgeTipe = match ($m['tipe']) {
                                'ujian'    => 'bg-danger-subtle text-danger border border-danger-subtle',
                                'libur'    => 'bg-warning-subtle text-warning border border-warning-subtle',
                                'kegiatan' => 'bg-info-subtle text-info border border-info-subtle',
                                default    => 'bg-secondary-subtle text-muted border border-secondary-subtle',
                            };
                            ?>
                            <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.85); border: 1.5px solid rgba(226, 232, 240, 0.9); box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge <?= $badgeTipe ?> small text-capitalize px-2 py-1" style="font-size: 0.72rem;">
                                        <?= e($m['tipe']) ?>
                                    </span>
                                </div>
                                <div class="fw-bold mb-1" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($m['judul']) ?></div>
                                <div class="small fw-semibold mb-1 d-inline-flex align-items-center gap-1" style="color: var(--accent-sapphire); font-size: 0.76rem;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                                    </svg>
                                    <span><?= e(tanggal_id($m['tanggal_mulai'])) ?></span>
                                    <?php if ($m['tanggal_mulai'] !== $m['tanggal_selesai']): ?>
                                        <span>&ndash; <?= e(tanggal_id($m['tanggal_selesai'])) ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (($m['deskripsi'] ?? '') !== ''): ?>
                                    <div class="small mt-1" style="color: var(--slate-secondary); font-size: 0.78rem; line-height: 1.4;"><?= e($m['deskripsi']) ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Seluruh Jadwal Agenda -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="squircle-icon squircle-icon-blue" style="width: 30px; height: 30px; border-radius: 8px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        </svg>
                    </div>
                    <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">Agenda Pendidikan (<?= count($events) ?> Kegiatan Terjadwal)</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="min-width: 220px;">Nama Agenda / Kegiatan</th>
                            <th>Kategori</th>
                            <th>Periode Pelaksanaan</th>
                            <?php if ($peran === 'admin'): ?>
                                <th class="text-end">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($events === []): ?>
                            <tr>
                                <td colspan="<?= $peran === 'admin' ? 4 : 3 ?>" class="text-center py-5" style="color: var(--slate-muted);">
                                    Belum ada agenda kegiatan yang dicatat untuk tahun ajaran ini.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($events as $ev): ?>
                            <?php
                            $badgeTipe = match ($ev['tipe']) {
                                'ujian'    => 'bg-danger-subtle text-danger border border-danger-subtle',
                                'libur'    => 'bg-warning-subtle text-warning border border-warning-subtle',
                                'kegiatan' => 'bg-info-subtle text-info border border-info-subtle',
                                default    => 'bg-secondary-subtle text-muted border border-secondary-subtle',
                            };
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($ev['judul']) ?></div>
                                    <?php if (($ev['deskripsi'] ?? '') !== ''): ?>
                                        <div class="small" style="color: var(--slate-muted); font-size: 0.76rem;"><?= e($ev['deskripsi']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $badgeTipe ?> text-capitalize px-2.5 py-1" style="font-size: 0.72rem;">
                                        <?= e($ev['tipe']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small fw-semibold" style="color: var(--slate-secondary); font-size: 0.8rem;">
                                        <?= e(tanggal_id($ev['tanggal_mulai'])) ?>
                                    </div>
                                    <?php if ($ev['tanggal_mulai'] !== $ev['tanggal_selesai']): ?>
                                        <div class="small" style="color: var(--slate-muted); font-size: 0.72rem;">s/d <?= e(tanggal_id($ev['tanggal_selesai'])) ?></div>
                                    <?php endif; ?>
                                </td>
                                <?php if ($peran === 'admin'): ?>
                                    <td class="text-end">
                                        <form method="post" action="<?= e(url('/kalender/hapus')) ?>" data-konfirmasi="Hapus agenda ini?">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= e($ev['id']) ?>">
                                            <button class="btn btn-sm btn-outline-danger px-2.5 py-1">Hapus</button>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold" id="modalTambahAgendaLabel" style="color: var(--slate-primary);">Tambah Agenda Kalender Akademik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="post" action="<?= e(url('/kalender/simpan')) ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="tahun_ajaran_id" value="<?= e($tahunAktif['id']) ?>">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="judul" class="form-label fw-bold">Nama Kegiatan / Agenda <span class="text-danger">*</span></label>
                        <input type="text" class="form-control py-2" id="judul" name="judul" required placeholder="Contoh: Penilaian Akhir Semester (PAS) Ganjil">
                    </div>

                    <div class="mb-3">
                        <label for="tipe" class="form-label fw-bold">Jenis Agenda <span class="text-danger">*</span></label>
                        <select class="form-select py-2" id="tipe" name="tipe" required>
                            <option value="ujian">Ujian / Asesmen Sumatif</option>
                            <option value="libur">Hari Libur / Cuti Bersama</option>
                            <option value="kegiatan" selected>Kegiatan Sekolah / Ekstrakurikuler</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="tanggal_mulai" class="form-label fw-bold">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control py-2" id="tanggal_mulai" name="tanggal_mulai" required>
                        </div>
                        <div class="col-6">
                            <label for="tanggal_selesai" class="form-label fw-bold">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control py-2" id="tanggal_selesai" name="tanggal_selesai" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-bold">Keterangan / Rincian Acara</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="2" placeholder="Catatan teknis pelaksanaan agenda..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2.5">
                    <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Simpan Agenda</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
