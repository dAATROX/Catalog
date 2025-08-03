<?php 
include 'db.php'; 
session_start();

// Auto Login ด้วย Cookie Token
if (isset($_COOKIE['auth_token'])) {
    $token = $_COOKIE['auth_token'];
    $sql = "SELECT username FROM users WHERE auth_token='$token' LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        header("Location: home.php");
        exit;
    }
}

// เตรียมค่ากรอกอัตโนมัติ
$saved_username = isset($_COOKIE['saved_username']) ? $_COOKIE['saved_username'] : "";
$saved_password = isset($_COOKIE['saved_password']) ? $_COOKIE['saved_password'] : "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $conn->query("UPDATE users SET last_login=NOW() WHERE username='$username'");

            if ($remember) {
                $token = bin2hex(random_bytes(16));
                $conn->query("UPDATE users SET auth_token='$token' WHERE username='$username'");
                setcookie('auth_token', $token, time() + (7 * 24 * 60 * 60), "/");
                setcookie('saved_username', $username, time() + (7 * 24 * 60 * 60), "/");
                setcookie('saved_password', $password, time() + (7 * 24 * 60 * 60), "/");
            }

            header("Location: home.php");
            exit;
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง');</script>";
        }
    } else {
        echo "<script>alert('ไม่พบผู้ใช้');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <img src="images/logo2.png" class="logo" alt="Bluestone Logo">
    <h2>Login</h2>
    <form method="POST">
    <div class="password-container">
    <input type="text" name="username" placeholder="Username" value="<?php echo $saved_username; ?>" required>
</div>
<div class="password-container">
    <input type="password" name="password" id="password" placeholder="Password" value="<?php echo $saved_password; ?>" required>
    <span class="toggle-password" onclick="togglePassword()">👁</span>
</div>

<label class="remember">
    <input type="checkbox" name="remember">
    <span class="checkmark"></span> Remember Me
</label>
        <button type="submit">Login</button>
    </form>
    <a href="register.php" class="register-link">Don't have an account?</a>
    <a href="forgot_password_step1.php" class="forgot-link">Forgotten password?</a>
    <hr>
    <p>Contact Us</p>
    <div class="social-icons">
        <a href="https://www.facebook.com/Bluestone.co.th/" target="_blank">
            <img src="images/icons-facebook.png" alt="Facebook">
        </a>
        <a href="https://www.instagram.com/bluestonethailand/" target="_blank">
            <img src="images/icons-ig.png" alt="Instagram">
        </a>
        <a href="https://line.me/ti/p/~@bluestonethailand" target="_blank">
            <img src="images/icons-line.png" alt="Line">
        </a>
        <a href="https://www.youtube.com/channel/UCQ3mRpetmm5Ek-LLdTjwaNQ" target="_blank">
            <img src="images/icons-youtube.png" alt="YouTube">
        </a>
    </div>
    <a href="https://www.bluestone.co.th" class="website-link">www.bluestone.co.th</a>
</div>
<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    passwordField.type = (passwordField.type === "password") ? "text" : "password";
}
</script>
</body>
</html>
