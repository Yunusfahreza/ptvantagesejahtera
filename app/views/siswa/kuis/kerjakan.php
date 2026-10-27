<?php $huruf = ['a', 'b', 'c', 'd', 'e']; ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card shadow-sm border-0 mb-3 sticky-top" style="top:4.5rem">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h1 class="h5 mb-0"><?= e($percobaan['judul']) ?></h1>
                    <p class="small text-body-secondary mb-0">
                        <?= e(count($soal)) ?> soal &middot;
                        <span id="statusSimpan" class="text-body-secondary">jawaban tersimpan otomatis</span>
                    </p>
                </div>

                <div class="text-end">
                    <div class="small text-body-secondary">Sisa waktu</div>
                    <div class="h4 mb-0 countdown" id="countdown"
                         data-sisa="<?= e($sisa) ?>">--:--</div>
                </div>
            </div>
        </div>

        <form method="post" action="<?= e(url('/siswa/kuis/selesai')) ?>" id="formSelesai"
              data-konfirmasi="Kumpulkan jawaban dan akhiri kuis sekarang?">
            <?= csrf_field() ?>
            <input type="hidden" name="kuis_id" value="<?= e($percobaan['kuis_id']) ?>">

            <div id="daftarSoal" data-kuis="<?= e($percobaan['kuis_id']) ?>"
                 data-url-jawab="<?= e(url('/siswa/kuis/jawab')) ?>">
                <?php foreach ($soal as $i => $s): ?>
                    <div class="card shadow-sm border-0 soal-card" data-soal="<?= e($s['id']) ?>">
                        <div class="card-body">
                            <p class="fw-semibold mb-2">
                                <?= e($i + 1) ?>. <?= nl2br(e($s['pertanyaan'])) ?>
                            </p>

                            <?php foreach ($huruf as $h): ?>
                                <?php if (($s['opsi_' . $h] ?? null) === null) { continue; } ?>
                                <label class="opsi-label">
                                    <input class="form-check-input me-2" type="radio"
                                           name="soal<?= e($s['id']) ?>" value="<?= e($h) ?>"
                                        <?= ($jawaban[(int) $s['id']] ?? '') === $h ? 'checked' : '' ?>>
                                    <strong><?= e(strtoupper($h)) ?>.</strong>
                                    <?= e($s['opsi_' . $h]) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="d-grid gap-2 my-4">
                <button type="submit" class="btn btn-primary btn-lg">Kumpulkan dan Akhiri Kuis</button>
            </div>
        </form>
    </div>
</div>
