<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// DATA HOST YANG BENAR (Tanpa https://)
$host = 'db.quxhhvvkwrruvpwfysqi.supabase.co'; 
$db   = 'postgres';
$user = 'postgres';
$pass = 'NyeblakSelalu2@'; 
$port = '5432';

try {
    // Format DSN untuk PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $conn = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
     // Menampilkan pesan error jika koneksi gagal
     die("Koneksi gagal: " . $e->getMessage());
}
?>