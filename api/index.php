<?php 
include 'config.php'; 
$error = ""; $success = "";

if(isset($_POST['register'])) {
    $name = $_POST['name']; $email = $_POST['email'];
    $color = $_POST['favorite_color']; $pass = $_POST['password'];

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
    $email = $_POST['email']; $pass = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['fav_color'] = $user['favorite_color'];
        header("Location: dashboard.php"); exit;
    } else { $error = "Email atau Password salah."; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Echoes | Entry Galaxy</title>
    <style>
        /* Tetap gunakan CSS asli Anda */
        body { background: #FFFFFF; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .auth-card { background: white; padding: 40px; border-radius: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 350px; text-align: center; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #eee; border-radius: 12px; box-sizing: border-box; }
        button { width: 100%; padding: 14px; background: #008080; color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; }
        .tabs { display: flex; margin-bottom: 20px; background: #f5f5f5; border-radius: 10px; padding: 5px; }
        .tab { flex: 1; padding: 8px; cursor: pointer; font-size: 13px; border-radius: 8px; }
        .tab.active { background: #008080; color: white; }
    </style>
</head>
<body>
    <div class="auth-card">
        <h2>ECHOES</h2>
        <?php if($error) echo "<p style='color:red'>$error</p>"; ?>
        <?php if($success) echo "<p style='color:green'>$success</p>"; ?>
        <div class="tabs">
            <div id="t1" class="tab active" onclick="show('login')">LOGIN</div>
            <div id="t2" class="tab" onclick="show('reg')">SIGN UP</div>
        </div>
        <form id="f1" method="POST"><input type="email" name="email" placeholder="Email"><input type="password" name="password" placeholder="Password"><button name="login">ENTER</button></form>
        <form id="f2" method="POST" style="display:none"><input type="text" name="name" placeholder="Name"><input type="email" name="email" placeholder="Email"><input type="color" name="favorite_color" value="#008080"><input type="password" name="password" placeholder="Password"><button name="register">JOIN</button></form>
    </div>
    <script>
        function show(p){
            document.getElementById('f1').style.display = p=='login'?'block':'none';
            document.getElementById('f2').style.display = p=='reg'?'block':'none';
            document.getElementById('t1').className = p=='login'?'tab active':'tab';
            document.getElementById('t2').className = p=='reg'?'tab active':'tab';
        }
    </script>
</body>
</html>