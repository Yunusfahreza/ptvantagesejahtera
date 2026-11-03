<?php
$isUbah = $buku !== null;
$aksi   = url('/perpustakaan/simpan');
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= e(url('/perpustakaan')) ?>">E-Perpustakaan</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= $isUbah ? 'Edit Buku' : 'Tambah Buku' ?></li>
                    </ol>
                </nav>
                <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary);"><?= e($judul) ?></h1>
                <p class="small mb-0" style="color: var(--slate-tertiary);">Unggah dokumen buku sekolah elektronik, modul pelajaran, atau buku bacaan ilmiah</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/perpustakaan')) ?>">
                &larr; Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <form method="post" action="<?= e($aksi) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <?php if ($isUbah): ?>
                        <input type="hidden" name="id" value="<?= e($buku['id']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-bold">Judul Buku / Modul <span class="text-danger">*</span></label>
                        <input type="text" class="form-control py-2" id="judul" name="judul" maxlength="200" required
                               value="<?= e($buku['judul'] ?? '') ?>" placeholder="Contoh: Matematika Tingkat Lanjut SMA Kelas XI">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="penulis" class="form-label fw-bold">Penulis / Penyusun Buku</label>
                            <input type="text" class="form-control py-2" id="penulis" name="penulis" maxlength="150"
                                   value="<?= e($buku['penulis'] ?? '') ?>" placeholder="Contoh: Kemendikbudristek RI">
                        </div>
                        <div class="col-md-6">
                            <label for="kategori" class="form-label fw-bold">Kategori Koleksi <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="kategori" name="kategori" required>
                                <option value="wajib" <?= ($buku['kategori'] ?? '') === 'wajib' ? 'selected' : '' ?>>Buku Wajib / Paket Utama</option>
                                <option value="pendukung" <?= ($buku['kategori'] ?? 'pendukung') === 'pendukung' ? 'selected' : '' ?>>Buku Referensi / Pendukung</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="mapel_id" class="form-label fw-bold">Mata Pelajaran Terkait</label>
                            <select class="form-select py-2" id="mapel_id" name="mapel_id">
                                <option value="">-- Umum / Lintas Mapel --</option>
                                <?php foreach ($mapel as $m): ?>
                                    <option value="<?= e($m['id']) ?>" <?= ((int) ($buku['mapel_id'] ?? 0) === (int) $m['id']) ? 'selected' : '' ?>>
                                        <?= e($m['nama']) ?> (<?= e($m['kode']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tingkat" class="form-label fw-bold">Tingkat Kelas Sasaran <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="tingkat" name="tingkat" required>
                                <option value="semua" <?= ($buku['tingkat'] ?? '') === 'semua' ? 'selected' : '' ?>>Semua Tingkat</option>
                                <option value="X" <?= ($buku['tingkat'] ?? '') === 'X' ? 'selected' : '' ?>>Kelas X</option>
                                <option value="XI" <?= ($buku['tingkat'] ?? '') === 'XI' ? 'selected' : '' ?>>Kelas XI</option>
                                <option value="XII" <?= ($buku['tingkat'] ?? '') === 'XII' ? 'selected' : '' ?>>Kelas XII</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-bold">Sinopsis / Ringkasan Buku</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                  placeholder="Tuliskan ulasan singkat mengenai bab dan materi utama dalam buku ini..."><?= e($buku['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label for="berkas" class="form-label fw-bold">
                                Berkas E-Book (PDF / EPUB)
                                <?php if (!$isUbah): ?><span class="text-danger">*</span><?php endif; ?>
                            </label>
                            <input type="file" class="form-control py-2" id="berkas" name="berkas" accept="application/pdf,.epub">
                            <div class="form-text small" style="color: var(--slate-muted);">
                                Format: PDF atau EPUB. Maksimal: 50 MB.
                                <?php if ($isUbah): ?>
                                    <br><span class="text-success fw-semibold">Berkas saat ini: <?= e($buku['file_asli']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="cover" class="form-label fw-bold">Gambar Sampul Depan <span class="text-muted fw-normal">(opsional)</span></label>
                            <input type="file" class="form-control py-2" id="cover" name="cover" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text small" style="color: var(--slate-muted);">Format: JPG, PNG, WebP (maks 5 MB).</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <a href="<?= e(url('/perpustakaan')) ?>" class="btn btn-outline-secondary px-4 py-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                            <?= $isUbah ? 'Simpan Perubahan' : 'Terbitkan Buku' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
