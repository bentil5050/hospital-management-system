<?php
// pages/login.php
session_start();
require_once '../includes/connect-db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | HospitalSys</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f7f7f7;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .left {
            flex: 1;
            background: url('../assets/login-doctor.jpg') no-repeat center center;
            background-size: cover;
        }
        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
        }
        .login-form {
            width: 100%;
            max-width: 400px;
            padding: 30px;
        }
        .login-form h2 {
            margin-bottom: 20px;
            color: #2b2b2b;
        }
        .login-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-form button {
            width: 100%;
            padding: 12px;
            background-color: #3474f0;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .login-form .options {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .login-form .contact-note {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left"></div>
    <div class="right">
        <form class="login-form" method="POST">
            <h2>Welcome back!</h2>
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Enter your username" required>
            <input type="password" name="password" placeholder="Enter password" required>
            <div class="options">
                <label><input type="checkbox"> Remember me</label>
                <a href="#">Forgot password?</a>
            </div>
            <button type="submit">Login</button>
            <p class="contact-note">
                Don’t have an account? Please contact the hospital front help desk (888-678-5671).
            </p>
        </form>
    </div>
</div>
</body>
</html>
