<?php
$nilaiAda = array_values(array_filter(
    array_column($siswa, 'nilai'),
    static fn ($n) => $n !== null
));
$rata = $nilaiAda === [] ? null : array_sum(array_map('floatval', $nilaiAda)) / count($nilaiAda);
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= e(url('/guru/kuis')) ?>">Kuis</a></li>
        <li class="breadcrumb-item active" aria-current="page">Hasil</li>
    </ol>
</nav>

<h1 class="h4 mb-1"><?= e($kuis['judul']) ?></h1>
<p class="text-body-secondary">
    <?= e($kuis['kelas_nama']) ?> &middot; <?= e($kuis['mapel_nama']) ?>
    &middot; <?= e($kuis['jumlah_soal']) ?> soal
    &middot; rata-rata <strong><?= e(nilai_tampil($rata)) ?></strong>
</p>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Siswa</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th class="text-center">Dijawab</th>
                    <th class="text-center">Nilai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($siswa as $s): ?>
                    <tr>
                        <td class="fw-semibold"><?= e($s['siswa_nama']) ?></td>
                        <td class="small"><?= e(tanggal_id($s['mulai_at'])) ?></td>
                        <td class="small"><?= e(tanggal_id($s['selesai_at'])) ?></td>
                        <td class="text-center">
                            <?= $s['hasil_id'] === null ? '-' : e($s['jumlah_dijawab']) . ' / ' . e($kuis['jumlah_soal']) ?>
                        </td>
                        <td class="text-center fw-semibold"><?= e(nilai_tampil($s['nilai'])) ?></td>
                        <td>
                            <?php if ($s['hasil_id'] === null): ?>
                                <span class="badge text-bg-secondary">Belum mengerjakan</span>
                            <?php elseif ($s['selesai_at'] === null): ?>
                                <span class="badge text-bg-info">Sedang mengerjakan</span>
                            <?php else: ?>
                                <span class="badge text-bg-success">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
