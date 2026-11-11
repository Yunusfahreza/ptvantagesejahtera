<?php
$isUbah = $meet !== null;
$aksi   = url('/guru/meet/simpan');
$statusSaatIni = $meet['status'] ?? 'terjadwal';
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= e(url('/guru/meet')) ?>">Google Meet</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= $isUbah ? 'Ubah Jadwal' : 'Jadwalkan Sesi' ?></li>
                    </ol>
                </nav>
                <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary);"><?= e($judul) ?></h1>
                <p class="small mb-0" style="color: var(--slate-tertiary);">Bagikan ruang Google Meet ke kelas agar siswa dapat bergabung langsung dari portal LMS</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/guru/meet')) ?>">
                &larr; Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <form method="post" action="<?= e($aksi) ?>">
                    <?= csrf_field() ?>
                    <?php if ($isUbah): ?>
                        <input type="hidden" name="id" value="<?= e($meet['id']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="pengampu_id" class="form-label fw-bold">Kelas & Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select py-2" id="pengampu_id" name="pengampu_id" required>
                            <option value="">-- Pilih Rombel & Mapel --</option>
                            <?php foreach ($pengampu as $p): ?>
                                <?php $selected = (int) ($meet['pengampu_id'] ?? 0) === (int) $p['id'] ? 'selected' : ''; ?>
                                <option value="<?= e($p['id']) ?>" <?= $selected ?>>
                                    Kelas <?= e($p['kelas_nama']) ?> &mdash; <?= e($p['mapel_nama']) ?> (<?= e($p['mapel_kode']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-bold">Judul / Topik Pertemuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control py-2" id="judul" name="judul" maxlength="150" required
                               value="<?= e($meet['judul'] ?? '') ?>"
                               placeholder="Contoh: Pembahasan Kisi-Kisi UAS & Tanya Jawab Bab 3">
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-bold">Agenda / Catatan untuk Siswa</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                  placeholder="Sampaikan persiapan yang perlu dibawa siswa (buku catatan, kalkulator, atau modul bab terkait)..."><?= e($meet['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3 p-3 rounded-4" style="background: rgba(5, 150, 105, 0.05); border: 1px solid rgba(5, 150, 105, 0.2);">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                            <label for="link_meet" class="form-label fw-bold mb-0">Tautan atau Kode Google Meet <span class="text-danger">*</span></label>
                            <a href="https://meet.google.com/new" target="_blank" rel="noopener noreferrer"
                               class="btn btn-sm btn-success px-2.5 py-1 fw-semibold d-inline-flex align-items-center gap-1">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 10l4.553-2.276A1 1 0 0 1 21 8.618v6.764a1 1 0 0 1-1.447.894L15 14v-4z"/><rect x="3" y="6" width="12" height="12" rx="2" ry="2"/>
                                </svg>
                                <span>Buat Room Meet Baru &nearr;</span>
                            </a>
                        </div>
                        <input type="text" class="form-control py-2" id="link_meet" name="link_meet" required
                               value="<?= e($meet['link_meet'] ?? '') ?>"
                               placeholder="https://meet.google.com/abc-defg-hij atau ketik kode abc-defg-hij">
                        <div class="form-text small" style="color: var(--slate-secondary);">
                            Anda dapat menempel tautan lengkap <code>https://meet.google.com/xxx-xxxx-xxx</code> atau cukup kode 3 bagiannya saja (contoh: <code>smn-grt-xii</code>).
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label fw-bold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control py-2" id="tanggal" name="tanggal" required
                                   value="<?= e($meet['tanggal'] ?? date('Y-m-d')) ?>">
                        </div>
                        <div class="col-md-2 col-6">
                            <label for="jam_mulai" class="form-label fw-bold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control py-2" id="jam_mulai" name="jam_mulai" required
                                   value="<?= e(substr((string) ($meet['jam_mulai'] ?? '08:00'), 0, 5)) ?>">
                        </div>
                        <div class="col-md-2 col-6">
                            <label for="jam_selesai" class="form-label fw-bold">Selesai <span class="text-danger">*</span></label>
                            <input type="time" class="form-control py-2" id="jam_selesai" name="jam_selesai" required
                                   value="<?= e(substr((string) ($meet['jam_selesai'] ?? '09:30'), 0, 5)) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-bold">Status Sesi <span class="text-danger">*</span></label>
                            <select class="form-select py-2" id="status" name="status">
                                <option value="terjadwal" <?= $statusSaatIni === 'terjadwal' ? 'selected' : '' ?>>Terjadwal (Akan Datang)</option>
                                <option value="berlangsung" <?= $statusSaatIni === 'berlangsung' ? 'selected' : '' ?>>Sedang Berlangsung (LIVE)</option>
                                <option value="selesai" <?= $statusSaatIni === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <a href="<?= e(url('/guru/meet')) ?>" class="btn btn-outline-secondary px-4 py-2">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                            <?= $isUbah ? 'Simpan Perubahan' : 'Publikasikan Google Meet' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
