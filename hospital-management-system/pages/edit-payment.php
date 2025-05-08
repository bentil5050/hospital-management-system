<?php
session_start();
require_once '../includes/connect-db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: manage-payments.php');
    exit();
}

$payment_id = $_GET['id'];
$error = '';
$success = '';

$stmt = $pdo->prepare("SELECT * FROM payments WHERE payment_id = ?");
$stmt->execute([$payment_id]);
$payment = $stmt->fetch();

if (!$payment) {
    $error = "Payment record not found.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $status = $_POST['payment_status'];
    $date = $_POST['payment_date'];

    if ($amount && $status && $date) {
        $update = $pdo->prepare("UPDATE payments SET amount = ?, payment_status = ?, payment_date = ? WHERE payment_id = ?");
        if ($update->execute([$amount, $status, $date, $payment_id])) {
            $success = "Payment updated successfully.";
            // Refresh the payment data
            $stmt->execute([$payment_id]);
            $payment = $stmt->fetch();
        } else {
            $error = "Failed to update payment.";
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
    <title>Edit Payment | HospitalSys</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2f3e9e;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #2f3e9e;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #25359a;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
        }

        .success {
            color: green;
            margin-top: 10px;
            text-align: center;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-pen"></i> Edit Payment</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($payment): ?>
    <form method="POST">
        <label for="amount">Amount ($):</label>
        <input type="number" name="amount" id="amount" value="<?= htmlspecialchars($payment['amount']) ?>" required>

        <label for="payment_status">Status:</label>
        <select name="payment_status" id="payment_status" required>
            <option value="Completed" <?= $payment['payment_status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
            <option value="Pending" <?= $payment['payment_status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Failed" <?= $payment['payment_status'] === 'Failed' ? 'selected' : '' ?>>Failed</option>
        </select>

        <label for="payment_date">Date:</label>
        <input type="date" name="payment_date" id="payment_date" value="<?= htmlspecialchars($payment['payment_date']) ?>" required>

        <button type="submit">Update Payment</button>
    </form>
    <?php endif; ?>

    <div class="back-link">
        <a href="manage-payments.php">← Back to Manage Payments</a>
    </div>
</div>

</body>
</html>
