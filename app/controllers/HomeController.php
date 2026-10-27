<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;

/**
 * Halaman depan: antar pengguna ke dashboard atau ke login.
 */
final class HomeController extends Controller
{
    public function index(): void
    {
        redirect(Auth::sudahLogin() ? Auth::dashboardPath() : '/login');
    }
}
