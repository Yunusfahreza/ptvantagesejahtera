<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Tugas & Evaluasi</span>
            <span>&bull;</span>
            <span>Semester Ganjil</span>
        </div>
        <h1 class="page-title">Daftar Tugas</h1>
        <p class="page-subtitle">Pantau batas waktu pengumpulan, status pengerjaan, dan penilaian tugas belajar Anda</p>
    </div>

    <!-- Live Search Filter -->
    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari judul tugas atau mapel..." data-instant-filter="#tabelTugasBody">
    </div>
</div>

<?php if ($data === []): ?>
    <div class="empty-state-box">
        <div class="empty-state-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
            </svg>
        </div>
        <h3 class="h5 fw-bold mb-1" style="color: var(--slate-primary);">Belum Ada Tugas</h3>
        <p class="small text-muted mb-0">Semua tugas kelas Anda sudah terselesaikan atau belum ada tugas aktif dari guru.</p>
    </div>
<?php else: ?>
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 220px;">Judul Tugas</th>
                        <th>Mata Pelajaran</th>
                        <th>Tenggat Waktu</th>
                        <th>Status Pengumpulan</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelTugasBody">
                    <?php foreach ($data as $t): ?>
                        <?php
                        $lewatTenggat = strtotime((string) $t['tenggat']) < time();
                        $belumKumpul = ($t['pengumpulan_id'] === null);
                        ?>
                        <tr>
                            <td>
                                <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;">
                                    <?= e($t['judul']) ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge px-2.5 py-1.5 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border: 1px solid rgba(15, 23, 42, 0.1); border-radius: 8px;">
                                    <?= e($t['mapel_kode']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="small fw-semibold d-inline-flex align-items-center gap-1.5 <?= ($belumKumpul && $lewatTenggat) ? 'text-danger' : '' ?>" style="color: var(--slate-secondary);">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    <span><?= e(tanggal_id($t['tenggat'])) ?></span>
                                </div>
                            </td>
                            <td>
                                <?php if ($belumKumpul): ?>
                                    <?php if ($lewatTenggat): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1">
                                            Belum Kumpul (Lewat)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1">
                                            Menunggu Penyerahan
                                        </span>
                                    <?php endif; ?>
                                <?php elseif ($t['status'] === 'terlambat'): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1">
                                        Diserahkan Terlambat
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1">
                                        Tepat Waktu
                                    </span>
                                <?php endif; ?>

                                <?php if ($t['nilai'] !== null): ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 ms-1" style="font-size: 0.68rem;">
                                        Sudah Dinilai
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($t['nilai'] !== null): ?>
                                    <span class="fw-bold px-2.5 py-1 rounded-2" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); font-size: 0.92rem;">
                                        <?= e(nilai_tampil($t['nilai'])) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">&mdash;</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary px-3"
                                   href="<?= e(url('/siswa/tugas/detail/' . $t['id'])) ?>">
                                    Detail Tugas
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
