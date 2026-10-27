<?php
$jumlahSoal = (int) $kuis['jumlah_soal'];
$nilai = $percobaan['nilai'];
$benar = $jumlahSoal === 0 ? 0 : (int) round(((float) $nilai / 100) * $jumlahSoal);
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="<?= e(url('/siswa/kuis')) ?>">Kuis</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hasil</li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0">
            <div class="card-body text-center p-4">
                <p class="text-body-secondary mb-1"><?= e($kuis['mapel_nama']) ?></p>
                <h1 class="h5 mb-3"><?= e($kuis['judul']) ?></h1>

                <p class="display-3 fw-bold text-primary mb-0"><?= e(nilai_tampil($nilai)) ?></p>
                <p class="text-body-secondary">Nilai akhir</p>

                <dl class="row text-start small mb-0">
                    <dt class="col-6">Jumlah soal</dt>
                    <dd class="col-6"><?= e($jumlahSoal) ?></dd>

                    <dt class="col-6">Soal dijawab</dt>
                    <dd class="col-6"><?= e($dijawab) ?></dd>

                    <dt class="col-6">Jawaban benar</dt>
                    <dd class="col-6"><?= e($benar) ?></dd>

                    <dt class="col-6">Mulai</dt>
                    <dd class="col-6"><?= e(tanggal_id($percobaan['mulai_at'])) ?></dd>

                    <dt class="col-6">Selesai</dt>
                    <dd class="col-6"><?= e(tanggal_id($percobaan['selesai_at'])) ?></dd>
                </dl>
            </div>
            <div class="card-footer bg-transparent d-flex gap-2">
                <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/siswa/kuis')) ?>">
                    Daftar Kuis
                </a>
                <a class="btn btn-outline-primary btn-sm" href="<?= e(url('/siswa/nilai')) ?>">
                    Nilai Saya
                </a>
            </div>
        </div>

        <p class="small text-body-secondary mt-3 mb-0">
            Kunci jawaban tidak ditampilkan. Hubungi guru pengampu bila ingin membahas soal.
        </p>
    </div>
</div>
