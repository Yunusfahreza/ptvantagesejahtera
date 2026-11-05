<?php

use App\Core\Auth;

$user = Auth::user();
?>
<!-- Top Greeting Strip -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge rounded-pill" style="background: rgba(15, 23, 42, 0.08); color: var(--slate-primary); font-weight: 700; font-size: 0.75rem;">
                SEMESTER GANJIL
            </span>
            <span class="small" style="color: var(--slate-tertiary);">&bull; SMAN 10 Garut</span>
        </div>
        <h2 class="h3 fw-bold mb-1" style="color: var(--slate-primary); letter-spacing: -0.02em;">
            Selamat Datang, <?= e($user['nama'] ?? 'Siswa') ?>
        </h2>
        <p class="small mb-0" style="color: var(--slate-secondary);">
            <?php if (!empty($mapel[0]['kelas_nama'])): ?>
                Terdaftar di <strong>Kelas <?= e($mapel[0]['kelas_nama']) ?></strong> &bull;
            <?php endif; ?>
            Pantau jadwal, materi pembelajaran, dan tugas Anda dalam antarmuka terpadu.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= e(url('/siswa/mapel')) ?>" class="control-capsule-btn" style="border-radius: 9999px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
            </svg>
            <span>Semua Mapel</span>
        </a>
        <a href="<?= e(url('/perpustakaan')) ?>" class="control-capsule-btn control-capsule-dark" style="border-radius: 9999px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
            </svg>
            <span>E-Perpustakaan</span>
        </a>
    </div>
</div>

<!-- ==========================================================================
     BARIS 1: GANTT CHART JADWAL (KIRI), STATISTIK ONGOING (TENGAH), VIDEO PREVIEW (KANAN)
     ========================================================================== -->
<div class="row g-3 mb-4">
    <!-- 1. GANTT CHART JADWAL SESI (Sesuai Referensi Gambar Kiri Atas) -->
    <div class="col-12 col-xl-7">
        <div class="card gantt-chart-card h-100 border-0">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; background: rgba(15, 23, 42, 0.06); color: var(--slate-primary);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </span>
                    <h3 class="h6 fw-bold mb-0" style="color: var(--slate-primary);">Jadwal Sesi Pembelajaran Hari Ini</h3>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill bg-white border text-dark px-2.5 py-1" style="font-size: 0.72rem; font-weight: 700;">
                        Hari Ini
                    </span>
                    <span class="badge rounded-pill text-white px-2.5 py-1" style="background: #0f172a; font-size: 0.72rem; font-weight: 700;">
                        AKTIF
                    </span>
                </div>
            </div>

            <!-- Horizontal Gantt Timeline Container (Fully responsive with touch swipe) -->
            <div class="gantt-timeline-container p-3 rounded-4 mb-2" style="background: rgba(248, 250, 252, 0.9); border: 1.5px solid rgba(226, 232, 240, 0.9);">
                <div class="gantt-timeline-inner">
                    <!-- Time Markers Axis -->
                    <div class="d-flex justify-content-between small fw-bold mb-3 pb-2" style="border-bottom: 1.5px dashed rgba(203, 213, 225, 0.8); color: var(--slate-tertiary); font-size: 0.75rem;">
                        <span>07:00</span>
                        <span>09:00</span>
                        <span>11:00</span>
                        <span>13:00</span>
                        <span>15:00</span>
                    </div>

                    <!-- Gantt Lanes with Dark Capsule Bars & Teacher Avatars -->
                    <div class="d-flex flex-column gap-3">
                        <!-- Lane 1: Matematika -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="small fw-bold text-truncate" style="width: 85px; flex-shrink: 0; color: var(--slate-primary); font-size: 0.78rem;">
                                Matematika
                            </span>
                            <div class="flex-grow-1 position-relative">
                                <div class="gantt-capsule-bar w-auto" style="margin-left: 6%; max-width: 82%;">
                                    <span>2 Jam &bull; Eksponen & Logaritma</span>
                                    <span class="gantt-avatar-pill" title="Dra. Siti Rahmawati">SR</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lane 2: Fisika -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="small fw-bold text-truncate" style="width: 85px; flex-shrink: 0; color: var(--slate-primary); font-size: 0.78rem;">
                                Fisika
                            </span>
                            <div class="flex-grow-1 position-relative">
                                <div class="gantt-capsule-bar w-auto" style="margin-left: 30%; max-width: 65%; background: #1e293b;">
                                    <span>2 Jam &bull; Gerak Lurus Beraturan</span>
                                    <span class="gantt-avatar-pill" title="Ahmad Fauzi, M.Pd">AF</span>
                                </div>
                            </div>
                        </div>

                        <!-- Lane 3: B. Indonesia -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="small fw-bold text-truncate" style="width: 85px; flex-shrink: 0; color: var(--slate-primary); font-size: 0.78rem;">
                                B. Indonesia
                            </span>
                            <div class="flex-grow-1 position-relative">
                                <div class="gantt-capsule-bar w-auto" style="margin-left: 55%; max-width: 42%;">
                                    <span>1.5 Jam &bull; Teks Laporan Observasi</span>
                                    <span class="gantt-avatar-pill" title="Nurul Hidayah, S.Pd">NH</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between px-1 mt-2">
                <span class="small" style="color: var(--slate-tertiary); font-size: 0.75rem;">
                    Jadwal terhubung langsung dengan kalender pengampu kelas.
                </span>
                <a href="<?= e(url('/siswa/jadwal')) ?>" class="small fw-bold text-decoration-none" style="color: var(--slate-primary); font-size: 0.78rem;">
                    Lihat Seluruh Pekan &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- 2. ONGOING PROGRESS STATISTIK (Sesuai Referensi Gambar Tengah Atas) -->
    <div class="col-12 col-md-6 col-xl-2">
        <div class="card h-100 border-0 p-3" style="border-radius: 30px; background: rgba(255, 255, 255, 0.88); border: 1.5px solid rgba(255, 255, 255, 0.95); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small fw-bold text-uppercase" style="color: var(--slate-tertiary); font-size: 0.68rem; letter-spacing: 0.05em;">Progres Belajar</span>
                <span class="badge rounded-circle p-1" style="background: rgba(15, 23, 42, 0.06); color: var(--slate-primary);">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                        <polyline points="17 6 23 6 23 12"/>
                    </svg>
                </span>
            </div>

            <div class="mb-2">
                <h3 class="display-6 fw-bold mb-0" style="color: var(--slate-primary); font-size: 2.2rem; letter-spacing: -0.03em;">85,4%</h3>
                <span class="small" style="color: #059669; font-size: 0.72rem; font-weight: 700;">&uarr; +12% dibanding bulan lalu</span>
            </div>

            <!-- Smooth SVG Wave Curve Chart -->
            <div class="my-2" style="height: 38px;">
                <svg viewBox="0 0 100 30" preserveAspectRatio="none" style="width: 100%; height: 100%; overflow: visible;">
                    <path d="M0 25 C20 28, 40 10, 60 18 C80 25, 90 5, 100 8" fill="none" stroke="#0f172a" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="small pt-2 border-top" style="border-color: rgba(226, 232, 240, 0.8) !important; font-size: 0.74rem;">
                <div class="d-flex justify-content-between mb-1" style="color: var(--slate-secondary);">
                    <span>&bull; Tugas Tuntas</span>
                    <strong style="color: var(--slate-primary);">4/5</strong>
                </div>
                <div class="d-flex justify-content-between mb-1" style="color: var(--slate-secondary);">
                    <span>&bull; Kuis Lulus</span>
                    <strong style="color: var(--slate-primary);">2</strong>
                </div>
                <div class="d-flex justify-content-between" style="color: var(--slate-secondary);">
                    <span>&bull; Kehadiran</span>
                    <strong style="color: #059669;">98%</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. FILE / VIDEO PREVIEW WIDGET (Sesuai Referensi Gambar Kanan Atas: Kartu Gelap 3D) -->
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card preview-dark-card h-100 border-0 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-circle p-1.5" style="background: rgba(255, 255, 255, 0.15); color: #ffffff;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polygon points="5 3 19 12 5 21 5 3"/>
                            </svg>
                        </span>
                        <span class="small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem; color: #94a3b8;">File Preview</span>
                    </div>
                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(255, 255, 255, 0.12); color: #ffffff; font-size: 0.68rem; font-weight: 700;">
                        18:45 MIN
                    </span>
                </div>

                <!-- Video Thumbnail / 3D Wave Visual -->
                <div class="position-relative rounded-4 overflow-hidden mb-3" style="height: 120px; background: #000000; border: 1px solid rgba(255, 255, 255, 0.2);">
                    <video id="dashboardMiniVideo" loop playsinline preload="metadata" style="width: 100%; height: 100%; object-fit: cover;" poster="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?auto=format&fit=crop&w=600&q=80">
                        <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" type="video/mp4">
                    </video>
                    
                    <div id="dashboardVideoOverlay" class="position-absolute inset-0 d-flex align-items-center justify-content-center"
                         style="top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.35); cursor: pointer;"
                         onclick="togglePlayDashboardVideo()">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 44px; height: 44px; background: #ffffff; color: #0f172a; box-shadow: 0 4px 14px rgba(0,0,0,0.4);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="5 3 19 12 5 21 5 3"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="fw-bold text-truncate" style="color: #ffffff; font-size: 0.88rem;">
                        Konsep Eksponen & Akar.mp4
                    </div>
                    <div class="small" style="color: #94a3b8; font-size: 0.72rem;">
                        Matematika Peminatan Kelas X
                    </div>
                </div>
            </div>

            <!-- Bottom Teacher Meta -->
            <div class="d-flex align-items-center justify-content-between pt-2" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                         style="width: 28px; height: 28px; background: #ffffff; color: #0f172a; font-size: 0.72rem;">
                        SR
                    </div>
                    <span class="small fw-semibold text-truncate" style="color: #ffffff; font-size: 0.76rem; max-width: 120px;">
                        Dra. Siti Rahmawati
                    </span>
                </div>
                <button type="button" class="btn btn-sm btn-light py-1 px-2.5" style="border-radius: 9999px; font-size: 0.72rem; font-weight: 700;" data-bs-toggle="modal" data-bs-target="#fullscreenVideoModal">
                    Buka
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     BARIS 2: ALL FILES (KAPSUL OVAL KIRI) & AKTIVITAS MODUL KELAS (KANAN)
     ========================================================================== -->
<div class="row g-3">
    <!-- 4. ALL FILES / BERKAS MATERI (Sesuai Referensi Gambar Kiri Bawah: Capsule File Rows) -->
    <div class="col-12 col-xl-4">
        <div class="card h-100 border-0 p-3" style="border-radius: 30px; background: rgba(255, 255, 255, 0.88); border: 1.5px solid rgba(255, 255, 255, 0.95); box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; background: #0f172a; color: #ffffff;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        </svg>
                    </span>
                    <h3 class="h6 fw-bold mb-0" style="color: var(--slate-primary);">All Files &bull; Berkas Belajar</h3>
                </div>
                <span class="badge rounded-pill bg-white border text-secondary px-2.5 py-1" style="font-size: 0.72rem; font-weight: 700;">
                    <?= e($jumlah_materi) ?> Berkas
                </span>
            </div>

            <!-- Horizontal Oval Capsule Rows (Exact Replication from the Reference) -->
            <div class="d-flex flex-column mb-2">
                <a href="<?= e(url('/siswa/materi')) ?>" class="file-capsule-item">
                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                              style="width: 30px; height: 30px; background: rgba(239, 68, 68, 0.1); color: #dc2626;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </span>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-truncate" style="font-size: 0.79rem;">Modul Eksponen & Logaritma.pdf</div>
                            <div class="small" style="font-size: 0.68rem; color: var(--slate-tertiary);">Matematika &bull; 2.4 MB</div>
                        </div>
                    </div>
                    <span class="badge rounded-pill bg-white border text-dark shadow-sm px-2 py-1" style="font-size: 0.68rem; font-weight: 700;">
                        Unduh
                    </span>
                </a>

                <a href="<?= e(url('/siswa/materi')) ?>" class="file-capsule-item">
                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                              style="width: 30px; height: 30px; background: rgba(37, 99, 235, 0.1); color: #2563eb;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </span>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-truncate" style="font-size: 0.79rem;">LKS Dinamika Gerak Lurus.docx</div>
                            <div class="small" style="font-size: 0.68rem; color: var(--slate-tertiary);">Fisika &bull; 1.1 MB</div>
                        </div>
                    </div>
                    <span class="badge rounded-pill bg-white border text-dark shadow-sm px-2 py-1" style="font-size: 0.68rem; font-weight: 700;">
                        Unduh
                    </span>
                </a>

                <a href="<?= e(url('/siswa/materi')) ?>" class="file-capsule-item">
                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                              style="width: 30px; height: 30px; background: rgba(5, 150, 105, 0.1); color: #059669;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="23 7 16 12 23 17 23 7"/>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                            </svg>
                        </span>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-truncate" style="font-size: 0.79rem;">Simulasi Hukum Gravitasi.mp4</div>
                            <div class="small" style="font-size: 0.68rem; color: var(--slate-tertiary);">Fisika &bull; 14.8 MB</div>
                        </div>
                    </div>
                    <span class="badge rounded-pill bg-white border text-dark shadow-sm px-2 py-1" style="font-size: 0.68rem; font-weight: 700;">
                        Tonton
                    </span>
                </a>

                <a href="<?= e(url('/siswa/materi')) ?>" class="file-capsule-item mb-0">
                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                              style="width: 30px; height: 30px; background: rgba(217, 119, 6, 0.1); color: #d97706;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </span>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-truncate" style="font-size: 0.79rem;">Panduan Teks Observasi.pdf</div>
                            <div class="small" style="font-size: 0.68rem; color: var(--slate-tertiary);">B. Indonesia &bull; 3.5 MB</div>
                        </div>
                    </div>
                    <span class="badge rounded-pill bg-white border text-dark shadow-sm px-2 py-1" style="font-size: 0.68rem; font-weight: 700;">
                        Unduh
                    </span>
                </a>
            </div>

            <div class="text-center pt-2">
                <a href="<?= e(url('/siswa/materi')) ?>" class="small fw-bold text-decoration-none" style="color: var(--slate-primary); font-size: 0.78rem;">
                    Buka Seluruh Materi Pembelajaran &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- 5. TEAM / AKTIVITAS KELAS BERJALAN (Sesuai Referensi Gambar Kanan Bawah: Wide Dark Team Card) -->
    <div class="col-12 col-xl-8">
        <div class="card team-dark-card h-100 border-0 d-flex flex-column justify-content-between">
            <!-- Header bar of the wide team card -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3" style="border-bottom: 1px solid rgba(255, 255, 255, 0.12);">
                <div class="d-flex align-items-center gap-2">
                    <span class="small fw-bold text-uppercase" style="letter-spacing: 0.08em; font-size: 0.85rem; color: #ffffff;">Aktivitas Kelas</span>
                    <span class="badge rounded-pill" style="background: rgba(255, 255, 255, 0.15); color: #ffffff; font-size: 0.68rem; font-weight: 700;">
                        &rarr;
                    </span>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="small" style="color: #94a3b8; font-size: 0.72rem;">Total Siswa:</span>
                        <strong class="text-white" style="font-size: 0.88rem;">36 Siswa</strong>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small" style="color: #94a3b8; font-size: 0.72rem;">Siswa Aktif:</span>
                        <strong style="color: #10b981; font-size: 0.88rem;">34 Aktif</strong>
                    </div>
                    <div class="d-none d-md-flex align-items-center gap-2">
                        <span class="small" style="color: #94a3b8; font-size: 0.72rem;">Dinamika:</span>
                        <svg width="45" height="18" viewBox="0 0 50 20" style="overflow: visible;">
                            <path d="M0 16 Q15 2, 30 14 T50 4" fill="none" stroke="#ffffff" stroke-width="2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 3 Sub-Columns: Tugas, Kuis CBT, dan Performa Equalizer -->
            <div class="row g-4">
                <!-- Col 1: Tugas & Evaluasi -->
                <div class="col-12 col-md-4">
                    <div class="mb-2">
                        <div class="small fw-bold text-white mb-1" style="font-size: 0.86rem;">Tugas & Evaluasi</div>
                        <div class="small" style="color: #94a3b8; font-size: 0.72rem;">
                            <?= count($tugas_mendekat) ?> tugas belum diserahkan
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 my-2">
                        <div class="progress flex-grow-1" style="height: 6px; background: rgba(255, 255, 255, 0.15); border-radius: 9999px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 75%; border-radius: 9999px;"></div>
                        </div>
                        <span class="small fw-bold text-white" style="font-size: 0.72rem;">75%</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                        <a href="<?= e(url('/siswa/tugas')) ?>" class="small text-decoration-none" style="color: #60a5fa; font-size: 0.75rem;">
                            Lihat Tugas &rarr;
                        </a>
                        <span class="badge rounded-pill" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; font-size: 0.65rem;">
                            Prioritas
                        </span>
                    </div>
                </div>

                <!-- Col 2: Kuis & Ujian CBT -->
                <div class="col-12 col-md-4">
                    <div class="mb-2">
                        <div class="small fw-bold text-white mb-1" style="font-size: 0.86rem;">Kuis & Ujian CBT</div>
                        <div class="small" style="color: #94a3b8; font-size: 0.72rem;">
                            <?= count($kuis_terbuka) ?> ujian sedang terbuka
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 my-2">
                        <div class="progress flex-grow-1" style="height: 6px; background: rgba(255, 255, 255, 0.15); border-radius: 9999px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 90%; border-radius: 9999px;"></div>
                        </div>
                        <span class="small fw-bold text-white" style="font-size: 0.72rem;">90%</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                        <a href="<?= e(url('/siswa/kuis')) ?>" class="small text-decoration-none" style="color: #60a5fa; font-size: 0.75rem;">
                            Ruang Ujian &rarr;
                        </a>
                        <span class="badge rounded-pill" style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 0.65rem;">
                            Tersedia
                        </span>
                    </div>
                </div>

                <!-- Col 3: Performa Akademik & Equalizer Graphic -->
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <div class="small fw-bold text-white mb-1" style="font-size: 0.86rem;">Performa Nilai</div>
                            <div class="small" style="color: #94a3b8; font-size: 0.72rem;">
                                Rata-rata IPK: 3.82 / 4.0
                            </div>
                        </div>
                        <!-- Mini Graphic Equalizer (Exact Replication from Development in Ref Image) -->
                        <div class="d-flex align-items-end gap-1" style="height: 24px;">
                            <span style="width: 3px; height: 8px; background: #ffffff; border-radius: 1px;"></span>
                            <span style="width: 3px; height: 16px; background: #ffffff; border-radius: 1px;"></span>
                            <span style="width: 3px; height: 22px; background: #ffffff; border-radius: 1px;"></span>
                            <span style="width: 3px; height: 14px; background: #ffffff; border-radius: 1px;"></span>
                            <span style="width: 3px; height: 20px; background: #ffffff; border-radius: 1px;"></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 my-2">
                        <div class="progress flex-grow-1" style="height: 6px; background: rgba(255, 255, 255, 0.15); border-radius: 9999px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 95%; border-radius: 9999px;"></div>
                        </div>
                        <span class="small fw-bold text-white" style="font-size: 0.72rem;">95%</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-3 pt-2" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                        <a href="<?= e(url('/siswa/nilai')) ?>" class="small text-decoration-none" style="color: #60a5fa; font-size: 0.75rem;">
                            Buka Rapor &rarr;
                        </a>
                        <span class="badge rounded-pill" style="background: rgba(96, 165, 250, 0.2); color: #93c5fd; font-size: 0.65rem;">
                            Sangat Baik
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================================================
     MODAL FULLSCREEN VIDEO PLAYER (PURE CRYSTAL GLASS)
     ========================================================================== -->
<div class="modal fade" id="fullscreenVideoModal" tabindex="-1" aria-labelledby="fullscreenVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-4" style="border-radius: 28px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(25px); border: 1.5px solid rgba(255, 255, 255, 0.9);">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="badge rounded-pill mb-1" style="background: rgba(15, 23, 42, 0.08); color: var(--slate-primary); font-weight: 700; font-size: 0.72rem;">VIDEO PEMBELAJARAN</span>
                    <h3 class="h5 fw-bold mb-0" id="fullscreenVideoModalLabel" style="color: var(--slate-primary);">
                        Konsep Eksponen & Bentuk Akar &bull; Kelas X
                    </h3>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="rounded-4 overflow-hidden mb-3" style="width: 100%; height: 380px; background: #000000; box-shadow: var(--shadow-luxury-card); border: 2px solid #ffffff;">
                <video id="modalVideoElement" controls playsinline style="width: 100%; height: 100%; object-fit: contain;">
                    <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" type="video/mp4">
                    Browser tidak mendukung video HTML5.
                </video>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <span class="small" style="color: var(--slate-tertiary); font-size: 0.78rem;">
                    Pengampu: Dra. Siti Rahmawati &bull; Kualitas Full HD 1080p
                </span>
                <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal" style="border-radius: 9999px; font-weight: 600;">
                    Tutup Pemutar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const miniVideo = document.getElementById('dashboardMiniVideo');
    const overlay = document.getElementById('dashboardVideoOverlay');
    const modalVideo = document.getElementById('modalVideoElement');
    const videoModal = document.getElementById('fullscreenVideoModal');

    function togglePlayDashboardVideo() {
        if (!miniVideo) return;
        if (miniVideo.paused) {
            miniVideo.play();
            if (overlay) overlay.style.opacity = '0';
        } else {
            miniVideo.pause();
            if (overlay) overlay.style.opacity = '1';
        }
    }

    if (miniVideo) {
        miniVideo.addEventListener('play', () => { if (overlay) overlay.style.opacity = '0'; });
        miniVideo.addEventListener('pause', () => { if (overlay) overlay.style.opacity = '1'; });
    }

    if (videoModal && modalVideo) {
        videoModal.addEventListener('shown.bs.modal', () => {
            if (miniVideo && !miniVideo.paused) miniVideo.pause();
            modalVideo.currentTime = miniVideo ? miniVideo.currentTime : 0;
            modalVideo.play();
        });
        videoModal.addEventListener('hidden.bs.modal', () => {
            modalVideo.pause();
        });
    }
</script>
