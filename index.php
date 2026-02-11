<?php 
include 'config.php'; 
// Pastikan session sudah dimulai di config.php, jika belum tambahkan session_start(); di baris pertama config.php

$error = ""; $success = "";

// --- LOGIKA REGISTER ---
if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $color = $_POST['favorite_color'];
    $pass = $_POST['password'];

    // Validasi Password: Harus mengandung Huruf Besar, Kecil, dan Angka
    if (!preg_match('/[A-Z]/', $pass) || !preg_match('/[a-z]/', $pass) || !preg_match('/[0-9]/', $pass)) {
        $error = "Password wajib: Huruf BESAR, kecil, dan angka.";
    } else {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        
        // Cek apakah email sudah ada
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if($check->num_rows > 0) { 
            $error = "Email sudah terdaftar."; 
        } else {
            // Simpan ke Database
            $sql = "INSERT INTO users (name, email, password, favorite_color) VALUES ('$name', '$email', '$hashed_pass', '$color')";
            if($conn->query($sql)) {
                $success = "Galaxy Created! Silakan login.";
            } else {
                $error = "Gagal mendaftar: " . $conn->error;
            }
        }
    }
}

// --- LOGIKA LOGIN ---
if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    
    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $res->fetch_assoc();
    
    if($user && password_verify($pass, $user['password'])) {
        // MENYIMPAN DATA KE SESSION UNTUK DIGUNAKAN DI HALAMAN LAIN
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email']; // <--- BARIS TERBARU: Agar halaman Account bisa direct email
        $_SESSION['fav_color'] = $user['favorite_color'];
        
        header("Location: dashboard.php");
        exit();
    } else { 
        $error = "Email atau Password salah!"; 
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Echoes | Entry Galaxy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --tosca: #008080; 
            --white: #FFFFFF; 
            --light-gray: #F8F9FA;
        }

        body { 
            background: var(--white);
            height: 100vh; display: flex; justify-content: center; align-items: center;
            font-family: 'Inter', sans-serif; color: #333; margin: 0;
            overflow: hidden; position: relative;
        }

        /* --- Elemen Galaksi (Bulan & Bintang) --- */
        .galaxy-bg {
            position: absolute; width: 100%; height: 100%;
            top: 0; left: 0; z-index: -1;
            background: linear-gradient(to bottom, #ffffff, #f0fdfd);
        }

        .moon {
            position: absolute;
            top: 10%; right: 10%;
            width: 120px; height: 120px;
            fill: var(--tosca);
            opacity: 0.2;
            animation: floatMoon 6s infinite ease-in-out;
        }

        .star-svg {
            position: absolute;
            fill: var(--tosca);
            opacity: 0.15;
            animation: twinkle 4s infinite ease-in-out;
        }

        @keyframes floatMoon {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.1); }
        }

        /* --- Container Form --- */
        .container { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px);
            padding: 45px; border-radius: 30px; width: 400px; 
            box-shadow: 0 15px 35px rgba(0, 80, 80, 0.1);
            border: 1px solid rgba(0, 128, 128, 0.1); 
            z-index: 10;
        }

        h2 { text-align: center; color: var(--tosca); letter-spacing: 6px; font-weight: 400; margin-bottom: 30px; }
        
        .tabs { display: flex; gap: 15px; margin-bottom: 25px; }
        .tab-btn { 
            flex: 1; background: none; color: #bbb; border: none; 
            cursor: pointer; padding-bottom: 10px; border-bottom: 2px solid #eee; 
            font-weight: 600; font-size: 14px; transition: 0.3s;
        }
        .tab-btn.active { color: var(--tosca); border-bottom-color: var(--tosca); }

        input { 
            width: 100%; padding: 15px; margin: 10px 0; border-radius: 12px; 
            border: 1px solid #E0E0E0; background: #fff; color: #333; box-sizing: border-box; 
            font-size: 14px;
        }
        input:focus { border-color: var(--tosca); outline: none; }

        button[type="submit"] { 
            width: 100%; padding: 15px; border-radius: 12px; border: none; 
            background: var(--tosca); color: white; font-weight: bold; 
            cursor: pointer; margin-top: 15px; transition: 0.3s;
            letter-spacing: 1px;
        }
        button[type="submit"]:hover { background: #006666; transform: translateY(-2px); }
        
        .msg { font-size: 12px; text-align: center; margin-bottom: 15px; font-weight: 600; }
    </style>
</head>
<body>

    <div class="galaxy-bg">
        <svg class="moon" viewBox="0 0 24 24">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>

        <script>
            const galaxy = document.querySelector('.galaxy-bg');
            for (let i = 0; i < 15; i++) {
                const size = Math.random() * 20 + 10;
                const star = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                star.setAttribute("viewBox", "0 0 24 24");
                star.setAttribute("class", "star-svg");
                star.style.width = size + "px";
                star.style.height = size + "px";
                star.style.top = Math.random() * 90 + "%";
                star.style.left = Math.random() * 90 + "%";
                star.style.animationDelay = Math.random() * 4 + "s";
                
                const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                path.setAttribute("d", "M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z");
                
                star.appendChild(path);
                galaxy.appendChild(star);
            }
        </script>
    </div>

    <div class="container">
        <h2>ECHOES</h2>
        <div class="tabs">
            <button class="tab-btn active" id="loginTab" onclick="showForm('login')">LOGIN</button>
            <button class="tab-btn" id="registerTab" onclick="showForm('register')">REGISTER</button>
        </div>
        
        <?php if($error): ?>
            <p class='msg' style='color:#ff4757;'><?= $error ?></p>
        <?php endif; ?>
        
        <?php if($success): ?>
            <p class='msg' style='color:var(--tosca);'><?= $success ?></p>
        <?php endif; ?>
        
        <form id="loginForm" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">ENTER GALAXY</button>
        </form>

        <form id="registerForm" method="POST" style="display:none;">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <div style="display:flex; gap:10px; align-items:center; margin:10px 0;">
                <label style="font-size:12px; color:#999;">Aura Color:</label>
                <input type="color" name="favorite_color" value="#008080" style="height:35px; width:45px; border:none; background:none; cursor:pointer;">
                <span style="font-size:10px; color:#ccc;">Pilih aura galaksimu</span>
            </div>
            <input type="password" name="password" placeholder="Pass (A-z, 0-9)" required>
            <button type="submit" name="register">SIGN UP</button>
        </form>
    </div>

    <script>
        function showForm(f) {
            const loginForm = document.getElementById('loginForm');
            const regForm = document.getElementById('registerForm');
            const loginTab = document.getElementById('loginTab');
            const regTab = document.getElementById('registerTab');

            if(f === 'login') {
                loginForm.style.display = 'block';
                regForm.style.display = 'none';
                loginTab.classList.add('active');
                regTab.classList.remove('active');
            } else {
                loginForm.style.display = 'none';
                regForm.style.display = 'block';
                loginTab.classList.remove('active');
                regTab.classList.add('active');
            }
        }
    </script>
</body>
</html>