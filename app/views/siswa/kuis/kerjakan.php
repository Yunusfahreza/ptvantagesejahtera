<?php $huruf = ['a', 'b', 'c', 'd', 'e']; ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <!-- Sticky Timer & Header Bar -->
        <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 4.8rem; z-index: 90;">
            <div class="card-body p-3.5 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="squircle-icon squircle-icon-blue" style="width: 42px; height: 42px; border-radius: 10px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="h5 fw-bold mb-0.5" style="color: var(--slate-primary);"><?= e($percobaan['judul']) ?></h1>
                        <p class="small mb-0 d-flex align-items-center gap-1.5" style="color: var(--slate-tertiary);">
                            <span><?= e(count($soal)) ?> Butir Soal</span>
                            <span>&bull;</span>
                            <span id="statusSimpan" class="text-body-secondary">Jawaban tersimpan otomatis</span>
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 p-2 px-3.5 rounded-pill" style="background: rgba(15, 23, 42, 0.05); border: 1.5px solid rgba(15, 23, 42, 0.08);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--accent-sapphire);">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <div class="text-end">
                        <div class="small text-uppercase fw-bold" style="font-size: 0.64rem; color: var(--slate-muted); letter-spacing: 0.05em;">Sisa Waktu</div>
                        <div class="countdown fw-bold mb-0" id="countdown" data-sisa="<?= e($sisa) ?>" 
                             style="font-size: 1.3rem; font-family: 'Space Grotesk', sans-serif; color: var(--slate-primary); line-height: 1;">
                            --:--
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="post" action="<?= e(url('/siswa/kuis/selesai')) ?>" id="formSelesai"
              data-konfirmasi="Kumpulkan jawaban dan akhiri kuis sekarang?">
            <?= csrf_field() ?>
            <input type="hidden" name="kuis_id" value="<?= e($percobaan['kuis_id']) ?>">

            <div id="daftarSoal" class="d-flex flex-column gap-3.5" data-kuis="<?= e($percobaan['kuis_id']) ?>"
                 data-url-jawab="<?= e(url('/siswa/kuis/jawab')) ?>">
                <?php foreach ($soal as $i => $s): ?>
                    <div class="card shadow-sm border-0 soal-card" data-soal="<?= e($s['id']) ?>">
                        <div class="card-body p-4">
                            <div class="d-flex gap-3 mb-3">
                                <span class="badge fw-bold px-2.5 py-1.5" style="background: var(--slate-primary); color: #ffffff; border-radius: 8px; font-size: 0.8rem; height: fit-content;">
                                    Soal #<?= e($i + 1) ?>
                                </span>
                                <div class="fw-semibold" style="color: var(--slate-primary); font-size: 1rem; line-height: 1.55;">
                                    <?= nl2br(e($s['pertanyaan'])) ?>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-2 mt-3 ps-md-4">
                                <?php foreach ($huruf as $h): ?>
                                    <?php if (($s['opsi_' . $h] ?? null) === null) { continue; } ?>
                                    <?php $isDipilih = (($jawaban[(int) $s['id']] ?? '') === $h); ?>
                                    <label class="opsi-label d-flex align-items-center gap-3 p-3 rounded-3" 
                                           style="background: <?= $isDipilih ? 'rgba(37, 99, 235, 0.06)' : 'rgba(255, 255, 255, 0.8)' ?>; border: 1.5px solid <?= $isDipilih ? 'var(--accent-sapphire)' : 'rgba(226, 232, 240, 0.9)' ?>; cursor: pointer; transition: all 0.2s ease;">
                                        <input class="form-check-input mt-0" type="radio"
                                               name="soal<?= e($s['id']) ?>" value="<?= e($h) ?>"
                                            <?= $isDipilih ? 'checked' : '' ?> style="transform: scale(1.15);">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge rounded-circle p-1 fw-bold" style="width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; background: rgba(15, 23, 42, 0.06); color: var(--slate-primary); font-size: 0.72rem;">
                                                <?= e(strtoupper($h)) ?>
                                            </span>
                                            <span style="color: var(--slate-secondary); font-size: 0.92rem;"><?= e($s['opsi_' . $h]) ?></span>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="my-4 pt-2 text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-lg" style="border-radius: var(--radius-pill); font-size: 1rem;">
                    Kumpulkan dan Akhiri Kuis
                </button>
            </div>
        </form>
    </div>
</div>
