<?php 
include 'config.php'; 

// Pastikan session dimulai
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if(!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit; 
}

$u_id = $_SESSION['user_id'];
$user_color = $_SESSION['fav_color'] ?? '#542665'; 
$user_name = $_SESSION['user_name'] ?? 'User';

// --- LOGIKA PUBLISH ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['publish'])) {
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    $base64_image = null;

    // Cek file foto
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_type = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        
        // Membaca file
        $data = file_get_contents($file_tmp);
        // Konversi ke base64
        $base64_image = 'data:image/' . $file_type . ';base64,' . base64_encode($data);
    }

    // Gunakan Prepared Statement untuk menghindari Error 500 karena query terlalu panjang
    $stmt = $conn->prepare("INSERT INTO stories (user_id, note, photo_path, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $u_id, $note, $base64_image);

    if ($stmt->execute()) {
        // Berhasil! Langsung arahkan ke dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan ke Galaxy: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Echoes | Capture Echo</title>
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
            min-height: 100vh; 
            overflow-x: hidden;
        }

        .galaxy-bg { position: fixed; width: 100%; height: 100%; top: 0; left: 0; z-index: -1; background: var(--bg-gradient); }
        .moon-sabit { position: absolute; top: 40px; right: 60px; width: 80px; fill: var(--user-theme); opacity: 0.15; transform: rotate(-15deg); }
        .star { position: absolute; fill: var(--user-theme); opacity: 0.1; animation: twinkle 4s infinite ease-in-out; }

        @keyframes twinkle {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.2); }
        }

        .sidebar { 
            width: 260px; height: 100vh; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);
            border-right: 1px solid rgba(0, 128, 128, 0.05); padding: 50px 20px; position: fixed; box-sizing: border-box; display: flex; flex-direction: column; 
        }
        
        /* Gaya Baru untuk Header Sidebar dengan Logo */
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
        .sidebar a:hover { background: #f9ffff; color: var(--tosca); }
        .sidebar a.active { background: var(--user-theme); color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }

        .main { margin-left: 260px; padding: 80px; width: calc(100% - 260px); box-sizing: border-box; }
        .main h1 { font-family: 'Lexend'; font-weight: 300; font-size: 2.5rem; margin: 0; margin-bottom: 40px; }

        .capture-card { background: white; padding: 40px; border-radius: 25px; box-shadow: 0 10px 40px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; }
        textarea { width: 100%; height: 180px; border: 1px solid #eee; border-radius: 15px; padding: 20px; margin-bottom: 25px; box-sizing: border-box; font-family: inherit; font-size: 1rem; outline: none; transition: 0.3s; resize: none; }
        textarea:focus { border-color: var(--user-theme); box-shadow: 0 0 10px rgba(0,0,0,0.02); }

        .file-label { display: block; margin-bottom: 10px; font-size: 0.9rem; color: #888; font-weight: 600; }
        .publish-btn { background: var(--user-theme); color: white; border: none; padding: 15px 40px; border-radius: 12px; cursor: pointer; font-family: 'Lexend'; font-weight: 600; font-size: 1rem; transition: 0.3s; margin-top: 10px; }
        .publish-btn:hover { opacity: 0.9; transform: translateY(-2px); }
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

        <a href="dashboard.php">Dashboard</a>
        <a href="capture.php" class="active">Capture Echo</a>
        <a href="calendar.php">Calendar</a>
        <a href="account.php">Account</a>
    </div>

    <div class="main">
        <h1>New <span style="color:var(--user-theme); font-weight:600;">Echo</span></h1>
        
        <form method="POST" enctype="multipart/form-data" class="capture-card">
            <textarea name="note" placeholder="Tuliskan memorimu hari ini..." required></textarea>
            
            <label class="file-label">Unggah Foto:</label>
            <input type="file" name="photo" accept="image/*" style="margin-bottom: 30px; display: block;">
            
            <button type="submit" name="publish" class="publish-btn">Publish to Galaxy</button>
        </form>
    </div>

</body>
</html>