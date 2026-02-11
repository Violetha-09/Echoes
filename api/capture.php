<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$u_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['publish'])) {
    $note = $_POST['note'];
    $base64_image = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_type = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $data = file_get_contents($file_tmp);
        $base64_image = 'data:image/' . $file_type . ';base64,' . base64_encode($data);
    }

    $stmt = $conn->prepare("INSERT INTO stories (user_id, note, photo_path, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
    if($stmt->execute([$u_id, $note, $base64_image])) {
        header("Location: dashboard.php");
        exit;
    }
}
?>