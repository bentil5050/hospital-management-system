<?php
session_start();
require_once '../includes/connect-db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header('Location: login.php');
    exit();
}

$loggedInUserId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT doctor_id FROM doctors WHERE user_id = ?");
$stmt->execute([$loggedInUserId]);
$doctor = $stmt->fetch();
$doctorId = $doctor['doctor_id'];

$filterStatus = $_GET['status'] ?? 'All';
$statusQuery = "";
$params = [$doctorId];

if ($filterStatus !== 'All') {
    $statusQuery = " AND a.status = ?";
    $params[] = $filterStatus;
}

$stmt = $pdo->prepare("SELECT a.*, u.full_name AS patient_name
    FROM appointments a
    JOIN patients p ON a.patient_id = p.patient_id
    JOIN users u ON p.user_id = u.user_id
    WHERE a.doctor_id = ? $statusQuery
    ORDER BY a.appointment_datetime ASC");
$stmt->execute($params);
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6fc;
        }
        .sidebar {
            width: 220px;
            background-color: #2f3e9e;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 30px;
            color: white;
        }
        .sidebar h2 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #1a237e;
        }
        .main-content {
            margin-left: 220px;
            padding: 40px;
        }
        .main-content h2 {
            font-size: 26px;
            color: #2f3e9e;
        }
        .filter-buttons {
            margin: 20px 0;
        }
        .filter-buttons a {
            padding: 8px 16px;
            margin-right: 10px;
            background-color: #e0e0e0;
            border-radius: 20px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }
        .filter-buttons a.active {
            background-color: #2f3e9e;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2f3e9e;
            color: white;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Doctor Panel</h2>
    <a href="doctor-dashboard.php">Dashboard</a>
    <a href="doctor-medical-records.php">Medical Records</a>
	<a href="doctor-prescriptions.php">Prescriptions</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main-content">
    <h2>Welcome, Doctor 🩺</h2>
    <p>Here are your upcoming appointments:</p>

    <div class="filter-buttons">
        <a href="?status=All" class="<?= $filterStatus === 'All' ? 'active' : '' ?>">All</a>
        <a href="?status=Completed" class="<?= $filterStatus === 'Completed' ? 'active' : '' ?>">Completed</a>
        <a href="?status=Canceled" class="<?= $filterStatus === 'Canceled' ? 'active' : '' ?>">Canceled</a>
        <a href="?status=Booked" class="<?= $filterStatus === 'Booked' ? 'active' : '' ?>">Booked</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>Date & Time</th>
            <th>Patient</th>
            <th>Purpose</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($appointments): ?>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['appointment_datetime']) ?></td>
                    <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['purpose']) ?></td>
                    <td><?= htmlspecialchars($appointment['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">No appointments found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
