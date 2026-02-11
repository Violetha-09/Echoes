<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// UPDATE TERAKHIR: PAKSA POOLER PORT 6543
$host = 'aws-0-ap-south-1.pooler.supabase.com'; 
$db   = 'postgres';
$user = 'postgres.quxhhvvkwrruvpwfysqi'; 
$pass = 'NyeblakSelalu2@'; 
$port = '6543'; 

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $conn = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
     die("Koneksi gagal: " . $e->getMessage());
}
?>