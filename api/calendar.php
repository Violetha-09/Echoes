<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$u_id = $_SESSION['user_id'];

$month = isset($_GET['m']) ? (int)$_GET['m'] : (int)date('m');
$year = isset($_GET['y']) ? (int)$_GET['y'] : (int)date('Y');

$memories_date = [];
// PostgreSQL menggunakan EXTRACT
$stmt = $conn->prepare("SELECT DISTINCT DATE(created_at) as tgl FROM stories WHERE user_id = ? AND EXTRACT(MONTH FROM created_at) = ? AND EXTRACT(YEAR FROM created_at) = ?");
$stmt->execute([$u_id, $month, $year]);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { $memories_date[] = $row['tgl']; }

$first_day = mktime(0, 0, 0, $month, 1, $year);
$days_in_month = date('t', $first_day);
$month_name = date('F', $first_day);
$day_of_week = date('w', $first_day);
?>