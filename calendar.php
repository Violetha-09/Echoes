<?php 
include 'config.php'; 
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$u_id = $_SESSION['user_id'];
$user_color = $_SESSION['fav_color'] ?? '#542665'; 

// Mengambil bulan dan tahun dari URL atau default hari ini
$month = isset($_GET['m']) ? $_GET['m'] : date('m');
$year = isset($_GET['y']) ? $_GET['y'] : date('Y');

$first_day = mktime(0, 0, 0, $month, 1, $year);
$days_in_month = date('t', $first_day);
$month_name = date('F', $first_day);
$day_of_week = date('w', $first_day);

// Ambil data echo dari database untuk bulan ini
$memories_date = [];
$res = $conn->query("SELECT DISTINCT DATE(created_at) as tgl FROM stories WHERE user_id = '$u_id' AND MONTH(created_at) = '$month' AND YEAR(created_at) = '$year'");
while($row = $res->fetch_assoc()) { $memories_date[] = $row['tgl']; }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Echoes | Memory Calendar Full</title>
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

        /* --- GALAXY BACKGROUND --- */
        .galaxy-bg { 
            position: fixed; 
            width: 100%; 
            height: 100%; 
            top: 0; 
            left: 0; 
            z-index: -1; 
            background: var(--bg-gradient); 
        }

        .moon-sabit { 
            position: absolute; 
            top: 30px; 
            right: 40px; 
            width: 70px; 
            fill: var(--user-theme); 
            opacity: 0.12; 
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

        /* --- SIDEBAR --- */
        .sidebar { 
            width: 260px; 
            height: 100vh; 
            background: rgba(255, 255, 255, 0.8); 
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(0, 128, 128, 0.05); 
            padding: 50px 20px; 
            position: fixed; 
            box-sizing: border-box; 
            display: flex; 
            flex-direction: column; 
            z-index: 100;
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

        .sidebar a { 
            color: #666; 
            text-decoration: none; 
            padding: 15px 20px; 
            border-radius: 12px; 
            margin-bottom: 8px; 
            font-weight: 500;
            transition: 0.3s;
            display: block;
        }

        .sidebar a.active { 
            background: var(--user-theme); 
            color: white; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* --- MAIN CONTENT --- */
        .main { 
            margin-left: 260px; 
            padding: 30px 40px; 
            width: calc(100% - 260px); 
            height: 100vh;
            box-sizing: border-box; 
            display: flex;
            flex-direction: column;
        }

        .main h1 {
            font-family: 'Lexend';
            font-weight: 300;
            font-size: 2.2rem;
            margin: 0 0 15px 0;
            z-index: 2;
        }

        /* --- PERBAIKAN: TRANSPARANSI CARD --- */
        .calendar-card { 
            background: rgba(255, 255, 255, 0.3); /* Transparan */
            backdrop-filter: blur(12px); /* Efek kaca/blur */
            -webkit-backdrop-filter: blur(12px);
            padding: 20px; 
            border-radius: 25px; 
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.03); 
            border: 1px solid rgba(255, 255, 255, 0.4); /* Border halus transparan */
            flex-grow: 1; 
            display: flex;
            flex-direction: column;
            z-index: 2;
        }

        .nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .nav-btn {
            text-decoration: none;
            color: var(--tosca);
            font-weight: 600;
            background: rgba(240, 253, 253, 0.6); /* Navigasi semi transparan */
            padding: 10px 25px;
            border-radius: 12px;
            transition: 0.3s;
        }

        .nav-btn:hover { background: var(--user-theme); color: white; }

        .calendar-grid { 
            display: grid; 
            grid-template-columns: repeat(7, 1fr); 
            grid-template-rows: 40px repeat(6, 1fr); 
            gap: 12px; 
            flex-grow: 1;
        }

        .day-name {
            text-align: center;
            font-size: 0.85rem;
            color: #888; /* Warna teks hari dipergelap sedikit agar terlihat */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .day-cell { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            border-radius: 20px; 
            border: 1px solid rgba(0, 0, 0, 0.03); 
            position: relative; 
            font-size: 1.2rem;
            color: #333;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1); /* Kotak tanggal transparan */
        }

        .day-cell:hover { 
            background: rgba(255, 255, 255, 0.5);
            border-color: var(--user-theme);
            transform: translateY(-3px);
        }

        /* Hari ini hanya warna teks tanpa kotak bold */
        .day-cell.today {
            color: var(--user-theme);
            font-weight: 600;
        }

        /* Icon Bulan Sabit untuk tanggal yang ada echo */
        .echo-moon { 
            width: 14px; 
            fill: var(--user-theme); 
            position: absolute; 
            bottom: 12px; 
            opacity: 1; 
        }
    </style>
</head>
<body>

    <div class="galaxy-bg">
        <svg class="moon-sabit" viewBox="0 0 24 24">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
        <script>
            for (let i = 0; i < 25; i++) {
                let star = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                star.setAttribute("viewBox", "0 0 24 24");
                star.classList.add("star");
                let size = Math.random() * 10 + 4;
                star.style.width = size + "px";
                star.style.top = Math.random() * 100 + "%";
                star.style.left = Math.random() * 100 + "%";
                star.style.animationDelay = Math.random() * 4 + "s";
                let path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                path.setAttribute("d", "M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z");
                star.appendChild(path);
                document.querySelector('.galaxy-bg').appendChild(star);
            }
        </script>
    </div>

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="PHOTO-2026-02-11-04-09-06.jpg" alt="Logo" class="sidebar-logo">
            <h2>ECHOES</h2>
        </div>

        <a href="dashboard.php">Dashboard</a>
        <a href="capture.php">Capture Echo</a>
        <a href="calendar.php" class="active">Calendar</a>
        <a href="account.php">Account</a>
    </div>

    <div class="main">
        <h1>Memory <span style="color:var(--user-theme); font-weight:600;">Calendar</span></h1>
        
        <div class="calendar-card">
            <div class="nav-header">
                <a href="?m=<?= $month-1 < 1 ? 12 : $month-1 ?>&y=<?= $month-1 < 1 ? $year-1 : $year ?>" class="nav-btn">← PREV</a>
                <h2 style="font-family:'Lexend'; font-weight: 600; margin: 0; color: #333;"><?= $month_name . " " . $year ?></h2>
                <a href="?m=<?= $month+1 > 12 ? 1 : $month+1 ?>&y=<?= $month+1 > 12 ? $year+1 : $year ?>" class="nav-btn">NEXT →</a>
            </div>

            <div class="calendar-grid">
                <?php 
                $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                foreach($days as $d) echo "<div class='day-name'>$d</div>";
                
                for($i=0; $i<$day_of_week; $i++) echo "<div></div>";
                
                $today_realtime = date('Y-m-d');

                for($d=1; $d<=$days_in_month; $d++) {
                    $full_date = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    $has_echo = in_array($full_date, $memories_date);
                    
                    $is_today = ($full_date == $today_realtime) ? 'today' : '';
                    
                    echo "<div class='day-cell $is_today'>$d";
                    if($has_echo) {
                        echo '<svg class="echo-moon" viewBox="0 0 24 24"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

</body>
</html>