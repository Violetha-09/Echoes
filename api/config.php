<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// DATA POOLER (Jalur khusus untuk Vercel agar tidak error)
$host = 'aws-0-ap-south-1.pooler.supabase.com'; 
$db   = 'postgres';
$user = 'postgres.quxhhvvkwrruvpwfysqi'; // User Pooler (pakai titik)
$pass = 'NyeblakSelalu2@'; 
$port = '6543'; // WAJIB 6543

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
     // Jika masih gagal, kita ingin lihat pesan error yang BARU
     die("Koneksi gagal: " . $e->getMessage());
}
?>