<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Tentukan apakah aplikasi sedang dalam mode pemeliharaan...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Daftarkan auto-loader Composer...
require __DIR__.'/../vendor/autoload.php';

// Jalankan aplikasi Laravel...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());