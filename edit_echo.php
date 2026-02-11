<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$u_id = $_SESSION['user_id'];
$user_color = $_SESSION['fav_color'] ?? '#542665';
$echo_id = $_GET['id'] ?? 0;

// Ambil data lama
$stmt = $conn->prepare("SELECT * FROM stories WHERE id = ? AND user_id = ?");
$stmt->bind_param("is", $echo_id, $u_id);
$stmt->execute();
$echo = $stmt->get_result()->fetch_assoc();

if(!$echo) { header("Location: dashboard.php"); exit; }

// Logika Simpan Perubahan
$success_msg = "";
if(isset($_POST['submit_update'])) {
    $new_note = $_POST['note'];
    
    $update = $conn->prepare("UPDATE stories SET note = ? WHERE id = ? AND user_id = ?");
    $update->bind_param("sis", $new_note, $echo_id, $u_id);
    
    if($update->execute()) {
        header("Location: echo_detail.php?id=$echo_id&status=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Echo | Echoes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Lexend:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --user-theme: <?= $user_color ?>; }
        body { background: #fcfdfd; margin: 0; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .edit-card { background: white; width: 90%; max-width: 500px; padding: 40px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); }
        h2 { font-family: 'Lexend'; font-weight: 600; margin-bottom: 10px; color: #333; }
        p { color: #888; font-size: 0.9rem; margin-bottom: 25px; }
        textarea { 
            width: 100%; height: 150px; padding: 20px; border-radius: 15px; border: 1px solid #eee; 
            font-family: 'Inter'; font-size: 1rem; resize: none; box-sizing: border-box; outline: none; transition: 0.3s;
        }
        textarea:focus { border-color: var(--user-theme); box-shadow: 0 0 0 4px rgba(0,0,0,0.02); }
        .btn-group { margin-top: 25px; display: flex; gap: 10px; }
        .btn { flex: 1; padding: 12px; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; transition: 0.3s; font-size: 0.95rem; text-decoration: none; text-align: center; }
        .btn-save { background: var(--user-theme); color: white; }
        .btn-cancel { background: #f5f5f5; color: #777; }
        .btn-save:hover { opacity: 0.9; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="edit-card">
        <h2>Perbaiki Gema</h2>
        <p>Ada detail yang terlewat? Edit ceritamu di bawah ini.</p>
        
        <form method="POST">
            <textarea name="note" required><?= htmlspecialchars($echo['note']) ?></textarea>
            <div class="btn-group">
                <button type="submit" name="submit_update" class="btn btn-save">Simpan Perubahan</button>
                <a href="echo_detail.php?id=<?= $echo_id ?>" class="btn btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>