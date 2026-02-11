<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$u_id = $_SESSION['user_id'];
$user_color = $_SESSION['fav_color'] ?? '#542665'; 
$user_name = $_SESSION['user_name'] ?? 'User';
$user_email = $_SESSION['user_email'] ?? 'Tidak terdaftar'; 

$res_count = $conn->query("SELECT COUNT(*) as total FROM stories WHERE user_id = $u_id");
$total_stories = ($res_count) ? $res_count->fetch_assoc()['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Echoes | Account Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Lexend:wght@300;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --user-theme: <?= $user_color ?>; 
            --tosca: #008080; 
            --bg-gradient: linear-gradient(to bottom, #ffffff, #f0fdfd);
        }

        body { 
            background: #FFFFFF; 
            margin: 0; 
            display: flex; 
            font-family: 'Inter', sans-serif; 
            height: 100vh; 
            overflow: hidden; 
        }

        .galaxy-bg { 
            position: fixed; 
            width: 100%; height: 100%; 
            top: 0; left: 0; 
            z-index: -1; 
            background: var(--bg-gradient); 
        }

        .moon-sabit { 
            position: absolute; 
            top: 40px; right: 60px; 
            width: 80px; 
            fill: var(--user-theme); 
            opacity: 0.15; 
            transform: rotate(-15deg);
        }

        .star { 
            position: absolute; 
            fill: var(--user-theme); 
            opacity: 0.1; 
            animation: twinkle 4s infinite ease-in-out; 
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.2); }
        }

        .sidebar { 
            width: 260px; 
            height: 100vh; 
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(0, 128, 128, 0.05); 
            padding: 50px 20px; 
            position: fixed; 
            box-sizing: border-box; 
            display: flex; 
            flex-direction: column; 
        }

        /* Gaya Header Sidebar dengan Logo */
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

        .sidebar-menu { flex-grow: 1; }

        .sidebar a { 
            color: #666; 
            text-decoration: none; 
            padding: 15px 20px; 
            border-radius: 12px; 
            margin-bottom: 8px; 
            font-weight: 500;
            display: block;
            transition: 0.3s;
        }

        .sidebar a.active { background: var(--user-theme); color: white; }

        .sidebar .logout-link {
            color: #e74c3c;
            margin-top: auto;
            border: 1px solid rgba(231, 76, 60, 0.1);
            text-align: center;
        }

        /* --- PERBAIKAN POSISI MAIN & CARD --- */
        .main { 
            margin-left: 260px; 
            padding: 40px 60px; 
            width: calc(100% - 260px); 
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start; 
            overflow-y: auto;
        }

        .main h1 {
            font-family: 'Lexend';
            font-weight: 300;
            margin-bottom: 20px;
            font-size: 2.2rem;
        }

        .profile-card { 
            background: white; 
            padding: 30px 45px; 
            border-radius: 25px; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.03); 
            border: 1px solid #f2f2f2;
            max-width: 750px; 
            width: 100%;
            margin-top: 10px;
        }

        .profile-header {
            margin-bottom: 25px;
        }

        .profile-header h2 { 
            margin: 0; 
            font-family: 'Lexend'; 
            color: #333; 
            font-size: 1.8rem;
        }

        .info-table { width: 100%; border-collapse: collapse; }
        
        .info-row { border-bottom: 1px solid #f8f8f8; }
        
        .info-row:last-child { border-bottom: none; }

        .info-label { 
            padding: 15px 0; 
            color: #888; 
            font-size: 0.9rem; 
            width: 220px; 
            font-weight: 500;
        }

        .info-value { 
            padding: 15px 0; 
            color: #444; 
            font-weight: 600; 
            font-size: 0.95rem;
        }

        .aura-dot {
            width: 12px; height: 12px;
            background: var(--user-theme);
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .echo-mini-box {
            width: 15px; height: 15px;
            background: var(--user-theme);
            border-radius: 3px;
            display: inline-block;
            margin-right: 4px;
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <div class="galaxy-bg">
        <svg class="moon-sabit" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        <script>
            for (let i = 0; i < 25; i++) {
                let star = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                star.setAttribute("viewBox", "0 0 24 24"); star.classList.add("star");
                let size = Math.random() * 8 + 4; star.style.width = size + "px";
                star.style.top = Math.random() * 100 + "%"; star.style.left = Math.random() * 100 + "%";
                star.style.animationDelay = Math.random() * 4 + "s";
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

        <div class="sidebar-menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="capture.php">Capture Echo</a>
            <a href="calendar.php">Calendar</a>
            <a href="account.php" class="active">Account</a>
        </div>
        <a href="logout.php" class="logout-link">Logout from Galaxy</a>
    </div>

    <div class="main">
        <h1>Account <span style="color:var(--user-theme); font-weight:600;">Profile</span></h1>
        
        <div class="profile-card">
            <div class="profile-header">
                <h2><?= htmlspecialchars($user_name) ?></h2>
                <span style="color:var(--tosca); font-size:0.75rem; font-weight:600; letter-spacing:1px;">GALAXY EXPLORER</span>
            </div>

            <table class="info-table">
                <tr class="info-row">
                    <td class="info-label">Email Address</td>
                    <td class="info-value"><?= htmlspecialchars($user_email) ?></td>
                </tr>
                <tr class="info-row">
                    <td class="info-label">Warna Aura</td>
                    <td class="info-value"><span class="aura-dot"></span><?= strtoupper($user_color) ?></td>
                </tr>
                <tr class="info-row">
                    <td class="info-label">Total Memory Collected</td>
                    <td class="info-value"><?= $total_stories ?> Echoes</td>
                </tr>
                <tr class="info-row">
                    <td class="info-label">Echo Collection</td>
                    <td class="info-value">
                        <?php if($total_stories > 0): ?>
                            <?php for($i=0; $i<min($total_stories, 25); $i++): ?>
                                <div class="echo-mini-box"></div>
                            <?php endfor; ?>
                            <?= ($total_stories > 25) ? '...' : '' ?>
                        <?php else: ?>
                            <span style="color:#ccc; font-weight:400; font-style:italic;">Belum ada memori</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>