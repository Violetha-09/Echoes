<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// DATA PASTI UNTUK REGION MUMBAI (PORT 6543)
$host = 'aws-0-ap-south-1.pooler.supabase.com'; 
$db   = 'postgres';
$user = 'postgres.quxhhvvkwrruvpwfysqi'; 
$pass = 'NyeblakSelalu2@'; 
$port = '6543'; 

try {
    // Format DSN harus rapat tanpa spasi yang aneh
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
     // Menampilkan error yang lebih jelas untuk kita debug
     echo "Koneksi Bermasalah: " . $e->getMessage();
     exit;
}
?>