<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$u_id = $_SESSION['user_id'];
$user_color = $_SESSION['fav_color'] ?? '#542665';
$echo_id = $_GET['id'] ?? 0;

// Ambil data detail gema
$stmt = $conn->prepare("SELECT * FROM stories WHERE id = ? AND user_id = ?");
$stmt->bind_param("is", $echo_id, $u_id);
$stmt->execute();
$echo = $stmt->get_result()->fetch_assoc();

if(!$echo) { header("Location: dashboard.php"); exit; }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Memori | Echoes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Lexend:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --user-theme: <?= $user_color ?>; }
        body { background: #fcfdfd; margin: 0; font-family: 'Inter', sans-serif; color: #444; }
        .container { max-width: 650px; margin: 60px auto; padding: 0 20px; }
        .btn-back { text-decoration: none; color: #aaa; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 25px; transition: 0.3s; }
        .btn-back:hover { color: var(--user-theme); transform: translateX(-4px); }
        .detail-card { background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 15px 45px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.02); }
        .img-wrapper { width: 100%; height: 380px; background: #f9f9f9; overflow: hidden; }
        .detail-img { width: 100%; height: 100%; object-fit: cover; }
        .content { padding: 35px; }
        .meta { color: var(--user-theme); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; display: block; }
        .story-text { font-family: 'Lexend'; font-size: 1.05rem; line-height: 1.7; color: #333; margin: 0; white-space: pre-wrap; }
        .actions { margin-top: 35px; border-top: 1px solid #f0f0f0; padding-top: 25px; display: flex; gap: 10px; }
        .btn { padding: 10px 22px; border-radius: 12px; border: none; cursor: pointer; font-family: 'Inter'; font-weight: 500; transition: 0.3s; font-size: 0.9rem; text-decoration: none; }
        .btn-edit { background: var(--user-theme); color: white; }
        .btn-delete { background: #fff1f1; color: #e74c3c; }
        .btn-delete:hover { background: #e74c3c; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
        <div class="detail-card">
            <?php if(!empty($echo['photo_path'])): ?>
                <div class="img-wrapper"><img src="<?= $echo['photo_path'] ?>" class="detail-img"></div>
            <?php endif; ?>
            <div class="content">
                <span class="meta">Memori Terbentuk: <?= date('d F Y', strtotime($echo['created_at'])) ?></span>
                <p class="story-text"><?= htmlspecialchars($echo['note']) ?></p>
                <div class="actions">
                    <a href="edit_echo.php?id=<?= $echo['id'] ?>" class="btn btn-edit">Edit Cerita</a>
                    <a href="delete_echo.php?id=<?= $echo['id'] ?>" class="btn btn-delete" onclick="return confirm('Hapus memori ini selamanya?')">Hapus</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>