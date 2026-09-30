<?php
// api/config/koneksi.php

$host     = getenv('DB_HOST') ?: 'ep-delicate-thunder-b3r1ym8p-pooler.c-4.ap-southeast-1.aws.neon.tech';
$port     = getenv('DB_PORT') ?: '5432';
$dbname   = getenv('DB_NAME') ?: 'neondb';
$username = getenv('DB_USER') ?: 'neondb_owner';
$password = getenv('DB_PASS') ?: 'npg_GSUzgT4Pay2wep'; // Ganti jika sudah reset password

try {
    // DSN PostgreSQL dengan SSL wajib untuk Neon
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 10,
    ]);

} catch (PDOException $e) {
    die('<div style="padding:2rem;background:#f8d7da;color:#721c24;font-family:sans-serif;">
            <h3>Koneksi Database Gagal</h3>
            <p>' . htmlspecialchars($e->getMessage()) . '</p>
         </div>');
}
?>
