<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$u_id = $_SESSION['user_id'];
$user_color = $_SESSION['fav_color'] ?? '#542665'; 
$user_name = $_SESSION['user_name'] ?? 'User';
$user_email = $_SESSION['user_email'] ?? 'Tidak terdaftar'; 

$res_count = $conn->prepare("SELECT COUNT(*) as total FROM stories WHERE user_id = ?");
$res_count->execute([$u_id]);
$total_stories = $res_count->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
?>