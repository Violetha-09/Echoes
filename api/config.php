<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Ganti data di bawah ini dengan info dari Supabase (Project Settings > Database)
$host = 'your-project-id.supabase.co'; // Contoh: db.xyz.supabase.co
$db   = 'postgres';
$user = 'postgres';
$pass = 'NyeblakSelalu2@'; // Password yang dibuat saat buat project
$port = '5432';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $conn = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
     die("Connection failed: " . $e->getMessage());
}
?>