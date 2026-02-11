<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// GANTI DATA DI BAWAH INI DENGAN INFO DARI SUPABASE
$host = 'https://quxhhvvkwrruvpwfysqi.supabase.co'; // Contoh: db.abcxyz.supabase.co
$db   = 'postgres';
$user = 'postgres';
$pass = 'NyeblakSelalu2@'; // Password yang dibuat saat buat project
$port = '5432';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $conn = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
     die("Koneksi gagal: " . $e->getMessage());
}
?>