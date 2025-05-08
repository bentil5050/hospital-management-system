<?php
session_start();
require_once '../includes/connect-db.php';
require_once '../includes/auth.php';

$userId = $_SESSION['user_id'];

// Fetch patient full name
$stmt = $pdo->prepare("SELECT full_name FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$patient = $stmt->fetch();
$fullName = $patient ? $patient['full_name'] : 'Patient';

// Get patient_id
$stmt = $pdo->prepare("SELECT patient_id FROM patients WHERE user_id = ?");
$stmt->execute([$userId]);
$patientRow = $stmt->fetch();
$patientId = $patientRow['patient_id'] ?? null;

// Fetch appointments
$appointments = [];
if ($patientId) {
    $stmt = $pdo->prepare("SELECT a.*, u.full_name AS doctor_name
                           FROM appointments a
                           JOIN doctors d ON a.doctor_id = d.doctor_id
                           JOIN users u ON d.user_id = u.user_id
                           WHERE a.patient_id = ?
                           ORDER BY a.appointment_datetime ASC");
    $stmt->execute([$patientId]);
    $appointments = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6fc;
            margin: 0;
        }

        .sidebar {
            width: 220px;
            background-color: #2f3e9e;
            color: white;
            position: fixed;
            height: 100vh;
            top: 0;
            left: 0;
            padding: 30px 20px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 0;
            font-weight: bold;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }

        .main-content {
            margin-left: 240px;
            padding: 40px;
        }

        .main-content h2 {
            color: #2f3e9e;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 14px 18px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #2f3e9e;
            color: white;
        }

        .status {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .Booked {
            background-color: #fff3cd;
            color: #856404;
        }

        .Completed {
            background-color: #d4edda;
            color: #155724;
        }

        .Canceled {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Patient Panel</h2>
    <a href="patient-dashboard.php">Dashboard</a>
    <a href="patient-prescriptions.php">My Prescriptions</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <h2>Welcome, <?= htmlspecialchars($fullName) ?></h2>
    <h3>My Appointments</h3>

    <?php if ($appointments): ?>
        <table>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Doctor</th>
                    <th>Purpose</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['appointment_datetime']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($row['purpose']) ?></td>
                        <td><span class="status <?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no upcoming appointments.</p>
    <?php endif; ?>
</div>
</body>
</html>
