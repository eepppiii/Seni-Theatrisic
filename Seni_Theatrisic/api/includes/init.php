<?php
// api/includes/init.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

// ============ DETEKSI CLOUD (Railway) ============
function isCloud()
{
    // Railway
    if (isset($_ENV['RAILWAY_ENVIRONMENT']) || isset($_SERVER['RAILWAY_ENVIRONMENT']))
        return true;
    return false;
}

// ============ BASE URL (Untuk Aset CSS/JS/Gambar) ============
function baseUrl()
{
    // Di Cloud, assets berada di root document root (api/)
    if (isCloud())
        return '/';

    // Di lokal (Laragon)
    $script = $_SERVER['SCRIPT_NAME'];      // /Seni_Theatrisic/api/Seni/list.php
    $root = rtrim(dirname($script), '/\\'); // /Seni_Theatrisic/api/Seni
    $root = str_replace('\\', '/', $root);

    // Naik ke folder utama project
    $base = basename($root);
    while (in_array($base, ['Seni', 'Anggota', 'includes', 'config', 'auth', 'api'])) {
        $root = dirname($root);
        $base = basename($root);
    }
    // Hasil: /Seni_Theatrisic
    // Tambahkan 'api/' karena document root lokal kita adalah folder api
    return rtrim($root, '/\\') . '/api/';
}

// ============ APP URL (Untuk Link Halaman PHP) ============
function appUrl($path = '')
{
    return baseUrl() . ltrim($path, '/');
}

// ============ HELPERS ============
function e($str)
{
    return htmlspecialchars((string) $str, ENT_QUOTES, 'UTF-8');
}

function setFlash($type, $pesan)
{
    $_SESSION['flash'] = ['type' => $type, 'pesan' => $pesan];
}

function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}
?>