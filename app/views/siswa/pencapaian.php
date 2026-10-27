<div class="mb-4">
    <h1 class="h4 mb-1 text-white fw-bold">Prestasi, Badge & Peringkat Kelas</h1>
    <p class="text-white-50 small mb-0">Raih badge penghargaan melalui konsistensi belajar dan pantau peringkat kelas</p>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Koleksi Badge Pribadi -->
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-transparent py-3 border-bottom border-secondary-subtle">
                <h5 class="card-title text-white fw-bold mb-0 d-flex align-items-center gap-2">
                    <span>🏆</span>
                    <span>Lencana Prestasi Saya</span>
                    <span class="badge bg-primary ms-auto"><?= count($badgeSaya) ?> Diraih</span>
                </h5>
            </div>
            <div class="card-body p-3">
                <?php if ($badgeSaya === []): ?>
                    <div class="text-center text-white-50 py-4 small">
                        <div class="fs-2 mb-2">🎯</div>
                        Belum ada lencana yang diraih.<br>
                        Kumpulkan tugas tepat waktu dan kerjakan kuis untuk membuka badge pertamamu!
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($badgeSaya as $b): ?>
                            <div class="p-3 rounded bg-body-tertiary border border-secondary-subtle d-flex align-items-center gap-3">
                                <div class="fs-1 flex-shrink-0"><?= e($b['ikon']) ?></div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold text-white"><?= e($b['label']) ?></div>
                                    <div class="small text-white-50"><?= e($b['deskripsi']) ?></div>
                                    <div class="small text-info-emphasis mt-1" style="font-size: 0.75rem;">
                                        Diraih pada <?= e(tanggal_id($b['diperoleh_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Info Cara Mendapatkan Badge -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <h6 class="text-white fw-bold mb-2 small text-uppercase tracking-wider">Lencana yang Dapat Dibuka</h6>
                <div class="d-flex flex-column gap-2 small text-white-50">
                    <div class="d-flex align-items-center gap-2">
                        <span>⏰</span>
                        <div><strong>Pengumpul Tepat Waktu:</strong> Kumpulkan minimal 3 tugas sebelum batas tenggat.</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span>💯</span>
                        <div><strong>Nilai Sempurna:</strong> Dapatkan skor 100 pada tugas atau kuis.</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span>📝</span>
                        <div><strong>Rajin Latihan:</strong> Selesaikan minimal 5 kuis online.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Leaderboard Kelas -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent py-3 border-bottom border-secondary-subtle">
                <h5 class="card-title text-white fw-bold mb-0 d-flex align-items-center gap-2">
                    <span>🥇</span>
                    <span>Papan Peringkat Kelas (Leaderboard)</span>
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;" class="text-center">Rank</th>
                            <th>Nama Siswa</th>
                            <th class="text-center" style="width: 100px;">Lencana</th>
                            <th class="text-end" style="width: 130px;">Rerata Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($leaderboard === []): ?>
                            <tr><td colspan="4" class="text-center text-body-secondary py-4">
                                Belum ada data nilai di kelas ini.
                            </td></tr>
                        <?php endif; ?>

                        <?php $rank = 1; foreach ($leaderboard as $m): ?>
                            <?php
                            $isSaya = (int) $m['id'] === (int) $siswaId;
                            $rankMedal = match ($rank) {
                                1 => '🥇 1',
                                2 => '🥈 2',
                                3 => '🥉 3',
                                default => (string) $rank,
                            };
                            ?>
                            <tr class="<?= $isSaya ? 'table-primary bg-primary-subtle' : '' ?>">
                                <td class="text-center fw-bold fs-6">
                                    <?= $rankMedal ?>
                                </td>
                                <td>
                                    <div class="fw-semibold text-white d-flex align-items-center gap-2">
                                        <span><?= e($m['nama']) ?></span>
                                        <?php if ($isSaya): ?>
                                            <span class="badge bg-primary text-white" style="font-size: 0.65rem;">Kamu</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="small text-white-50"><?= e($m['username']) ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary-subtle text-white-50 border border-secondary-subtle">
                                        🏆 <?= (int) ($m['total_badge'] ?? 0) ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-white">
                                    <?= number_format((float) ($m['nilai_rata'] ?? 0), 1) ?>
                                </td>
                            </tr>
                        <?php $rank++; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
