<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h4 mb-1 text-white fw-bold">Video Pembelajaran</h1>
        <p class="text-white-50 small mb-0">Kelola materi interaktif video (unggah langsung atau tautan YouTube/Google Drive)</p>
    </div>
    <?php if ($pengampu !== []): ?>
        <a class="btn btn-primary d-inline-flex align-items-center gap-2" href="<?= e(url('/guru/video/form')) ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tambah Video</span>
        </a>
    <?php endif; ?>
</div>

<?php if ($pengampu === []): ?>
    <div class="alert alert-warning border-0 shadow-sm">
        Anda belum ditetapkan sebagai guru pengampu di kelas mana pun. Hubungi administrator sekolah.
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Judul Video</th>
                    <th>Tipe</th>
                    <th>Kelas & Mapel</th>
                    <th>Durasi</th>
                    <th>Diunggah</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data === []): ?>
                    <tr><td colspan="6" class="text-center text-body-secondary py-5">
                        <div class="mb-2">🎬</div>
                        Belum ada video pembelajaran. Klik tombol "Tambah Video" untuk menambahkan video pertama Anda.
                    </td></tr>
                <?php endif; ?>

                <?php foreach ($data as $v): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold text-white"><?= e($v['judul']) ?></div>
                            <?php if (($v['deskripsi'] ?? '') !== ''): ?>
                                <div class="small text-white-50">
                                    <?= e(mb_strimwidth((string) $v['deskripsi'], 0, 85, '...')) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($v['tipe'] === 'youtube'): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" class="me-1"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31.6 31.6 0 0 0 0 12c0 2 .2 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1c.3-1.9.5-3.8.5-5.8 0-2-.2-3.9-.5-5.8zM9.5 15.5v-7l6.2 3.5-6.2 3.5z"/></svg>
                                    YouTube
                                </span>
                            <?php elseif ($v['tipe'] === 'gdrive'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    Google Drive
                                </span>
                            <?php else: ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                    Berkas MP4
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="small fw-semibold"><?= e($v['kelas_nama']) ?></div>
                            <div class="small text-white-50"><?= e($v['mapel_nama']) ?></div>
                        </td>
                        <td class="small text-white-50">
                            <?= e($v['durasi'] ?? '—') ?>
                        </td>
                        <td class="small text-white-50"><?= e(tanggal_id($v['created_at'])) ?></td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <?php if ($v['tipe'] === 'upload'): ?>
                                    <a class="btn btn-sm btn-outline-info" target="_blank"
                                       href="<?= e(url('/guru/video/stream/' . $v['id'])) ?>">Putar</a>
                                <?php elseif ($v['tipe'] === 'youtube'): ?>
                                    <a class="btn btn-sm btn-outline-info" target="_blank"
                                       href="<?= e($v['url_embed']) ?>">Buka</a>
                                <?php endif; ?>
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?= e(url('/guru/video/form/' . $v['id'])) ?>">Ubah</a>
                                <form method="post" action="<?= e(url('/guru/video/hapus')) ?>"
                                      data-konfirmasi="Hapus video ini beserta berkasnya?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($v['id']) ?>">
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
