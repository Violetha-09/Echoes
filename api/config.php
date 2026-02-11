<?php
// UPDATE MANUAL PAKSA KE PORT 6543 - JAM 15:15
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$host = 'aws-0-ap-south-1.pooler.supabase.com'; 
$db   = 'postgres';
$user = 'postgres.quxhhvvkwrruvpwfysqi'; 
$pass = 'NyeblakSelalu2@'; 
$port = '6543'; 

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $conn = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
     die("Koneksi gagal total: " . $e->getMessage());
}
?>