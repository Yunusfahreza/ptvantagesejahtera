<div class="mb-4">
    <h1 class="h4 mb-1 text-white fw-bold">Jadwal Pelajaran Kelas</h1>
    <p class="text-white-50 small mb-0">Rangkaian jadwal belajar mata pelajaran kelas Anda setiap hari</p>
</div>

<?php
$daftarHari = [
    'senin'  => 'Senin',
    'selasa' => 'Selasa',
    'rabu'   => 'Rabu',
    'kamis'  => 'Kamis',
    'jumat'  => 'Jumat',
    'sabtu'  => 'Sabtu',
];
$hariIni = match ((int) date('N')) {
    1 => 'senin',
    2 => 'selasa',
    3 => 'rabu',
    4 => 'kamis',
    5 => 'jumat',
    6 => 'sabtu',
    default => 'minggu',
};
?>

<div class="row g-4">
    <?php foreach ($daftarHari as $kodeHari => $namaHari): ?>
        <?php
        $list = $jadwal[$kodeHari] ?? [];
        $isHariIni = ($kodeHari === $hariIni);
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 <?= $isHariIni ? 'border border-primary' : '' ?>">
                <div class="card-header bg-transparent py-3 border-bottom border-secondary-subtle d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-white fw-bold d-flex align-items-center gap-2">
                        <span><?= e($namaHari) ?></span>
                        <?php if ($isHariIni): ?>
                            <span class="badge bg-primary text-white" style="font-size: 0.7rem;">Hari Ini</span>
                        <?php endif; ?>
                    </h6>
                    <span class="badge bg-secondary-subtle text-white-50"><?= count($list) ?> Mapel</span>
                </div>
                <div class="card-body p-3">
                    <?php if ($list === []): ?>
                        <div class="text-center text-white-50 py-4 small fst-italic">
                            Tidak ada jadwal pelajaran
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($list as $item): ?>
                                <div class="p-3 rounded bg-body-tertiary border border-secondary-subtle">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="badge bg-info-subtle text-info border border-info-subtle small">
                                            <?= e(substr((string) $item['jam_mulai'], 0, 5)) ?> - <?= e(substr((string) $item['jam_selesai'], 0, 5)) ?> WIB
                                        </span>
                                        <span class="badge text-bg-dark border small"><?= e($item['mapel_kode']) ?></span>
                                    </div>
                                    <div class="fw-semibold text-white mb-1"><?= e($item['mapel_nama']) ?></div>
                                    <div class="small text-white-50">Guru: <?= e($item['guru_nama']) ?></div>
                                    <?php if (($item['ruangan'] ?? '') !== ''): ?>
                                        <div class="small text-info-emphasis mt-1">📍 Ruangan: <?= e($item['ruangan']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
