<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$u_id = $_SESSION['user_id'];
$user_color = $_SESSION['fav_color'] ?? '#542665'; 
$user_name = $_SESSION['user_name'] ?? 'Violetha Nazwa Simaremare';

$stmt = $conn->prepare("SELECT * FROM stories WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$u_id]);
$memories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php if(count($memories) > 0): ?>
    <?php foreach($memories as $row): ?>
        <a href="echo_detail.php?id=<?= $row['id'] ?>" class="memory-link">
             <h4 class="memory-note"><?= htmlspecialchars($row['note']) ?></h4>
        </a>
    <?php endforeach; ?>
<?php else: ?>
    <p>Belum ada gema memori.</p>
<?php endif; ?>