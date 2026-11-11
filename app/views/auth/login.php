<?php

use App\Core\Config;
?>
<div class="row g-4 g-xl-5 align-items-center">
    <!-- ======================================================================
         KOLOM KIRI: INFORMASI & PROFIL SISTEM PEMBELAJARAN (LMS SMAN 10 GARUT)
         ====================================================================== -->
    <div class="col-12 col-lg-7 text-start">
        <!-- Top Institutional Capsule Badge -->
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3"
             style="background: rgba(255, 255, 255, 0.85); border: 1.5px solid rgba(255, 255, 255, 0.95); box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04); backdrop-filter: blur(12px);">
            <div style="width: 8px; height: 8px; border-radius: 50%; background: #059669; box-shadow: 0 0 8px rgba(5, 150, 105, 0.6);"></div>
            <span class="small fw-bold" style="color: var(--slate-primary); font-size: 0.74rem; letter-spacing: 0.04em;">SMAN 10 GARUT</span>
            <span style="color: var(--slate-tertiary);">&bull;</span>
            <span class="small fw-semibold" style="color: var(--slate-secondary); font-size: 0.74rem;">Kurikulum Merdeka 2026/2027</span>
        </div>

        <!-- Main Title & Tagline -->
        <h1 class="display-6 fw-bold mb-3" style="color: var(--slate-primary); letter-spacing: -0.03em; line-height: 1.2;">
            Sistem Pembelajaran Digital Terintegrasi & Interaktif
        </h1>
        <p class="mb-4" style="color: var(--slate-secondary); font-size: 0.96rem; line-height: 1.6; max-width: 580px;">
            Platform resmi <strong style="color: var(--slate-primary);">LMS SMAN 10 Garut</strong> untuk memfasilitasi modul pembelajaran digital, kelas tatap muka Google Meet, penugasan mandiri, evaluasi CBT, serta pemantauan nilai dan presensi secara real-time.
        </p>

        <!-- Bento Feature Grid (4 Kartu Fitur Kunci LMS) -->
        <div class="row g-3 mb-4">
            <!-- Fitur 1: Materi & Video Modul -->
            <div class="col-12 col-sm-6">
                <div class="p-3 rounded-4 h-100"
                     style="background: rgba(255, 255, 255, 0.72); border: 1.5px solid rgba(255, 255, 255, 0.9); box-shadow: 0 8px 24px rgba(15, 23, 42, 0.03); backdrop-filter: blur(16px);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-3"
                             style="width: 34px; height: 34px; background: rgba(37, 99, 235, 0.08); color: var(--accent-sapphire);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                                <path d="M6 6h10"/><path d="M6 10h10"/>
                            </svg>
                        </div>
                        <h2 class="h6 fw-bold mb-0" style="color: var(--slate-primary); font-size: 0.88rem;">Materi & Modul Digital</h2>
                    </div>
                    <p class="small mb-0" style="color: var(--slate-secondary); font-size: 0.78rem; line-height: 1.5;">
                        Akses modul ajar, materi presentasi PDF, dan video interaktif guru per bab pertemuan.
                    </p>
                </div>
            </div>

            <!-- Fitur 2: Pengumpulan Tugas -->
            <div class="col-12 col-sm-6">
                <div class="p-3 rounded-4 h-100"
                     style="background: rgba(255, 255, 255, 0.72); border: 1.5px solid rgba(255, 255, 255, 0.9); box-shadow: 0 8px 24px rgba(15, 23, 42, 0.03); backdrop-filter: blur(16px);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-3"
                             style="width: 34px; height: 34px; background: rgba(16, 185, 129, 0.08); color: var(--accent-emerald);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M9 11l3 3L22 4"/>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                        </div>
                        <h2 class="h6 fw-bold mb-0" style="color: var(--slate-primary); font-size: 0.88rem;">Tugas & Evaluasi Daring</h2>
                    </div>
                    <p class="small mb-0" style="color: var(--slate-secondary); font-size: 0.78rem; line-height: 1.5;">
                        Pengumpulan tugas tepat waktu dengan feedback koreksi dan nilai transparan dari guru.
                    </p>
                </div>
            </div>

            <!-- Fitur 3: Ujian & Kuis CBT -->
            <div class="col-12 col-sm-6">
                <div class="p-3 rounded-4 h-100"
                     style="background: rgba(255, 255, 255, 0.72); border: 1.5px solid rgba(255, 255, 255, 0.9); box-shadow: 0 8px 24px rgba(15, 23, 42, 0.03); backdrop-filter: blur(16px);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-3"
                             style="width: 34px; height: 34px; background: rgba(245, 158, 11, 0.08); color: #d97706;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                        </div>
                        <h2 class="h6 fw-bold mb-0" style="color: var(--slate-primary); font-size: 0.88rem;">Ujian & Kuis CBT</h2>
                    </div>
                    <p class="small mb-0" style="color: var(--slate-secondary); font-size: 0.78rem; line-height: 1.5;">
                        Tes objektif berbasis komputer dengan pengatur waktu otomatis dan rekap skor instan.
                    </p>
                </div>
            </div>

            <!-- Fitur 4: Transkrip & Peringkat -->
            <div class="col-12 col-sm-6">
                <div class="p-3 rounded-4 h-100"
                     style="background: rgba(255, 255, 255, 0.72); border: 1.5px solid rgba(255, 255, 255, 0.9); box-shadow: 0 8px 24px rgba(15, 23, 42, 0.03); backdrop-filter: blur(16px);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-3"
                             style="width: 34px; height: 34px; background: rgba(139, 92, 246, 0.08); color: #7c3aed;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <line x1="18" y1="20" x2="18" y2="10"/>
                                <line x1="12" y1="20" x2="12" y2="4"/>
                                <line x1="6" y1="20" x2="6" y2="14"/>
                            </svg>
                        </div>
                        <h2 class="h6 fw-bold mb-0" style="color: var(--slate-primary); font-size: 0.88rem;">Buku Nilai & Analitik</h2>
                    </div>
                    <p class="small mb-0" style="color: var(--slate-secondary); font-size: 0.78rem; line-height: 1.5;">
                        Grafik interaktif progres belajar, distribusi predikat, serta leaderboard kelas.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Information Strip -->
        <div class="d-flex flex-wrap align-items-center gap-3 pt-3" style="border-top: 1.5px solid rgba(226, 232, 240, 0.8);">
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill bg-white border text-dark px-2.5 py-1" style="font-size: 0.72rem; font-weight: 700;">
                    6 Rombel Aktif
                </span>
                <span class="badge rounded-pill bg-white border text-dark px-2.5 py-1" style="font-size: 0.72rem; font-weight: 700;">
                    10 Mapel Pokok
                </span>
                <span class="badge rounded-pill text-white px-2.5 py-1" style="background: #0f172a; font-size: 0.72rem; font-weight: 700;">
                    1.5 Semester Aktif
                </span>
            </div>
            <span class="small" style="color: var(--slate-tertiary); font-size: 0.74rem;">
                Kerjasama Resmi PT. Vantage Sejahtera &bull; Garut, Jawa Barat
            </span>
        </div>
    </div>

    <!-- ======================================================================
         KOLOM KANAN: KARTU LOGIN PENGGUNA (UKURAN TETAP, POSISI SEBELAH KANAN)
         ====================================================================== -->
    <div class="col-12 col-lg-5 d-flex justify-content-center justify-content-lg-end">
        <div class="card spatial-panel border-0 p-3 p-sm-4 shadow-lg w-100" style="max-width: 440px; border-radius: 28px !important;">
            <div class="card-body p-2 p-sm-3">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-circle mb-3"
                         style="background: rgba(15, 23, 42, 0.05); border: 1.5px solid rgba(226, 232, 240, 0.9); box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--slate-primary);">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <h2 class="h4 fw-bold mb-1" style="color: var(--slate-primary); letter-spacing: -0.02em;">
                        <?= e(Config::get('app.nama')) ?>
                    </h2>
                    <p class="small mb-0" style="color: var(--slate-tertiary);">
                        Masuk dengan akun terdaftar SMAN 10 Garut
                    </p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2 px-3 mb-3 small border-0" role="alert" style="border-radius: 14px; background: rgba(225, 29, 72, 0.1); color: #be123c;">
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= e(url('/login')) ?>" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-3 text-start">
                        <label for="username" class="form-label small fw-semibold" style="color: var(--slate-secondary);">Username / NISN / NIP</label>
                        <input type="text" class="form-control" id="username" name="username"
                               value="<?= e($username ?? '') ?>" required autofocus
                               autocomplete="username" maxlength="50"
                               placeholder="Masukkan nama pengguna"
                               style="border-radius: 14px; padding: 10px 14px; border: 1.5px solid rgba(226, 232, 240, 0.9);">
                    </div>

                    <div class="mb-4 text-start">
                        <label for="password" class="form-label small fw-semibold" style="color: var(--slate-secondary);">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                               required autocomplete="current-password"
                               placeholder="Masukkan kata sandi"
                               style="border-radius: 14px; padding: 10px 14px; border: 1.5px solid rgba(226, 232, 240, 0.9);">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 mb-3 fw-bold"
                            style="border-radius: 9999px; background: #0f172a; border-color: #0f172a; box-shadow: 0 4px 14px rgba(15, 23, 42, 0.25);">
                        Masuk ke Portal
                    </button>
                </form>

                <div class="text-center mt-3 pt-3" style="border-top: 1.5px solid rgba(226, 232, 240, 0.8);">
                    <span class="small" style="color: var(--slate-tertiary); font-size: 0.74rem;">
                        Portal Akses Siswa, Guru & Tenaga Kependidikan
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

