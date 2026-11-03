<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Manajemen Evaluasi</span>
            <span>&bull;</span>
            <span>Kuis & Ujian CBT</span>
        </div>
        <h1 class="page-title">Daftar Kuis & Asesmen</h1>
        <p class="page-subtitle">Rancang instrumen evaluasi pilihan ganda, atur batas durasi, dan pantau hasil pengerjaan siswa</p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="search-pill-wrapper d-none d-md-block" style="max-width: 250px;">
            <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="search-pill-input" placeholder="Cari kuis atau kelas..." data-instant-filter="#tabelKuisGuru">
        </div>
        <?php if ($pengampu !== []): ?>
            <a class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm" href="<?= e(url('/guru/kuis/form')) ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                <span>Buat Kuis Baru</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning border-0 shadow-sm mb-4">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun. Silakan hubungi admin sekolah.
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="min-width: 200px;">Judul Kuis</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Jendela Waktu Pelaksanaan</th>
                    <th class="text-center">Durasi</th>
                    <th class="text-center">Soal</th>
                    <th class="text-center">Peserta</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelKuisGuru">
                <?php if ($data === []): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5" style="color: var(--slate-muted);">
                            <div class="mb-2">âœ¨</div>
                            Belum ada agenda kuis yang dibuat.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $q): ?>
                    <?php
                    $sekarang = time();
                    $mulai    = strtotime((string) $q['mulai']);
                    $selesai  = strtotime((string) $q['selesai']);
                    ?>
                    <tr>
                        <td>
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($q['judul']) ?></div>
                        </td>
                        <td>
                            <span class="badge fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border-radius: 6px;">
                                <?= e($q['kelas_nama']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge px-2 py-1 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border-radius: 6px; font-size: 0.72rem;">
                                <?= e($q['mapel_kode']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="small fw-semibold" style="color: var(--slate-secondary); font-size: 0.74rem;">
                                <?= e(tanggal_id($q['mulai'])) ?> &ndash; <?= e(tanggal_id($q['selesai'])) ?>
                            </div>
                            <div class="mt-1">
                                <?php if ($sekarang < $mulai): ?>
                                    <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle px-2 py-0.5" style="font-size: 0.65rem;">Belum Dibuka</span>
                                <?php elseif ($sekarang <= $selesai): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.65rem;">Sedang Dibuka</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5" style="font-size: 0.65rem;">Ditutup</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="small fw-bold" style="color: var(--slate-secondary);"><?= e($q['durasi_menit']) ?> mnt</span>
                        </td>
                        <td class="text-center">
                            <?php if ((int) $q['jumlah_soal'] === 0): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5">0 Soal</span>
                            <?php else: ?>
                                <span class="badge px-2 py-1 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border-radius: 6px; font-size: 0.74rem;">
                                    <?= e($q['jumlah_soal']) ?> Butir
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="badge px-2.5 py-1 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border-radius: 6px; font-size: 0.74rem;">
                                <?= e($q['jumlah_peserta']) ?> Siswa
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap gap-1.5 justify-content-end">
                                <a class="btn btn-sm btn-primary px-3 py-1 fw-semibold shadow-sm"
                                   href="<?= e(url('/guru/kuis/soal/' . $q['id'])) ?>">Bank Soal</a>
                                <a class="btn btn-sm btn-outline-info px-2.5 py-1"
                                   href="<?= e(url('/guru/kuis/hasil/' . $q['id'])) ?>">Hasil</a>
                                <a class="btn btn-sm btn-outline-primary px-2.5 py-1"
                                   href="<?= e(url('/guru/kuis/form/' . $q['id'])) ?>">Ubah</a>
                                <form method="post" action="<?= e(url('/guru/kuis/hapus')) ?>"
                                      data-konfirmasi="Hapus kuis ini beserta seluruh butir soal dan rekap nilai siswa?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($q['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger px-2.5 py-1">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
