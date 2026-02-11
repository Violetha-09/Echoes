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
            $sql = "INSERT INTO users (name, email, password, favorite_color) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if($stmt->execute([$name, $email, $hashed_pass, $color])) {
                $success = "Galaxy Created! Silakan login.";
            } else {
                $error = "Gagal mendaftar.";
            }
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
    } else {
        $error = "Email atau Password salah.";
    }
}
?>