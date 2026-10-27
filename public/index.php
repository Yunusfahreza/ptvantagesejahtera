<?php

/**
 * Satu-satunya pintu masuk aplikasi (front controller).
 * Document root diarahkan ke folder ini.
 */

declare(strict_types=1);

use App\Core\Auth;
use App\Core\Autoloader;
use App\Core\Config;
use App\Core\HttpException;
use App\Core\Router;
use App\Core\View;

require __DIR__ . '/../app/core/Config.php';
require __DIR__ . '/../app/core/Autoloader.php';

Autoloader::daftar();

try {
    Config::muat(__DIR__ . '/../config/config.php');
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Konfigurasi belum siap: ' . $e->getMessage();
    exit;
}

require __DIR__ . '/../app/core/helpers.php';

date_default_timezone_set((string) Config::get('app.timezone', 'Asia/Jakarta'));

$debug = (bool) Config::get('app.debug', false);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// Header keamanan dasar.
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: same-origin');

Auth::mulaiSesi();

$router = new Router();
require __DIR__ . '/../app/routes.php';

$metode = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

// Buang sub-folder tempat aplikasi dipasang (mis. /ptvantagesejahtera/public).
$basePath = base_path();

if ($basePath !== '' && str_starts_with($path, $basePath)) {
    $path = substr($path, strlen($basePath));
}

try {
    $router->jalankan($metode, $path === '' ? '/' : $path);
} catch (HttpException $e) {
    http_response_code($e->status());
    View::render('errors/http', [
        'judul'  => $e->status() === 403 ? 'Akses Ditolak' : 'Tidak Ditemukan',
        'status' => $e->status(),
        'pesan'  => $e->getMessage(),
    ]);
} catch (Throwable $e) {
    error_log('[LMS] ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    View::render('errors/http', [
        'judul'  => 'Terjadi Kesalahan',
        'status' => 500,
        'pesan'  => $debug
            ? $e->getMessage() . ' (' . $e->getFile() . ':' . $e->getLine() . ')'
            : 'Terjadi kesalahan pada server. Silakan coba lagi atau hubungi administrator.',
    ]);
}
