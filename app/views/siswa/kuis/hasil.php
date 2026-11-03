<?php
$jumlahSoal = (int) $kuis['jumlah_soal'];
$nilai = $percobaan['nilai'];
$benar = $jumlahSoal === 0 ? 0 : (int) round(((float) $nilai / 100) * $jumlahSoal);
$salah = max(0, (int) $dijawab - $benar);
$tidakDijawab = max(0, $jumlahSoal - (int) $dijawab);
$isLulus = ((float) $nilai >= 75);
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb page-breadcrumb">
                <li class="breadcrumb-item"><a href="<?= e(url('/siswa/kuis')) ?>">Daftar Kuis</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);">Hasil Evaluasi</li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0">
            <div class="card-body text-center p-4 p-md-5">
                <span class="badge px-2.5 py-1.5 fw-bold mb-2" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border: 1px solid rgba(15, 23, 42, 0.1); border-radius: 8px;">
                    <?= e($kuis['mapel_nama']) ?>
                </span>
                
                <h1 class="h4 fw-bold mb-4" style="color: var(--slate-primary); letter-spacing: -0.01em;">
                    <?= e($kuis['judul']) ?>
                </h1>

                <!-- Score Ring Card -->
                <div class="p-4 rounded-4 mb-4" style="background: rgba(248, 250, 252, 0.9); border: 1.5px solid rgba(226, 232, 240, 0.9);">
                    <div class="text-uppercase fw-bold small mb-1" style="color: var(--slate-muted); letter-spacing: 0.08em; font-size: 0.72rem;">Perolehan Skor Akhir</div>
                    <div class="display-3 fw-bold mb-1" style="font-family: 'Space Grotesk', sans-serif; color: <?= $isLulus ? 'var(--accent-emerald)' : 'var(--accent-amber)' ?>; line-height: 1;">
                        <?= e(nilai_tampil($nilai)) ?>
                    </div>
                    <div class="badge px-3 py-1 fw-bold" style="background: <?= $isLulus ? 'rgba(5, 150, 105, 0.1)' : 'rgba(217, 119, 6, 0.1)' ?>; color: <?= $isLulus ? 'var(--accent-emerald)' : 'var(--accent-amber)' ?>; border-radius: 9999px;">
                        <?= $isLulus ? 'Memenuhi Standar Kelulusan' : 'Perlu Remedial / Pembahasan' ?>
                    </div>
                </div>

                <!-- Metrics breakdown -->
                <div class="row g-2 mb-4 text-center">
                    <div class="col-4">
                        <div class="p-2.5 rounded-3" style="background: rgba(5, 150, 105, 0.06); border: 1px solid rgba(5, 150, 105, 0.2);">
                            <div class="fw-bold fs-5 mb-0" style="color: var(--accent-emerald); font-family: 'Space Grotesk', sans-serif;"><?= $benar ?></div>
                            <div class="small fw-semibold" style="color: var(--slate-tertiary); font-size: 0.72rem;">Benar</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2.5 rounded-3" style="background: rgba(225, 29, 72, 0.06); border: 1px solid rgba(225, 29, 72, 0.2);">
                            <div class="fw-bold fs-5 mb-0" style="color: var(--accent-rose); font-family: 'Space Grotesk', sans-serif;"><?= $salah ?></div>
                            <div class="small fw-semibold" style="color: var(--slate-tertiary); font-size: 0.72rem;">Salah</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2.5 rounded-3" style="background: rgba(15, 23, 42, 0.05); border: 1px solid rgba(15, 23, 42, 0.1);">
                            <div class="fw-bold fs-5 mb-0" style="color: var(--slate-primary); font-family: 'Space Grotesk', sans-serif;"><?= $jumlahSoal ?></div>
                            <div class="small fw-semibold" style="color: var(--slate-tertiary); font-size: 0.72rem;">Total Butir</div>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-3 text-start small mb-0" style="background: rgba(248, 250, 252, 0.85); border: 1px solid rgba(226, 232, 240, 0.9);">
                    <div class="d-flex justify-content-between mb-2 pb-1.5 border-bottom" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <span style="color: var(--slate-tertiary);">Waktu Mulai:</span>
                        <span class="fw-semibold" style="color: var(--slate-secondary);"><?= e(tanggal_id($percobaan['mulai_at'])) ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span style="color: var(--slate-tertiary);">Waktu Diserahkan:</span>
                        <span class="fw-semibold" style="color: var(--slate-secondary);"><?= e(tanggal_id($percobaan['selesai_at'])) ?></span>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-transparent p-3 d-flex gap-2 justify-content-between border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                <a class="btn btn-outline-secondary btn-sm px-3 py-2 fw-semibold" href="<?= e(url('/siswa/kuis')) ?>">
                    &larr; Daftar Kuis
                </a>
                <a class="btn btn-primary btn-sm px-3 py-2 fw-semibold" href="<?= e(url('/siswa/nilai')) ?>">
                    Buku Nilai Saya
                </a>
            </div>
        </div>

        <p class="small text-center mt-3 mb-0" style="color: var(--slate-muted);">
            Kunci jawaban tidak ditampilkan untuk menjaga kerahasiaan bank soal. Hubungi guru pengampu untuk sesi pembahasan.
        </p>
    </div>
</div>
