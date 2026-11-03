<?php
$isUbah = $video !== null;
$aksi   = url('/guru/video/simpan');
$tipeSaatIni = $video['tipe'] ?? 'youtube';
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= e(url('/guru/video')) ?>">Video Pembelajaran</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= $isUbah ? 'Edit Video' : 'Tambah Baru' ?></li>
                    </ol>
                </nav>
                <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary);"><?= e($judul) ?></h1>
                <p class="small mb-0" style="color: var(--slate-tertiary);">Publikasikan media belajar interaktif melalui tautan YouTube, Drive, atau unggahan berkas</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/guru/video')) ?>">
                &larr; Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <form method="post" action="<?= e($aksi) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <?php if ($isUbah): ?>
                        <input type="hidden" name="id" value="<?= e($video['id']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="pengampu_id" class="form-label fw-bold">Kelas & Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select py-2" id="pengampu_id" name="pengampu_id" required>
                            <option value="">-- Pilih Rombel & Mapel --</option>
                            <?php foreach ($pengampu as $p): ?>
                                <?php $selected = (int) ($video['pengampu_id'] ?? 0) === (int) $p['id'] ? 'selected' : ''; ?>
                                <option value="<?= e($p['id']) ?>" <?= $selected ?>>
                                    Kelas <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?> (<?= e($p['mapel_kode']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-bold">Judul Video <span class="text-danger">*</span></label>
                        <input type="text" class="form-control py-2" id="judul" name="judul" maxlength="150" required
                               value="<?= e($video['judul'] ?? '') ?>" placeholder="Contoh: Pembahasan Hukum Newton & Dinamika Partikel">
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-bold">Deskripsi / Petunjuk Belajar</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                  placeholder="Catatan pengantar materi atau instruksi menyimak bagi siswa..."><?= e($video['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="tipe" class="form-label fw-bold">Metode Sumber Video <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="tipe" name="tipe" onchange="gantiTipeVideo(this.value)">
                                <option value="youtube" <?= $tipeSaatIni === 'youtube' ? 'selected' : '' ?>>YouTube (Rekomendasi Cepat)</option>
                                <option value="gdrive" <?= $tipeSaatIni === 'gdrive' ? 'selected' : '' ?>>Google Drive</option>
                                <option value="upload" <?= $tipeSaatIni === 'upload' ? 'selected' : '' ?>>Unggah File MP4/WebM (Maks 100 MB)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="durasi" class="form-label fw-bold">Perkiraan Durasi (opsional)</label>
                            <input type="text" class="form-control py-2" id="durasi" name="durasi" maxlength="10"
                                   value="<?= e($video['durasi'] ?? '') ?>" placeholder="Contoh: 15:30">
                        </div>
                    </div>

                    <!-- Wadah input URL YouTube / GDrive -->
                    <div id="wadah-url" class="mb-3 <?= $tipeSaatIni === 'upload' ? 'd-none' : '' ?>">
                        <label for="url_embed" class="form-label fw-bold">Tautan (URL) Video <span class="text-danger">*</span></label>
                        <input type="url" class="form-control py-2" id="url_embed" name="url_embed"
                               value="<?= e($video['url_embed'] ?? '') ?>"
                               placeholder="https://www.youtube.com/watch?v=... atau https://drive.google.com/file/d/...">
                        <div class="form-text small" style="color: var(--slate-muted);">Sistem otomatis mengonversi tautan biasa menjadi mode pemutar tayang (embed).</div>
                    </div>

                    <!-- Wadah upload file MP4 -->
                    <div id="wadah-upload" class="mb-4 <?= $tipeSaatIni !== 'upload' ? 'd-none' : '' ?>">
                        <label for="berkas" class="form-label fw-bold">
                            Berkas Video (MP4 / WebM)
                            <?php if (!$isUbah): ?><span class="text-danger">*</span><?php endif; ?>
                        </label>
                        <input type="file" class="form-control" id="berkas" name="berkas" accept="video/mp4,video/webm">
                        <div class="form-text small" style="color: var(--slate-muted);">
                            Format yang didukung: MP4, WebM. Ukuran maksimal: 100 MB.
                            <?php if ($isUbah && ($video['file_asli'] ?? '') !== ''): ?>
                                <br><span class="text-success fw-semibold">Berkas saat ini: <?= e($video['file_asli']) ?> (kosongkan jika tidak diganti)</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <a href="<?= e(url('/guru/video')) ?>" class="btn btn-outline-secondary px-4 py-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                            <?= $isUbah ? 'Simpan Perubahan' : 'Terbitkan Video' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function gantiTipeVideo(val) {
    var wadahUrl = document.getElementById('wadah-url');
    var wadahUpload = document.getElementById('wadah-upload');
    if (val === 'upload') {
        wadahUrl.classList.add('d-none');
        wadahUpload.classList.remove('d-none');
    } else {
        wadahUrl.classList.remove('d-none');
        wadahUpload.classList.add('d-none');
    }
}
</script>
