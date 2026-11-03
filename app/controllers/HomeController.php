<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\View;

/**
 * Halaman depan: antar pengguna ke dashboard atau ke login.
 */
final class HomeController extends Controller
{
    public function index(): void
    {
        redirect(Auth::sudahLogin() ? Auth::dashboardPath() : '/login');
    }

    /**
     * UI Baru: Single-Page Monochromatic Glassmorphism.
     */
    public function glass(): void
    {
        redirect(Auth::sudahLogin() ? Auth::dashboardPath() : '/login');
    }
}
