<nav aria-label="breadcrumb">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="<?= e(url('/guru/tugas')) ?>">Daftar Tugas</a></li>
        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);">Penilaian & Koreksi</li>
    </ol>
</nav>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1 class="page-title"><?= e($tugas['judul']) ?></h1>
        <p class="page-subtitle">
            Kelas <?= e($tugas['kelas_nama']) ?> &bull; <?= e($tugas['mapel_nama']) ?> &bull; Tenggat: <strong><?= e(tanggal_id($tugas['tenggat'])) ?></strong>
        </p>
    </div>

    <!-- Quick search student -->
    <div class="search-pill-wrapper">
        <svg class="search-pill-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" class="search-pill-input" placeholder="Cari nama siswa..." data-instant-filter="#tabelNilaiSiswaBody">
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="min-width: 180px;">Nama Siswa</th>
                    <th>Waktu Serah</th>
                    <th>Status Pengumpulan</th>
                    <th>Berkas Siswa</th>
                    <th style="min-width: 24rem;">Input Nilai & Umpan Balik Guru</th>
                </tr>
            </thead>
            <tbody id="tabelNilaiSiswaBody">
                <?php foreach ($siswa as $s): ?>
                    <tr>
                        <td>
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($s['siswa_nama']) ?></div>
                        </td>
                        <td>
                            <div class="small fw-semibold" style="color: var(--slate-secondary); font-size: 0.74rem;">
                                <?= $s['dikumpulkan_at'] ? e(tanggal_id($s['dikumpulkan_at'])) : '<span class="text-muted">&mdash;</span>' ?>
                            </div>
                        </td>
                        <td>
                            <?php if ($s['pengumpulan_id'] === null): ?>
                                <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle px-2 py-0.5" style="font-size: 0.72rem;">Belum Kumpul</span>
                            <?php elseif ($s['status'] === 'terlambat'): ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1" style="font-size: 0.72rem;">Terlambat</span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 0.72rem;">Tepat Waktu</span>
                            <?php endif; ?>

                            <?php if ($s['nilai'] !== null): ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 ms-1" style="font-size: 0.68rem;">Dinilai</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($s['pengumpulan_id'] !== null): ?>
                                <a class="small fw-bold text-decoration-none d-inline-flex align-items-center gap-1" style="color: var(--accent-sapphire);" 
                                   href="<?= e(url('/guru/tugas/pengumpulan/' . $s['pengumpulan_id'])) ?>">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    <span><?= e(mb_strimwidth((string) $s['file_asli'], 0, 22, '...')) ?></span>
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">&mdash;</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($s['pengumpulan_id'] === null): ?>
                                <span class="text-muted small fst-italic">
                                    Form nilai aktif setelah siswa mengumpulkan.
                                </span>
                            <?php else: ?>
                                <form method="post" action="<?= e(url('/guru/tugas/nilai')) ?>" class="row g-2 align-items-center">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="pengumpulan_id" value="<?= e($s['pengumpulan_id']) ?>">

                                    <div class="col-4 col-sm-3">
                                        <input type="number" class="form-control form-control-sm py-1.5 fw-bold"
                                               id="nilai<?= e($s['pengumpulan_id']) ?>"
                                               name="nilai" min="0" max="100" step="0.01" required
                                               placeholder="0-100"
                                               value="<?= e(nilai_input($s['nilai'])) ?>">
                                    </div>

                                    <div class="col">
                                        <input type="text" class="form-control form-control-sm py-1.5"
                                               id="komentar<?= e($s['pengumpulan_id']) ?>"
                                               name="komentar" maxlength="2000"
                                               placeholder="Komentar / masukan guru..."
                                               value="<?= e($s['komentar'] ?? '') ?>">
                                    </div>

                                    <div class="col-auto">
                                        <button class="btn btn-sm btn-primary px-3 py-1.5 fw-semibold shadow-sm">Simpan</button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
