<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <h1 class="h4 mb-3">Impor Siswa dari CSV</h1>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h2 class="h6">Format berkas</h2>
                <p class="small text-body-secondary mb-2">
                    Baris pertama wajib berisi header. Pemisah koma atau titik koma keduanya diterima.
                </p>
                <pre class="bg-body-tertiary p-3 rounded small mb-3">nama,username,password,kelas
Ahmad Fauzi,siswa.ahmad2,rahasia123,X-1
Bella Puspita,siswa.bella2,rahasia123,X-1</pre>

                <ul class="small text-body-secondary">
                    <li>Kolom <code>kelas</code> diisi nama kelas yang sudah ada di tahun ajaran aktif.</li>
                    <li>Username harus unik, 3 sampai 50 karakter: huruf kecil, angka, titik, garis bawah, strip.</li>
                    <li>Password minimal 8 karakter.</li>
                    <li>Baris yang gagal dilaporkan; baris yang valid tetap tersimpan.</li>
                </ul>

                <p class="small mb-0">
                    Kelas tersedia di tahun ajaran aktif:
                    <?php if ($kelas === []): ?>
                        <span class="text-danger">belum ada kelas di tahun ajaran aktif.</span>
                    <?php else: ?>
                        <?php foreach ($kelas as $k): ?>
                            <span class="badge text-bg-light border"><?= e($k['nama']) ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="card-footer bg-transparent">
                <a class="btn btn-sm btn-outline-secondary"
                   href="<?= e(url('/admin/pengguna/contoh-csv')) ?>">Unduh contoh CSV</a>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <form method="post" action="<?= e(url('/admin/pengguna/impor')) ?>"
                      enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="berkas" class="form-label">Berkas CSV</label>
                        <input type="file" class="form-control" id="berkas" name="berkas"
                               accept=".csv,text/csv" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Impor</button>
                        <a class="btn btn-outline-secondary" href="<?= e(url('/admin/pengguna')) ?>">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($hasil)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent fw-semibold">Hasil impor</div>
                <div class="card-body">
                    <p class="mb-2">
                        <span class="badge text-bg-success"><?= e($hasil['berhasil']) ?> baris berhasil</span>
                        <span class="badge text-bg-<?= $hasil['gagal'] === [] ? 'secondary' : 'danger' ?>">
                            <?= e(count($hasil['gagal'])) ?> baris gagal
                        </span>
                    </p>

                    <?php if ($hasil['gagal'] !== []): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:5rem">Baris</th>
                                        <th>Alasan</th>
                                        <th>Isi baris</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($hasil['gagal'] as $g): ?>
                                        <tr>
                                            <td class="text-center"><?= e($g['baris']) ?></td>
                                            <td><?= e($g['alasan']) ?></td>
                                            <td class="small text-body-secondary"><?= e($g['isi']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
