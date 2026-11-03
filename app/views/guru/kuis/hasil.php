<?php
$nilaiAda = array_values(array_filter(
    array_column($siswa, 'nilai'),
    static fn ($n) => $n !== null
));
$rata = $nilaiAda === [] ? null : array_sum(array_map('floatval', $nilaiAda)) / count($nilaiAda);
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb page-breadcrumb">
        <li class="breadcrumb-item"><a href="<?= e(url('/guru/kuis')) ?>">Kuis & Asesmen</a></li>
        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);">Rekapitulasi Hasil</li>
    </ol>
</nav>

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h1 class="page-title"><?= e($kuis['judul']) ?></h1>
        <p class="page-subtitle">
            Kelas <?= e($kuis['kelas_nama']) ?> &bull; <?= e($kuis['mapel_nama']) ?> &bull; <?= e($kuis['jumlah_soal']) ?> Butir Soal
        </p>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="p-2 px-3.5 rounded-pill" style="background: rgba(37, 99, 235, 0.08); border: 1.5px solid rgba(37, 99, 235, 0.25);">
            <span class="small" style="color: var(--slate-tertiary);">Rerata Kelas:</span>
            <span class="fw-bold fs-6 ms-1" style="color: var(--accent-sapphire); font-family: 'Space Grotesk', sans-serif;">
                <?= e(nilai_tampil($rata)) ?>
            </span>
        </div>
        <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/guru/kuis')) ?>">
            &larr; Kembali
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="min-width: 180px;">Nama Siswa</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th class="text-center">Dijawab</th>
                    <th class="text-center">Perolehan Nilai</th>
                    <th>Status Pengerjaan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($siswa as $s): ?>
                    <tr>
                        <td>
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($s['siswa_nama']) ?></div>
                        </td>
                        <td>
                            <div class="small fw-semibold" style="color: var(--slate-secondary); font-size: 0.74rem;">
                                <?= $s['mulai_at'] ? e(tanggal_id($s['mulai_at'])) : '<span class="text-muted">&mdash;</span>' ?>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-semibold" style="color: var(--slate-secondary); font-size: 0.74rem;">
                                <?= $s['selesai_at'] ? e(tanggal_id($s['selesai_at'])) : '<span class="text-muted">&mdash;</span>' ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge px-2 py-1 fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-secondary); border-radius: 6px; font-size: 0.74rem;">
                                <?= $s['hasil_id'] === null ? '&mdash;' : e($s['jumlah_dijawab']) . ' / ' . e($kuis['jumlah_soal']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if ($s['nilai'] !== null): ?>
                                <span class="fw-bold px-2.5 py-1 rounded-2" style="background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire); font-size: 0.92rem; font-family: 'Space Grotesk', sans-serif;">
                                    <?= e(nilai_tampil($s['nilai'])) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted small">&mdash;</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($s['hasil_id'] === null): ?>
                                <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle px-2 py-0.5" style="font-size: 0.72rem;">Belum Mengerjakan</span>
                            <?php elseif ($s['selesai_at'] === null): ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1" style="font-size: 0.72rem;">Sedang Mengerjakan</span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 0.72rem;">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
