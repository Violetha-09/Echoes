<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$u_id = $_SESSION['user_id'];
$user_color = $_SESSION['fav_color'] ?? '#542665'; 
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM stories WHERE user_id = ?");
$stmt->execute([$u_id]);
$total_stories = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
?>