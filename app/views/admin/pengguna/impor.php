<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= e(url('/admin/pengguna')) ?>">Pengguna</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);">Impor Massal</li>
                    </ol>
                </nav>
                <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary);">Impor Data Siswa dari Berkas CSV</h1>
                <p class="small mb-0" style="color: var(--slate-tertiary);">Tambahkan akun siswa dalam jumlah banyak secara otomatis melalui berkas spreadsheet</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/admin/pengguna')) ?>">
                &larr; Kembali
            </a>
        </div>

        <!-- Format Panduan Berkas -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-2" style="color: var(--slate-primary);">Format Header & Struktur Kolom CSV</h2>
                <p class="small mb-3" style="color: var(--slate-tertiary);">
                    Baris pertama dokumen CSV wajib berisi nama kolom (header). Pemisah koma (<code>,</code>) maupun titik-koma (<code>;</code>) didukung oleh sistem.
                </p>
                
                <div class="p-3 rounded-3 font-monospace small mb-3" style="background: rgba(15, 23, 42, 0.05); border: 1px solid rgba(15, 23, 42, 0.08); color: var(--slate-primary);">
nama,username,password,kelas
Ahmad Fauzi,siswa.ahmad2,rahasia123,X-1
Bella Puspita,siswa.bella2,rahasia123,X-1
                </div>

                <ul class="small mb-3 d-flex flex-column gap-1" style="color: var(--slate-secondary);">
                    <li>Kolom <code>kelas</code> diisi dengan nama kelas yang sudah terdaftar pada tahun ajaran aktif.</li>
                    <li>Username harus unik, panjang 3-50 karakter (huruf kecil, angka, titik, strip).</li>
                    <li>Password minimal 8 karakter.</li>
                    <li>Jika ada baris yang gagal, sistem akan melaporkan letak errornya dan tetap memproses baris yang valid.</li>
                </ul>

                <div class="p-2.5 rounded-2 small" style="background: rgba(248, 250, 252, 0.85); border: 1px solid rgba(226, 232, 240, 0.85);">
                    <span class="fw-bold" style="color: var(--slate-secondary);">Kelas Tersedia di Tahun Ajaran Aktif:</span>
                    <div class="d-flex flex-wrap gap-1.5 mt-1.5">
                        <?php if ($kelas === []): ?>
                            <span class="text-danger fw-semibold">Belum ada kelas aktif di database.</span>
                        <?php else: ?>
                            <?php foreach ($kelas as $k): ?>
                                <span class="badge px-2 py-1" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border: 1px solid rgba(15, 23, 42, 0.1); border-radius: 6px;">
                                    <?= e($k['nama']) ?>
                                </span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent p-3 border-top d-flex justify-content-end" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                <a class="btn btn-sm btn-outline-primary px-3 py-1.5 d-inline-flex align-items-center gap-1.5"
                   href="<?= e(url('/admin/pengguna/contoh-csv')) ?>">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    <span>Unduh Template Contoh CSV</span>
                </a>
            </div>
        </div>

        <!-- Form Unggah CSV -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <form method="post" action="<?= e(url('/admin/pengguna/impor')) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="berkas" class="form-label fw-bold">Pilih Berkas CSV Siswa <span class="text-danger">*</span></label>
                        <input type="file" class="form-control py-2" id="berkas" name="berkas" accept=".csv,text/csv" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <a class="btn btn-outline-secondary px-4 py-2" href="<?= e(url('/admin/pengguna')) ?>">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                            Mulai Proses Impor
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Hasil Laporan Impor -->
        <?php if (!empty($hasil)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-header py-3">
                    <span class="fw-bold" style="color: var(--slate-primary); font-size: 0.94rem;">Laporan Hasil Pemrosesan Impor</span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex gap-2 mb-3">
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 fs-6 fw-bold">
                            <?= e($hasil['berhasil']) ?> Baris Berhasil Ditambahkan
                        </span>
                        <span class="badge bg-<?= $hasil['gagal'] === [] ? 'secondary-subtle text-muted' : 'danger-subtle text-danger' ?> border px-3 py-1.5 fs-6 fw-bold">
                            <?= e(count($hasil['gagal'])) ?> Baris Gagal
                        </span>
                    </div>

                    <?php if ($hasil['gagal'] !== []): ?>
                        <div class="p-3 rounded-3" style="background: rgba(225, 29, 72, 0.05); border: 1px solid rgba(225, 29, 72, 0.2);">
                            <h6 class="fw-bold text-danger mb-2 small text-uppercase">Rincian Baris yang Mengalami Kendala:</h6>
                            <ul class="small mb-0 ps-3" style="color: var(--accent-rose);">
                                <?php foreach ($hasil['gagal'] as $g): ?>
                                    <li class="mb-1"><?= e($g) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
