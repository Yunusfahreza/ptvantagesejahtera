<?php
$isUbah = $buku !== null;
$aksi   = url('/perpustakaan/simpan');
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h4 mb-1 text-white fw-bold"><?= e($judul) ?></h1>
                <p class="text-white-50 small mb-0">Tambahkan e-book, buku paket sekolah, atau modul referensi perpustakaan digital</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/perpustakaan')) ?>">Kembali</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="post" action="<?= e($aksi) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <?php if ($isUbah): ?>
                        <input type="hidden" name="id" value="<?= e($buku['id']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="judul" class="form-label text-white-50 small fw-semibold">Judul Buku / E-Book <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="judul" name="judul" maxlength="200" required
                               value="<?= e($buku['judul'] ?? '') ?>" placeholder="Contoh: Matematika Tingkat Lanjut SMA Kelas XI">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="penulis" class="form-label text-white-50 small fw-semibold">Penulis / Penyusun</label>
                            <input type="text" class="form-control" id="penulis" name="penulis" maxlength="150"
                                   value="<?= e($buku['penulis'] ?? '') ?>" placeholder="Contoh: Kemendikbudristek / Prof. Dr. ...">
                        </div>
                        <div class="col-md-6">
                            <label for="kategori" class="form-label text-white-50 small fw-semibold">Kategori Buku <span class="text-danger">*</span></label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="wajib" <?= ($buku['kategori'] ?? '') === 'wajib' ? 'selected' : '' ?>>Buku Wajib / Paket Utama</option>
                                <option value="pendukung" <?= ($buku['kategori'] ?? 'pendukung') === 'pendukung' ? 'selected' : '' ?>>Buku Referensi / Pendukung</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="mapel_id" class="form-label text-white-50 small fw-semibold">Mata Pelajaran</label>
                            <select class="form-select" id="mapel_id" name="mapel_id">
                                <option value="">-- Umum / Semua Mapel --</option>
                                <?php foreach ($mapel as $m): ?>
                                    <option value="<?= e($m['id']) ?>" <?= ((int) ($buku['mapel_id'] ?? 0) === (int) $m['id']) ? 'selected' : '' ?>>
                                        <?= e($m['nama']) ?> (<?= e($m['kode']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tingkat" class="form-label text-white-50 small fw-semibold">Tingkat Kelas <span class="text-danger">*</span></label>
                            <select class="form-select" id="tingkat" name="tingkat" required>
                                <option value="semua" <?= ($buku['tingkat'] ?? '') === 'semua' ? 'selected' : '' ?>>Semua Tingkat</option>
                                <option value="X" <?= ($buku['tingkat'] ?? '') === 'X' ? 'selected' : '' ?>>Kelas X</option>
                                <option value="XI" <?= ($buku['tingkat'] ?? '') === 'XI' ? 'selected' : '' ?>>Kelas XI</option>
                                <option value="XII" <?= ($buku['tingkat'] ?? '') === 'XII' ? 'selected' : '' ?>>Kelas XII</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label text-white-50 small fw-semibold">Sinopsis / Ringkasan Buku</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                  placeholder="Tuliskan gambaran singkat materi atau topik yang dibahas..."><?= e($buku['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label for="berkas" class="form-label text-white-50 small fw-semibold">
                                File E-Book (PDF / EPUB)
                                <?php if (!$isUbah): ?><span class="text-danger">*</span><?php endif; ?>
                            </label>
                            <input type="file" class="form-control" id="berkas" name="berkas" accept="application/pdf,.epub">
                            <div class="form-text text-white-50">
                                Format: PDF atau EPUB. Maksimal: 50 MB.
                                <?php if ($isUbah): ?>
                                    <br><span class="text-info">File saat ini: <?= e($buku['file_asli']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="cover" class="form-label text-white-50 small fw-semibold">Gambar Sampul (Opsional)</label>
                            <input type="file" class="form-control" id="cover" name="cover" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text text-white-50">Format: JPG, PNG, WebP (maks 5 MB).</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= e(url('/perpustakaan')) ?>" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <?= $isUbah ? 'Simpan Perubahan' : 'Unggah Buku' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
