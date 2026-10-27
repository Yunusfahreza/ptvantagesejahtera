<?php
$adalahEdit = $user !== null;
$roleAktif  = $user['role'] ?? 'siswa';
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <h1 class="h4 mb-3"><?= $adalahEdit ? 'Ubah Pengguna' : 'Tambah Pengguna' ?></h1>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="post" action="<?= e(url('/admin/pengguna/simpan')) ?>" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= e($user['id'] ?? '0') ?>">

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" required
                               maxlength="100" value="<?= e($user['nama'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required
                               maxlength="50" value="<?= e($user['username'] ?? '') ?>">
                        <div class="form-text">
                            Huruf kecil, angka, titik, garis bawah, atau strip. Minimal 3 karakter.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Peran</label>
                        <select class="form-select" id="role" name="role" required>
                            <?php foreach (['admin', 'guru', 'siswa'] as $r): ?>
                                <option value="<?= e($r) ?>" <?= $roleAktif === $r ? 'selected' : '' ?>>
                                    <?= e(ucfirst($r)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3" id="blokKelas">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select class="form-select" id="kelas_id" name="kelas_id">
                            <option value="0">-- pilih kelas --</option>
                            <?php foreach ($kelas as $k): ?>
                                <option value="<?= e($k['id']) ?>"
                                    <?= (int) ($user['kelas_id'] ?? 0) === (int) $k['id'] ? 'selected' : '' ?>>
                                    <?= e($k['nama']) ?> &middot; <?= e($k['tahun_nama']) ?>
                                    <?= (int) $k['tahun_aktif'] === 1 ? '(aktif)' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Wajib untuk siswa. Diabaikan untuk admin dan guru.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Password <?= $adalahEdit ? '(kosongkan bila tidak diubah)' : '' ?>
                        </label>
                        <input type="text" class="form-control" id="password" name="password"
                               minlength="8" autocomplete="off"
                               <?= $adalahEdit ? '' : 'required' ?>
                               placeholder="Minimal 8 karakter">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a class="btn btn-outline-secondary" href="<?= e(url('/admin/pengguna')) ?>">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
/* Sembunyikan pilihan kelas untuk peran selain siswa. Validasi tetap di server. */
(function () {
    var role = document.getElementById('role');
    var blok = document.getElementById('blokKelas');

    function sinkron() {
        blok.classList.toggle('d-none', role.value !== 'siswa');
    }

    role.addEventListener('change', sinkron);
    sinkron();
}());
</script>
