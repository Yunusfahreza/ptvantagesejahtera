<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 mb-1 text-white fw-bold">Catatan Belajar Saya</h1>
        <p class="text-white-50 small mb-0">Buku catatan digital pribadi untuk menulis rangkuman, rumus, dan materi penting</p>
    </div>
    <a class="btn btn-primary d-inline-flex align-items-center gap-2" href="<?= e(url('/siswa/catatan/form')) ?>">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        <span>Buat Catatan Baru</span>
    </a>
</div>

<div class="row g-4">
    <?php if ($data === []): ?>
        <div class="col-12">
            <div class="card shadow-sm border-0 text-center py-5">
                <div class="text-body-secondary">
                    <div class="fs-1 mb-2">📝</div>
                    <p class="mb-2">Anda belum memiliki catatan belajar pribadi.</p>
                    <a href="<?= e(url('/siswa/catatan/form')) ?>" class="btn btn-sm btn-outline-primary">Mulai Menulis</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($data as $c): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 d-flex flex-column transition-hover">
                <div class="card-body p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                            <?= e($c['mapel_nama'] ?? 'Umum') ?>
                        </span>
                        <span class="small text-white-50"><?= e(tanggal_id($c['updated_at'])) ?></span>
                    </div>

                    <h5 class="card-title text-white fw-bold mb-2"><?= e($c['judul']) ?></h5>

                    <p class="card-text text-white-50 small flex-grow-1" style="white-space: pre-wrap; font-size: 0.85rem;">
                        <?= e(mb_strimwidth((string) $c['isi'], 0, 160, '...')) ?>
                    </p>

                    <div class="pt-3 border-top border-secondary-subtle d-flex justify-content-end gap-1 mt-auto">
                        <a href="<?= e(url('/siswa/catatan/form/' . $c['id'])) ?>" class="btn btn-sm btn-outline-primary">
                            Buka / Ubah
                        </a>
                        <form method="post" action="<?= e(url('/siswa/catatan/hapus')) ?>" data-konfirmasi="Hapus catatan ini?">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= e($c['id']) ?>">
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
