<?php
session_start();
require_once '../includes/connect-db.php';
require_once '../includes/auth.php';

$user_id = $_GET['id'] ?? null;
$error = '';
$success = '';

if (!$user_id) {
    header("Location: dashboard.php");
    exit;
}

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    $error = "User not found.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';

    if ($full_name && $username && $email && $phone_number) {
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, username = ?, email = ?, phone_number = ? WHERE user_id = ?");
        $stmt->execute([$full_name, $username, $email, $phone_number, $user_id]);
        $success = "User updated successfully!";
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f9fc;
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
        }
        h2 {
            color: #3b5bdb;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-top: 12px;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #3b5bdb;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2c44b0;
        }
        .alert {
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .alert-danger {
            background: #ffe6e6;
            color: #cc0000;
        }
        .alert-success {
            background: #e6ffed;
            color: #007f3f;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        .back-link a {
            text-decoration: none;
            color: #3b5bdb;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit User</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Full Name</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Phone Number</label>
        <input type="text" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required>

        <button type="submit">Update</button>
    </form>

    <div class="back-link">
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
