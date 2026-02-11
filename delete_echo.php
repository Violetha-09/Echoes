<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { exit; }

$u_id = $_SESSION['user_id'];
$echo_id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM stories WHERE id = ? AND user_id = ?");
$stmt->bind_param("is", $echo_id, $u_id);

if($stmt->execute()) {
    header("Location: dashboard.php?msg=deleted");
}
exit;