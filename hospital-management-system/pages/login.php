<?php
// pages/login.php
session_start();
require_once '../includes/connect-db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // ✅ Redirect based on user role
            if ($user['role'] === 'Admin') {
                header("Location: dashboard.php");
            } elseif ($user['role'] === 'Doctor') {
                header("Location: doctor-dashboard.php");
            } elseif ($user['role'] === 'Patient') {
                header("Location: patient-dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | HospitalSys</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <!-- Left Panel -->
        <div class="login-left">
            <img src="../assets/img/login-doctor.png" alt="Doctor">
        </div>

        <!-- Right Panel -->
        <div class="login-right">
            <div class="login-form-wrapper">
                <div class="hospital-brand">HospitalSys</div>
                <h2>Welcome back!</h2>
                <p style="text-align:center; font-size: 12px; color: #777;">Login to your account to continue</p>

                <?php if ($error): ?>
                    <div style="color: red; text-align:center; margin-bottom: 10px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <label>Username</label>
                    <input type="text" name="username" required>

                    <label>Password</label>
                    <input type="password" name="password" id="password" required>

                    <div style="margin-bottom: 10px;">
                        <input type="checkbox" onclick="togglePassword()"> Show password
                        <input type="checkbox" name="remember"> Remember me
                    </div>

                    <button type="submit">Login</button>

                    <small>Don’t have an account?<br>
                    <span>Please contact the hospital front help desk (888-678-5671)</span></small>
                </form>
            </div>
        </div>
    </div>

    <script>
    function togglePassword() {
        const pwd = document.getElementById("password");
        pwd.type = pwd.type === "password" ? "text" : "password";
    }
    </script>
</body>
</html>
