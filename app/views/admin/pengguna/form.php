<?php
$adalahEdit = $user !== null;
$roleAktif  = $user['role'] ?? 'siswa';
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb page-breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?= e(url('/admin/pengguna')) ?>">Pengguna</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: var(--slate-primary);"><?= $adalahEdit ? 'Edit Pengguna' : 'Tambah Baru' ?></li>
                    </ol>
                </nav>
                <h1 class="h4 fw-bold mb-1" style="color: var(--slate-primary);"><?= $adalahEdit ? 'Ubah Data Pengguna' : 'Tambah Pengguna Baru' ?></h1>
                <p class="small mb-0" style="color: var(--slate-tertiary);">Kelola kredensial akun, peran akses sistem, dan penempatan kelas siswa</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 py-1.5" href="<?= e(url('/admin/pengguna')) ?>">
                &larr; Kembali
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <form method="post" action="<?= e(url('/admin/pengguna/simpan')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= e($user['id'] ?? '0') ?>">

                    <div class="mb-3">
                        <label for="nama" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control py-2" id="nama" name="nama" required
                               maxlength="100" value="<?= e($user['nama'] ?? '') ?>" placeholder="Nama lengkap beserta gelar jika ada">
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control py-2" id="username" name="username" required
                               maxlength="50" value="<?= e($user['username'] ?? '') ?>" placeholder="mis. ahmad.fauzi">
                        <div class="form-text small" style="color: var(--slate-muted);">
                            Gunakan huruf kecil, angka, titik, strip atau garis bawah. Minimal 3 karakter.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label fw-bold">Peran Akses <span class="text-danger">*</span></label>
                        <select class="form-select py-2" id="role" name="role" required>
                            <?php foreach (['admin', 'guru', 'siswa'] as $r): ?>
                                <option value="<?= e($r) ?>" <?= $roleAktif === $r ? 'selected' : '' ?>>
                                    <?= e(ucfirst($r)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3" id="blokKelas">
                        <label for="kelas_id" class="form-label fw-bold">Rombel Kelas <span class="text-muted fw-normal">(Khusus Siswa)</span></label>
                        <select class="form-select py-2" id="kelas_id" name="kelas_id">
                            <option value="0">-- Pilih Rombel Kelas --</option>
                            <?php foreach ($kelas as $k): ?>
                                <option value="<?= e($k['id']) ?>"
                                    <?= (int) ($user['kelas_id'] ?? 0) === (int) $k['id'] ? 'selected' : '' ?>>
                                    Kelas <?= e($k['nama']) ?> &middot; <?= e($k['tahun_nama']) ?>
                                    <?= (int) $k['tahun_aktif'] === 1 ? '(Aktif)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text small" style="color: var(--slate-muted);">Wajib ditentukan untuk akun siswa. Diabaikan untuk admin dan guru.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">
                            Password <?= $adalahEdit ? '<span class="text-muted fw-normal">(kosongkan jika tidak diubah)</span>' : '<span class="text-danger">*</span>' ?>
                        </label>
                        <input type="text" class="form-control py-2" id="password" name="password"
                               minlength="8" autocomplete="off"
                               <?= $adalahEdit ? '' : 'required' ?>
                               placeholder="Minimal 8 karakter...">
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
                        <a class="btn btn-outline-secondary px-4 py-2" href="<?= e(url('/admin/pengguna')) ?>">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                            <?= $adalahEdit ? 'Simpan Perubahan' : 'Buat Pengguna' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
