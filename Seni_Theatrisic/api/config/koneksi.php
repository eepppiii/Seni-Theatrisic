<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host     = 'ep-delicate-thunder-b3r1ym8p-pooler.c-4.ap-southeast-1.aws.neon.tech';
$port     = '5432';
$dbname   = 'neondb';
$username = 'neondb_owner';
$password = 'npg_ApB4LZy2CVXH'; 

try {
    // Menyesuaikan DSN dengan menambahkan channel_binding=require
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require;channel_binding=require";
    
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 15, // Naikkan timeout untuk membangunkan Neon
    ]);
    
} catch (PDOException $e) {
    echo "❌ KONEKSI GAGAL: " . $e->getMessage();
}
?>
