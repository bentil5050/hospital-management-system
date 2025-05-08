<?php
session_start();
require_once '../includes/connect-db.php';
require_once '../includes/auth.php';

// Only Admin can access
if ($_SESSION['role'] !== 'Admin') {
    header("Location: dashboard.php");
    exit();
}

try {
    $stmt = $pdo->query("
        SELECT 
            p.payment_id,
            p.appointment_id,
            u.full_name AS patient_name,
            p.amount,
            p.payment_status,
            p.payment_date
        FROM payments p
        JOIN appointments a ON p.appointment_id = a.appointment_id
        JOIN users u ON p.patient_id = u.user_id
        ORDER BY p.payment_date DESC
    ");
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Payments</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2e4aad;
            text-align: center;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #2e4aad;
            color: #fff;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
        }
        .edit-link {
            color: #fff;
            background-color: #28a745;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
        }
        .edit-link:hover {
            background-color: #218838;
        }
        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: #2e4aad;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>💳 Manage Payments</h2>

    <?php if (count($payments) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Appointment ID</th>
                    <th>Amount ($)</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= htmlspecialchars($payment['patient_name']) ?></td>
                        <td><?= $payment['appointment_id'] ?></td>
                        <td><?= number_format($payment['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($payment['payment_status']) ?></td>
                        <td><?= $payment['payment_date'] ?></td>
                        <td><a class="edit-link" href="edit-payment.php?id=<?= $payment['payment_id'] ?>">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No payment records found.</p>
    <?php endif; ?>

    <a class="back-link" href="dashboard.php">&larr; Back to Dashboard</a>
</div>

</body>
</html>
