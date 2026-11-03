<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Manajemen Evaluasi</span>
            <span>&bull;</span>
            <span>Penugasan Siswa</span>
        </div>
        <h1 class="page-title">Daftar Penugasan</h1>
        <p class="page-subtitle">Kelola instruksi tugas, pantau rekap pengumpulan siswa, dan input penilaian hasil kerja</p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <div class="search-pill-wrapper d-none d-md-block" style="max-width: 250px;">
            <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" class="search-pill-input" placeholder="Cari tugas atau kelas..." data-instant-filter="#tabelTugasGuru">
        </div>
        <?php if ($pengampu !== []): ?>
            <a class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 fw-semibold shadow-sm" href="<?= e(url('/guru/tugas/form')) ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                <span>Buat Tugas Baru</span>
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
                    <th style="min-width: 220px;">Judul Tugas</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Tenggat Waktu</th>
                    <th class="text-center">Terkumpul</th>
                    <th class="text-center">Perlu Dinilai</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelTugasGuru">
                <?php if ($data === []): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5" style="color: var(--slate-muted);">
                            <div class="mb-2">ðŸ“‹</div>
                            Belum ada tugas yang dibuat untuk kelas yang Anda ampu.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $t): ?>
                    <?php $lewat = strtotime((string) $t['tenggat']) < time(); ?>
                    <tr>
                        <td>
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($t['judul']) ?></div>
                        </td>
                        <td>
                            <span class="badge fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border-radius: 6px;">
                                <?= e($t['kelas_nama']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge px-2 py-1 fw-bold" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); border-radius: 6px; font-size: 0.72rem;">
                                <?= e($t['mapel_kode']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="small fw-semibold d-inline-flex align-items-center gap-1 <?= $lewat ? 'text-muted' : '' ?>" style="color: var(--slate-secondary);">
                                <?= e(tanggal_id($t['tenggat'])) ?>
                                <?php if ($lewat): ?>
                                    <span class="badge bg-secondary-subtle text-muted border px-1.5 py-0.5 ms-1" style="font-size: 0.65rem;">Lewat</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge px-2.5 py-1 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-secondary); border-radius: 6px; font-size: 0.76rem;">
                                <?= e($t['jumlah_kumpul']) ?> / <?= e($t['jumlah_siswa']) ?> Siswa
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if ((int) $t['belum_dinilai'] > 0): ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 fw-bold" style="font-size: 0.76rem;">
                                    <?= e($t['belum_dinilai']) ?> Menunggu
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.68rem;">Selesai</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap gap-1.5 justify-content-end">
                                <a class="btn btn-sm btn-primary px-3 py-1 fw-semibold shadow-sm"
                                   href="<?= e(url('/guru/tugas/nilai/' . $t['id'])) ?>">Periksa Nilai</a>
                                <a class="btn btn-sm btn-outline-primary px-2.5 py-1"
                                   href="<?= e(url('/guru/tugas/form/' . $t['id'])) ?>">Ubah</a>
                                <form method="post" action="<?= e(url('/guru/tugas/hapus')) ?>"
                                      data-konfirmasi="Hapus tugas ini beserta seluruh pengumpulan berkas siswa?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($t['id']) ?>">
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
