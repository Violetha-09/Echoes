<?php 
include 'config.php'; 
$error = ""; $success = "";

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $color = $_POST['favorite_color'];
    $pass = $_POST['password'];

    if (!preg_match('/[A-Z]/', $pass) || !preg_match('/[a-z]/', $pass) || !preg_match('/[0-9]/', $pass)) {
        $error = "Password wajib: Huruf BESAR, kecil, dan angka.";
    } else {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if($check->rowCount() > 0) { 
            $error = "Email sudah terdaftar."; 
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, favorite_color) VALUES (?, ?, ?, ?)");
            if($stmt->execute([$name, $email, $hashed_pass, $color])) {
                $success = "Galaxy Created! Silakan login.";
            } else { $error = "Gagal mendaftar."; }
        }
    }
}

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['fav_color'] = $user['favorite_color'];
        header("Location: dashboard.php");
        exit;
    } else { $error = "Email atau Password salah."; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Echoes | Entry Galaxy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Lexend:wght@300;600&display=swap" rel="stylesheet">
    <style>
        body { background: #FFFFFF; margin: 0; display: flex; align-items: center; justify-content: center; height: 100vh; font-family: 'Inter', sans-serif; overflow: hidden; }
        .galaxy-bg { position: fixed; width: 100%; height: 100%; top: 0; left: 0; z-index: -1; background: radial-gradient(circle at center, #ffffff 0%, #f0fdfd 100%); }
        .auth-card { background: rgba(255, 255, 255, 0.9); padding: 40px; border-radius: 30px; box-shadow: 0 20px 50px rgba(0, 128, 128, 0.1); width: 350px; text-align: center; border: 1px solid rgba(0, 128, 128, 0.1); }
        .logo { width: 80px; margin-bottom: 10px; }
        h2 { font-family: 'Lexend'; font-weight: 600; color: #008080; margin: 0; }
        .tabs { display: flex; margin: 20px 0; background: #f0f0f0; border-radius: 15px; padding: 5px; }
        .tab { flex: 1; padding: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #999; border-radius: 12px; }
        .tab.active { background: #008080; color: white; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #eee; border-radius: 12px; box-sizing: border-box; }
        button { width: 100%; padding: 14px; background: #008080; color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; }
        .msg { font-size: 12px; padding: 10px; margin-bottom: 10px; border-radius: 10px; }
        .error { background: #fff5f5; color: #e53e3e; }
        .success { background: #f0fff4; color: #38a169; }
    </style>
</head>
<body>
    <div class="galaxy-bg"></div>
    <div class="auth-card">
        <img src="PHOTO-2026-02-11-04-09-06.jpg" alt="Logo" class="logo">
        <h2>ECHOES</h2>
        <?php if($error): ?><div class="msg error"><?= $error ?></div><?php endif; ?>
        <?php if($success): ?><div class="msg success"><?= $success ?></div><?php endif; ?>
        <div class="tabs">
            <div id="loginTab" class="tab active" onclick="showForm('login')">LOGIN</div>
            <div id="registerTab" class="tab" onclick="showForm('register')">SIGN UP</div>
        </div>
        <form id="loginForm" method="POST">
            <input type="email" name="email" placeholder="Email Galaxy" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">ENTER GALAXY</button>
        </form>
        <form id="registerForm" method="POST" style="display:none;">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="color" name="favorite_color" value="#008080" style="height:40px;">
            <input type="password" name="password" placeholder="Pass (A-z, 0-9)" required>
            <button type="submit" name="register">SIGN UP</button>
        </form>
    </div>
    <script>
        function showForm(f) {
            document.getElementById('loginForm').style.display = f === 'login' ? 'block' : 'none';
            document.getElementById('registerForm').style.display = f === 'login' ? 'none' : 'block';
            document.getElementById('loginTab').className = f === 'login' ? 'tab active' : 'tab';
            document.getElementById('registerTab').className = f === 'login' ? 'tab' : 'tab active';
        }
    </script>
</body>
</html>