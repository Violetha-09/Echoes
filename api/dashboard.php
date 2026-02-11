<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$u_id = $_SESSION['user_id'];
$user_color = $_SESSION['fav_color'] ?? '#542665'; 
$user_name = $_SESSION['user_name'] ?? 'Violetha Nazwa Simaremare';

$res_memories = $conn->query("SELECT * FROM stories WHERE user_id = '$u_id' ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Echoes | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Lexend:wght@300;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --user-theme: <?= $user_color ?>; 
            --tosca: #008080; 
            --bg-gradient: linear-gradient(to bottom, #ffffff, #f0fdfd);
        }

        body { background: #FFFFFF; margin: 0; display: flex; font-family: 'Inter', sans-serif; min-height: 100vh; overflow-x: hidden; }

        .galaxy-bg { position: fixed; width: 100%; height: 100%; top: 0; left: 0; z-index: -1; background: var(--bg-gradient); }
        .moon-sabit { position: absolute; top: 40px; right: 60px; width: 80px; fill: var(--user-theme); opacity: 0.15; transform: rotate(-15deg); }
        .star { position: absolute; fill: var(--user-theme); opacity: 0.1; animation: twinkle 4s infinite ease-in-out; }
        @keyframes twinkle { 0%, 100% { opacity: 0.1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(1.2); } }

        .sidebar { width: 260px; height: 100vh; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-right: 1px solid rgba(0, 128, 128, 0.05); padding: 50px 20px; position: fixed; box-sizing: border-box; display: flex; flex-direction: column; }
        
        /* Pembaruan Header Sidebar dengan Logo */
        .sidebar-header { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            margin-bottom: 60px; 
            justify-content: center;
        }
        .sidebar-logo { 
            width: 40px; 
            height: 40px; 
            object-fit: contain;
        }
        .sidebar h2 { 
            color: var(--tosca); 
            font-family: 'Lexend'; 
            margin: 0; 
            letter-spacing: 3px; 
            font-size: 1.2rem;
        }

        .sidebar a { color: #666; text-decoration: none; padding: 15px 20px; border-radius: 12px; margin-bottom: 8px; font-weight: 500; transition: 0.3s; }
        .sidebar a.active { background: var(--user-theme); color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }

        .main { margin-left: 260px; padding: 80px; width: calc(100% - 260px); box-sizing: border-box; }
        .main h1 { font-family: 'Lexend'; font-weight: 300; font-size: 2.5rem; margin: 0; }
        .subtitle { color: #888; margin-top: 10px; margin-bottom: 50px; }

        /* --- CARD STYLING --- */
        .memory-link { text-decoration: none; color: inherit; display: block; margin-bottom: 20px; }
        
        .memory-item { 
            background: white; 
            padding: 25px; 
            border-radius: 20px; 
            border: 1px solid #f5f5f5; 
            display: flex; 
            gap: 25px; 
            align-items: center; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
            transition: 0.3s;
            position: relative;
        }

        .memory-item:hover {
            transform: translateX(10px);
            border-color: var(--user-theme);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .memory-thumb { width: 80px; height: 80px; border-radius: 15px; object-fit: cover; background: #f9f9f9; flex-shrink: 0; }

        .memory-info { flex-grow: 1; overflow: hidden; padding-right: 30px; }
        
        .memory-note { 
            margin: 5px 0; 
            font-family: 'Lexend'; 
            font-weight: 400; 
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
        }

        .arrow-icon {
            position: absolute;
            right: 25px;
            color: #ccc;
            font-size: 1.2rem;
            transition: 0.3s;
        }
        .memory-item:hover .arrow-icon {
            color: var(--user-theme);
            right: 20px;
        }
    </style>
</head>
<body>

    <div class="galaxy-bg">
        <svg class="moon-sabit" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        <script>
            for (let i = 0; i < 20; i++) {
                let star = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                star.setAttribute("viewBox", "0 0 24 24"); star.classList.add("star");
                let size = Math.random() * 15 + 5;
                star.style.width = size + "px"; star.style.top = Math.random() * 100 + "%";
                star.style.left = Math.random() * 100 + "%"; star.style.animationDelay = Math.random() * 4 + "s";
                let path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                path.setAttribute("d", "M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z");
                star.appendChild(path); document.querySelector('.galaxy-bg').appendChild(star);
            }
        </script>
    </div>

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="PHOTO-2026-02-11-04-09-06.jpg" alt="Logo" class="sidebar-logo">
            <h2>ECHOES</h2>
        </div>
        
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="capture.php">Capture Echo</a>
        <a href="calendar.php">Calendar</a>
        <a href="account.php">Account</a>
    </div>

    <div class="main">
        <h1>Halo, <span style="color:var(--user-theme); font-weight:600;"><?= htmlspecialchars($user_name) ?></span></h1>
        <p class="subtitle">Bagaimana galaksi memorimu hari ini?</p>

        <div style="margin-top: 20px;">
            <h3 style="font-family:'Lexend'; font-weight:400; color:#555; margin-bottom:20px;">Recent Echoes</h3>
            
            <?php if($res_memories->num_rows > 0): ?>
                <?php while($row = $res_memories->fetch_assoc()): ?>
                    <a href="echo_detail.php?id=<?= $row['id'] ?>" class="memory-link">
                        <div class="memory-item">
                            <?php if(!empty($row['photo_path'])): ?>
                                <img src="<?= $row['photo_path'] ?>" class="memory-thumb">
                            <?php else: ?>
                                <div class="memory-thumb" style="display:flex;align-items:center;justify-content:center;font-size:10px;color:#ccc">NO PIC</div>
                            <?php endif; ?>
                            
                            <div class="memory-info">
                                <small style="color:var(--user-theme); font-weight:600;"><?= date('d M Y', strtotime($row['created_at'])) ?></small>
                                <h4 class="memory-note"><?= htmlspecialchars($row['note']) ?></h4>
                            </div>

                            <div class="arrow-icon">→</div>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color:#bbb; font-style:italic;">Belum ada gema memori. Mulailah menulis di Capture Echo.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>