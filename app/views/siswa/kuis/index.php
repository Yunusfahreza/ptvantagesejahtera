<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Ujian & Asesmen</span>
            <span>&bull;</span>
            <span>Semester Ganjil</span>
        </div>
        <h1 class="page-title">Kuis & Ujian Online</h1>
        <p class="page-subtitle">Daftar evaluasi pemahaman, kuis berkala, dan asesmen harian Anda</p>
    </div>

    <!-- Live Search Filter -->
    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari judul kuis atau mapel..." data-instant-filter="#daftarKuisGrid">
    </div>
</div>

<?php if ($data === []): ?>
    <div class="empty-state-box">
        <div class="empty-state-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Kuis Aktif</h3>
        <p class="small text-muted mb-0">Tidak ada agenda kuis atau ujian online yang dijadwalkan untuk kelas Anda saat ini.</p>
    </div>
<?php else: ?>
    <div class="row g-3" id="daftarKuisGrid">
        <?php foreach ($data as $q): ?>
            <?php
            $sudahSelesai   = $q['selesai_at'] !== null;
            $sedangKerjakan = $q['hasil_id'] !== null && !$sudahSelesai;
            $dibuka         = (int) $q['sedang_dibuka'] === 1;
            $belumDibuka    = (int) $q['belum_dibuka'] === 1;
            ?>
            <div class="col-12 col-md-6 col-xl-4 filterable-item">
                <div class="card h-100 shadow-sm border-0 d-flex flex-column justify-content-between">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                            <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border: 1px solid rgba(15, 23, 42, 0.1); border-radius: 8px;">
                                <?= e($q['mapel_kode']) ?> &bull; <?= e($q['mapel_nama']) ?>
                            </span>

                            <?php if ($sudahSelesai): ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">
                                    Selesai
                                </span>
                            <?php elseif ($dibuka): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                    Sedang Dibuka
                                </span>
                            <?php elseif ($belumDibuka): ?>
                                <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle px-2.5 py-1">
                                    Belum Dibuka
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1">
                                    Sudah Ditutup
                                </span>
                            <?php endif; ?>
                        </div>

                        <h2 class="h5 fw-bold mb-3" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                            <?= e($q['judul']) ?>
                        </h2>

                        <div class="p-3 rounded-3 mb-3" style="background: rgba(248, 250, 252, 0.85); border: 1px solid rgba(226, 232, 240, 0.9);">
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-1.5 border-bottom" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                                <span class="small" style="color: var(--slate-tertiary);">Periode Buka</span>
                                <span class="small fw-semibold" style="color: var(--slate-secondary);"><?= e(tanggal_id($q['mulai'])) ?></span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2 pb-1.5 border-bottom" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                                <span class="small" style="color: var(--slate-tertiary);">Batas Akhir</span>
                                <span class="small fw-semibold" style="color: var(--slate-secondary);"><?= e(tanggal_id($q['selesai'])) ?></span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2 pb-1.5 border-bottom" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                                <span class="small" style="color: var(--slate-tertiary);">Durasi & Soal</span>
                                <span class="small fw-bold" style="color: var(--slate-primary);"><?= e($q['durasi_menit']) ?> Menit &bull; <?= e($q['jumlah_soal']) ?> Butir</span>
                            </div>

                            <?php if ($sudahSelesai): ?>
                                <div class="d-flex justify-content-between align-items-center pt-1">
                                    <span class="small fw-bold" style="color: var(--slate-primary);">Perolehan Nilai</span>
                                    <span class="badge px-2.5 py-1 fw-bold fs-6" style="background: rgba(37, 99, 235, 0.1); color: var(--accent-sapphire); border: 1px solid rgba(37, 99, 235, 0.25); border-radius: 6px;">
                                        <?= e(nilai_tampil($q['nilai'])) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-footer p-3 bg-transparent border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <?php if ($sudahSelesai): ?>
                            <a class="btn btn-sm btn-outline-primary w-100 py-2 fw-semibold"
                               href="<?= e(url('/siswa/kuis/hasil/' . $q['id'])) ?>">
                                Lihat Analisis Hasil
                            </a>
                        <?php elseif ($sedangKerjakan && $dibuka): ?>
                            <a class="btn btn-sm btn-warning w-100 py-2 fw-bold shadow-sm"
                               href="<?= e(url('/siswa/kuis/kerjakan/' . $q['id'])) ?>">
                                Lanjutkan Pengerjaan
                            </a>
                        <?php elseif ($sedangKerjakan): ?>
                            <a class="btn btn-sm btn-outline-primary w-100 py-2 fw-semibold"
                               href="<?= e(url('/siswa/kuis/hasil/' . $q['id'])) ?>">
                                Lihat Hasil
                            </a>
                        <?php elseif ($dibuka && (int) $q['jumlah_soal'] > 0): ?>
                            <form method="post" action="<?= e(url('/siswa/kuis/mulai')) ?>"
                                  data-konfirmasi="Mulai kuis sekarang? Anda hanya punya satu kesempatan dan waktu langsung berjalan.">
                                <?= csrf_field() ?>
                                <input type="hidden" name="kuis_id" value="<?= e($q['id']) ?>">
                                <button class="btn btn-sm btn-primary w-100 py-2 fw-bold shadow-sm">
                                    Mulai Kerjakan Sekarang
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-sm btn-secondary w-100 py-2" disabled>
                                <?= (int) $q['jumlah_soal'] === 0 ? 'Belum Ada Soal' : 'Kuis Tidak Tersedia' ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
