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

<div class="page-header-box d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <div class="page-breadcrumb">
            <span>Manajemen Pengguna</span>
            <span>&bull;</span>
            <span>Hak Akses & Akun</span>
        </div>
        <h1 class="page-title">Direktori Pengguna <span class="fs-6 fw-bold" style="color: var(--accent-sapphire);">(<?= e($total) ?> Terdata)</span></h1>
        <p class="page-subtitle">Kelola akun administrator, tenaga pengajar (guru), dan peserta didik (siswa)</p>
    </div>

    <div class="d-flex align-items-center gap-2">
        <a class="btn btn-outline-secondary btn-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm" href="<?= e(url('/admin/pengguna/impor')) ?>">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            <span>Impor CSV</span>
        </a>
        <a class="btn btn-primary btn-sm px-3.5 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" href="<?= e(url('/admin/pengguna/form')) ?>">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tambah Pengguna</span>
        </a>
    </div>
</div>

<!-- Search & Filtering Box -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">
        <form method="get" action="<?= e(url('/admin/pengguna')) ?>" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label for="q" class="form-label fw-bold">Pencarian Nama / Username</label>
                <div class="position-relative">
                    <input type="search" class="form-control py-2" id="q" name="q"
                           value="<?= e($filter['q']) ?>" placeholder="Ketik nama atau username...">
                </div>
            </div>

            <div class="col-6 col-md-2">
                <label for="role" class="form-label fw-bold">Peran Akses</label>
                <select class="form-select py-2" id="role" name="role">
                    <option value="">Semua Peran</option>
                    <?php foreach (['admin', 'guru', 'siswa'] as $r): ?>
                        <option value="<?= e($r) ?>" <?= $filter['role'] === $r ? 'selected' : '' ?>>
                            <?= e(ucfirst($r)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-3">
                <label for="kelas" class="form-label fw-bold">Kelas Siswa</label>
                <select class="form-select py-2" id="kelas" name="kelas">
                    <option value="">Semua Rombel</option>
                    <?php foreach ($kelas as $k): ?>
                        <option value="<?= e($k['id']) ?>"
                            <?= $filter['kelas'] === (int) $k['id'] ? 'selected' : '' ?>>
                            <?= e($k['nama']) ?> (<?= e($k['tahun_nama']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <label for="status" class="form-label fw-bold">Status Akun</label>
                <select class="form-select py-2" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="aktif"    <?= $filter['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= $filter['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>

            <div class="col-6 col-md-1">
                <button class="btn btn-primary w-100 py-2 fw-semibold">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="min-width: 180px;">Nama Lengkap</th>
                    <th>Username</th>
                    <th>Peran</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th class="text-end">Aksi Akun</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data === []): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color: var(--slate-muted);">
                            <div class="mb-2">ðŸ”</div>
                            Tidak ada pengguna yang cocok dengan kriteria pencarian.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $u): ?>
                    <?php
                    $roleBadge = match ($u['role']) {
                        'admin' => '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-0.5" style="font-size: 0.72rem;">ADMIN</span>',
                        'guru'  => '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.72rem;">GURU</span>',
                        default => '<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 0.72rem;">SISWA</span>',
                    };
                    ?>
                    <tr>
                        <td>
                            <div class="fw-bold" style="color: var(--slate-primary); font-size: 0.92rem;"><?= e($u['nama']) ?></div>
                        </td>
                        <td>
                            <code class="px-2 py-1 rounded" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-secondary); font-size: 0.8rem;"><?= e($u['username']) ?></code>
                        </td>
                        <td><?= $roleBadge ?></td>
                        <td>
                            <?php if (!empty($u['kelas_nama'])): ?>
                                <span class="badge fw-bold" style="background: rgba(15, 23, 42, 0.05); color: var(--slate-primary); border-radius: 6px;">
                                    <?= e($u['kelas_nama']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted small">&mdash;</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($u['status'] === 'aktif'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5" style="font-size: 0.72rem;">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle px-2 py-0.5" style="font-size: 0.72rem;">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap gap-1.5 justify-content-end">
                                <a class="btn btn-sm btn-outline-primary px-2.5 py-1"
                                   href="<?= e(url('/admin/pengguna/form/' . $u['id'])) ?>">Ubah</a>

                                <button class="btn btn-sm btn-outline-secondary px-2.5 py-1" type="button"
                                        data-bs-toggle="modal" data-bs-target="#reset<?= e($u['id']) ?>">
                                    Reset Password
                                </button>

                                <?php if ((int) $u['id'] !== (int) Auth::id()): ?>
                                    <form method="post" action="<?= e(url('/admin/pengguna/status')) ?>"
                                          data-konfirmasi="Ubah status akses pengguna ini?">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($u['id']) ?>">
                                        <input type="hidden" name="status"
                                               value="<?= $u['status'] === 'aktif' ? 'nonaktif' : 'aktif' ?>">
                                        <button class="btn btn-sm btn-outline-<?= $u['status'] === 'aktif' ? 'danger' : 'success' ?> px-2.5 py-1">
                                            <?= $u['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                            <!-- Modal Reset Password -->
                            <div class="modal fade" id="reset<?= e($u['id']) ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form class="modal-content text-start" method="post" action="<?= e(url('/admin/pengguna/reset')) ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($u['id']) ?>">
                                        <div class="modal-header py-3">
                                            <h5 class="modal-title fw-bold" style="color: var(--slate-primary);">Reset Password Pengguna</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <p class="small mb-3" style="color: var(--slate-tertiary);">
                                                Ketikkan password baru untuk akun <strong><?= e($u['nama']) ?></strong> (<code><?= e($u['username']) ?></code>):
                                            </p>
                                            <label class="form-label fw-bold">Password Baru <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control py-2" name="password"
                                                   minlength="8" required autocomplete="off"
                                                   placeholder="Minimal 8 karakter...">
                                        </div>
                                        <div class="modal-footer py-2.5">
                                            <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Password</button>
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
        <div class="card-footer bg-transparent py-3 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important;">
            <nav aria-label="Navigasi halaman">
                <ul class="pagination pagination-sm mb-0 flex-wrap gap-1">
                    <li class="page-item <?= $halaman <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link rounded-2" href="<?= e($tautanHalaman(max(1, $halaman - 1))) ?>">&larr; Sebelumnya</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                        <li class="page-item <?= $i === $halaman ? 'active' : '' ?>">
                            <a class="page-link rounded-2" href="<?= e($tautanHalaman($i)) ?>"><?= e($i) ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $halaman >= $total_halaman ? 'disabled' : '' ?>">
                        <a class="page-link rounded-2" href="<?= e($tautanHalaman(min($total_halaman, $halaman + 1))) ?>">Berikutnya &rarr;</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>
