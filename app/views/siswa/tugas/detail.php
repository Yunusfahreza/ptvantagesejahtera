<?php
$sudahKumpul = $tugas['pengumpulan_id'] !== null;
$lewatTenggat = strtotime((string) $tugas['tenggat']) < time();
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="<?= e(url('/siswa/tugas')) ?>">Daftar Tugas</a></li>
        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= e($tugas['judul']) ?></li>
    </ol>
</nav>

<div class="row g-4">
    <!-- Kolom Kiri: Rincian & Petunjuk Tugas -->
    <div class="col-12 col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border: 1px solid rgba(15, 23, 42, 0.1); border-radius: 8px;">
                            <?= e($tugas['mapel_nama']) ?>
                        </span>
                        
                        <?php if ($lewatTenggat): ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1">
                                Batas Waktu Terlewat
                            </span>
                        <?php else: ?>
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1">
                                Aktif
                            </span>
                        <?php endif; ?>
                    </div>

                    <h1 class="h4 fw-bold mb-2" style="color: var(--slate-primary); letter-spacing: -0.02em;">
                        <?= e($tugas['judul']) ?>
                    </h1>

                    <div class="d-flex flex-wrap align-items-center gap-3 small mb-4 pb-3 border-bottom" style="color: var(--slate-tertiary); border-color: rgba(226, 232, 240, 0.8) !important;">
                        <div>
                            Guru: <span class="fw-semibold" style="color: var(--slate-secondary);"><?= e($tugas['guru_nama']) ?></span>
                        </div>
                        <div>&bull;</div>
                        <div class="d-inline-flex align-items-center gap-1.5">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span>Tenggat: <strong style="color: var(--slate-primary);"><?= e(tanggal_id($tugas['tenggat'])) ?></strong></span>
                        </div>
                    </div>

                    <?php if (($tugas['deskripsi'] ?? '') !== ''): ?>
                        <div class="mb-4">
                            <h6 class="small fw-bold text-uppercase tracking-wider mb-2" style="color: var(--slate-muted); font-size: 0.72rem;">Instruksi Pengerjaan:</h6>
                            <div class="p-3.5 rounded-3" style="background: rgba(248, 250, 252, 0.8); border: 1px solid rgba(226, 232, 240, 0.8); line-height: 1.6; color: var(--slate-secondary);">
                                <?= nl2br(e($tugas['deskripsi'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($tugas['lampiran'] !== null): ?>
                    <div class="pt-3 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <a class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2 px-3 py-2"
                           href="<?= e(url('/siswa/tugas/lampiran/' . $tugas['id'])) ?>">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                            </svg>
                            <span>Unduh Lampiran: <strong><?= e($tugas['lampiran_asli']) ?></strong></span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Pengumpulan Siswa & Lembar Nilai -->
    <div class="col-12 col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="squircle-icon squircle-icon-blue" style="width: 32px; height: 32px; border-radius: 8px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">Lembar Pengumpulan Saya</span>
                </div>
            </div>

            <div class="card-body p-4">
                <?php if ($sudahKumpul): ?>
                    <div class="p-3 rounded-3 mb-4" style="background: rgba(248, 250, 252, 0.85); border: 1px solid rgba(226, 232, 240, 0.9);">
                        <div class="d-flex justify-content-between align-items-center mb-2.5 pb-2 border-bottom" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <span class="small" style="color: var(--slate-tertiary);">Status Penyerahan</span>
                            <?php if ($tugas['status'] === 'terlambat'): ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1">
                                    Diserahkan Terlambat
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                    Tepat Waktu
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2.5 pb-2 border-bottom" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <span class="small" style="color: var(--slate-tertiary);">Waktu Penyerahan</span>
                            <span class="small fw-semibold" style="color: var(--slate-secondary);"><?= e(tanggal_id($tugas['dikumpulkan_at'])) ?></span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2.5 pb-2 border-bottom" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                            <span class="small" style="color: var(--slate-tertiary);">Berkas Terunggah</span>
                            <a class="small fw-bold text-decoration-none d-inline-flex align-items-center gap-1" style="color: var(--accent-sapphire);" 
                               href="<?= e(url('/siswa/tugas/berkas/' . $tugas['id'])) ?>">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                <span><?= e($tugas['pengumpulan_file_asli']) ?></span>
                            </a>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small" style="color: var(--slate-tertiary);">Nilai dari Guru</span>
                            <?php if ($tugas['nilai'] !== null): ?>
                                <span class="badge px-3 py-1.5 fs-6 fw-bold" style="background: rgba(37, 99, 235, 0.1); color: var(--accent-sapphire); border: 1.5px solid rgba(37, 99, 235, 0.25); border-radius: 8px;">
                                    <?= e(nilai_tampil($tugas['nilai'])) ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-muted border px-2 py-1">Menunggu Penilaian</span>
                            <?php endif; ?>
                        </div>

                        <?php if (($tugas['komentar'] ?? '') !== ''): ?>
                            <div class="mt-3 pt-2 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                                <div class="small fw-bold mb-1" style="color: var(--slate-secondary);">Catatan / Masukan Guru:</div>
                                <div class="small fst-italic p-2 rounded" style="background: #ffffff; color: var(--slate-secondary); border: 1px solid rgba(226, 232, 240, 0.8);">
                                    <?= nl2br(e($tugas['komentar'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state-box p-4 mb-4 text-center">
                        <div class="empty-state-icon mx-auto" style="width: 48px; height: 48px; margin-bottom: 0.75rem;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Berkas Terkumpul</h6>
                        <p class="small text-muted mb-0">
                            Silakan unggah dokumen tugas Anda sebelum batas waktu berakhir.
                            <?php if ($lewatTenggat): ?>
                                <br><span class="text-danger fw-semibold">Catatan: Karena tenggat telah lewat, tugas akan ditandai terlambat.</span>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($terkunci): ?>
                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center gap-2 small mb-0" style="border-radius: var(--radius-md);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <span>Tugas telah dinilai oleh guru pengampu. Sesi pengumpulan terkunci.</span>
                    </div>
                <?php else: ?>
                    <form method="post" action="<?= e(url('/siswa/tugas/kumpul')) ?>"
                          enctype="multipart/form-data"
                          <?= $sudahKumpul ? 'data-konfirmasi="Ganti berkas pengumpulan? Waktu dan status penyerahan akan diperbarui ulang."' : '' ?>>
                        <?= csrf_field() ?>
                        <input type="hidden" name="tugas_id" value="<?= e($tugas['id']) ?>">

                        <div class="mb-3">
                            <label for="berkas" class="form-label fw-bold">
                                <?= $sudahKumpul ? 'Unggah Berkas Pengganti' : 'Pilih Berkas Tugas' ?>
                            </label>
                            <input type="file" class="form-control" id="berkas" name="berkas" required>
                            <div class="d-flex justify-content-between align-items-center mt-1.5">
                                <span class="form-text small" style="color: var(--slate-muted); font-size: 0.74rem;">Format: <?= e($ekstensi) ?> &bull; Maks: <?= e($maks) ?></span>
                                <span class="small fw-semibold" data-nama-file></span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm">
                            <?= $sudahKumpul ? 'Kirim Pembaruan Berkas' : 'Kumpulkan Tugas Sekarang' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
