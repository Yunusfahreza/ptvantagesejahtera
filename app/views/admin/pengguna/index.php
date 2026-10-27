<?php

use App\Core\Auth;

/** Bangun URL paginasi dengan filter yang sedang aktif. */
$tautanHalaman = static function (int $hal) use ($filter): string {
    $q = array_filter([
        'q'      => $filter['q'],
        'role'   => $filter['role'],
        'kelas'  => $filter['kelas'] > 0 ? (string) $filter['kelas'] : '',
        'status' => $filter['status'],
        'hal'    => (string) $hal,
    ], static fn ($v) => $v !== '');

    return url('/admin/pengguna?' . http_build_query($q));
};
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="h4 mb-0">Pengguna <span class="text-body-secondary fs-6">(<?= e($total) ?>)</span></h1>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('/admin/pengguna/impor')) ?>">Impor CSV</a>
        <a class="btn btn-primary btn-sm" href="<?= e(url('/admin/pengguna/form')) ?>">Tambah Pengguna</a>
    </div>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="get" action="<?= e(url('/admin/pengguna')) ?>" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label for="q" class="form-label small mb-1">Cari nama atau username</label>
                <input type="search" class="form-control form-control-sm" id="q" name="q"
                       value="<?= e($filter['q']) ?>" placeholder="mis. Ahmad">
            </div>

            <div class="col-6 col-md-2">
                <label for="role" class="form-label small mb-1">Peran</label>
                <select class="form-select form-select-sm" id="role" name="role">
                    <option value="">Semua</option>
                    <?php foreach (['admin', 'guru', 'siswa'] as $r): ?>
                        <option value="<?= e($r) ?>" <?= $filter['role'] === $r ? 'selected' : '' ?>>
                            <?= e(ucfirst($r)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <label for="kelas" class="form-label small mb-1">Kelas</label>
                <select class="form-select form-select-sm" id="kelas" name="kelas">
                    <option value="">Semua</option>
                    <?php foreach ($kelas as $k): ?>
                        <option value="<?= e($k['id']) ?>"
                            <?= $filter['kelas'] === (int) $k['id'] ? 'selected' : '' ?>>
                            <?= e($k['nama']) ?> (<?= e($k['tahun_nama']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <label for="status" class="form-label small mb-1">Status</label>
                <select class="form-select form-select-sm" id="status" name="status">
                    <option value="">Semua</option>
                    <option value="aktif"    <?= $filter['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= $filter['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <div class="col-6 col-md-1 d-flex gap-1">
                <button class="btn btn-primary btn-sm w-100">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Peran</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data === []): ?>
                    <tr><td colspan="6" class="text-center text-body-secondary py-4">
                        Tidak ada pengguna yang cocok dengan filter.
                    </td></tr>
                <?php endif; ?>

                <?php foreach ($data as $u): ?>
                    <tr>
                        <td class="fw-semibold"><?= e($u['nama']) ?></td>
                        <td><code><?= e($u['username']) ?></code></td>
                        <td><span class="badge text-bg-secondary"><?= e($u['role']) ?></span></td>
                        <td><?= e($u['kelas_nama'] ?? '-') ?></td>
                        <td>
                            <?php if ($u['status'] === 'aktif'): ?>
                                <span class="badge text-bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge text-bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap gap-1 justify-content-end">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= e(url('/admin/pengguna/form/' . $u['id'])) ?>">Ubah</a>

                                <button class="btn btn-sm btn-outline-secondary" type="button"
                                        data-bs-toggle="modal" data-bs-target="#reset<?= e($u['id']) ?>">
                                    Reset Password
                                </button>

                                <?php if ((int) $u['id'] !== (int) Auth::id()): ?>
                                    <form method="post" action="<?= e(url('/admin/pengguna/status')) ?>"
                                          data-konfirmasi="Ubah status pengguna ini?">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($u['id']) ?>">
                                        <input type="hidden" name="status"
                                               value="<?= $u['status'] === 'aktif' ? 'nonaktif' : 'aktif' ?>">
                                        <button class="btn btn-sm btn-outline-<?= $u['status'] === 'aktif' ? 'danger' : 'success' ?>">
                                            <?= $u['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                            <div class="modal fade" id="reset<?= e($u['id']) ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form class="modal-content" method="post"
                                          action="<?= e(url('/admin/pengguna/reset')) ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($u['id']) ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reset Password</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <p class="small text-body-secondary">
                                                Password baru untuk <strong><?= e($u['nama']) ?></strong>.
                                            </p>
                                            <input type="text" class="form-control" name="password"
                                                   minlength="8" required autocomplete="off"
                                                   placeholder="Minimal 8 karakter">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_halaman > 1): ?>
        <div class="card-footer bg-transparent">
            <nav aria-label="Navigasi halaman">
                <ul class="pagination pagination-sm mb-0 flex-wrap">
                    <li class="page-item <?= $halaman <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= e($tautanHalaman(max(1, $halaman - 1))) ?>">Sebelumnya</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                        <li class="page-item <?= $i === $halaman ? 'active' : '' ?>">
                            <a class="page-link" href="<?= e($tautanHalaman($i)) ?>"><?= e($i) ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $halaman >= $total_halaman ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= e($tautanHalaman(min($total_halaman, $halaman + 1))) ?>">Berikutnya</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>
