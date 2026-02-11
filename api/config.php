<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Ambil data ini dari Supabase: Project Settings > Database
$host = 'Violetha-09\'s Project.supabase.co'; 
$db   = 'postgres';
$user = 'postgres';
$pass = 'NyeblakSelalu2@'; 
$port = '5432';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $conn = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
     die("Koneksi gagal: " . $e->getMessage());
}
?>