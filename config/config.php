<?php
/**
 * Salin file ini menjadi config/config.php lalu sesuaikan nilainya.
 * config/config.php TIDAK boleh di-commit.
 */

return [
    // Base URL aplikasi tanpa slash di akhir. Contoh XAMPP: http://localhost/ptvantagesejahtera/public
    'base_url' => '',

    'db' => [
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'name'     => 'lms_sma',
        'user'     => 'root',
        'pass'     => '',
        'charset'  => 'utf8mb4',
    ],

    'app' => [
        'nama'        => 'LMS SMAN 10 Garut',
        'timezone'    => 'Asia/Jakarta',
        // true hanya di mesin pengembangan
        'debug'       => true,
        // Set true kalau situs dilayani lewat HTTPS
        'https'       => false,
        'upload_dir'  => __DIR__ . '/../uploads',
        'upload_max'  => 10 * 1024 * 1024, // 10 MB
    ],

    'login' => [
        'max_gagal'    => 5,
        'tahan_detik'  => 300, // 5 menit
    ],
];
