<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$u_id = $_SESSION['user_id'];
$user_color = $_SESSION['fav_color'] ?? '#542665'; 
$user_name = $_SESSION['user_name'] ?? 'User';

$stmt = $conn->prepare("SELECT * FROM stories WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$u_id]);
$memories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Echoes | Dashboard</title>
    <style>
        :root { --user-theme: <?= $user_color ?>; }
        body { background: #FFFFFF; display: flex; font-family: 'Inter', sans-serif; margin: 0; }
        .sidebar { width: 250px; border-right: 1px solid #eee; height: 100vh; padding: 30px; }
        .main { flex: 1; padding: 50px; }
        .welcome-card { background: var(--user-theme); color: white; padding: 30px; border-radius: 25px; }
        .memory-item { display: flex; align-items: center; padding: 15px; border: 1px solid #eee; border-radius: 15px; margin-top: 10px; }
        .memory-thumb { width: 50px; height: 50px; border-radius: 10px; margin-right: 15px; object-fit: cover; }
        a { text-decoration: none; color: inherit; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ECHOES</h2>
        <p><a href="dashboard.php">Dashboard</a></p>
        <p><a href="capture.php">Capture Echo</a></p>
        <p><a href="calendar.php">Calendar</a></p>
        <p><a href="account.php">Account</a></p>
        <p><a href="logout.php" style="color:red;">Logout</a></p>
    </div>
    <div class="main">
        <div class="welcome-card">
            <h1>Hello, <?= explode(' ', $user_name)[0] ?>!</h1>
        </div>
        <h3>Recent Echoes</h3>
        <?php foreach($memories as $row): ?>
            <div class="memory-item">
                <img src="<?= $row['photo_path'] ?>" class="memory-thumb">
                <div>
                    <small><?= date('d M Y', strtotime($row['created_at'])) ?></small>
                    <h4><?= htmlspecialchars($row['note']) ?></h4>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>