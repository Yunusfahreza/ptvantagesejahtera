<div class="mb-4">
    <h1 class="h4 mb-1 text-white fw-bold">Presensi & Kehadiran Saya</h1>
    <p class="text-white-50 small mb-0">Pantau rekap kehadiran belajar Anda di seluruh mata pelajaran</p>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th class="text-center" style="width: 100px;">Hadir</th>
                    <th class="text-center" style="width: 100px;">Sakit</th>
                    <th class="text-center" style="width: 100px;">Izin</th>
                    <th class="text-center" style="width: 100px;">Alpha</th>
                    <th class="text-center" style="width: 130px;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rekap === []): ?>
                    <tr><td colspan="6" class="text-center text-body-secondary py-5">
                        <div class="mb-2">📋</div>
                        Belum ada data catatan presensi yang tercatat untuk Anda.
                    </td></tr>
                <?php endif; ?>

                <?php foreach ($rekap as $r): ?>
                    <?php
                    $total = (int) ($r['total'] ?? 0);
                    $hadir = (int) ($r['hadir'] ?? 0);
                    $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;
                    $badgePersen = match (true) {
                        $persen >= 85 => 'bg-success',
                        $persen >= 70 => 'bg-warning text-dark',
                        default       => 'bg-danger',
                    };
                    ?>
                    <tr>
                        <td>
                            <div class="fw-semibold text-white"><?= e($r['mapel_nama']) ?></div>
                            <div class="small text-white-50"><?= e($r['mapel_kode']) ?> &bull; Total <?= $total ?> Pertemuan</div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                <?= $hadir ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                <?= (int) ($r['sakit'] ?? 0) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                <?= (int) ($r['izin'] ?? 0) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                <?= (int) ($r['alpha'] ?? 0) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge <?= $badgePersen ?> px-2 py-1">
                                <?= $persen ?>%
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
