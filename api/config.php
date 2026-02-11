<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// DATA DARI SUPABASE KAMU (Tanpa https://)
$host = 'db.quxhhvvkwrruvpwfysqi.supabase.co'; 
$db   = 'postgres';
$user = 'postgres';
$pass = 'NyeblakSelalu2@'; 
$port = '5432';

try {
    // Memperbaiki penulisan DSN agar terbaca oleh Vercel
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $conn = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
     // Ini akan menampilkan pesan jika masih gagal konek
     die("Koneksi gagal: " . $e->getMessage());
}
?>