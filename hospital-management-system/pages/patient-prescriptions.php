<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/connect-db.php';

// Get current patient user_id from session
$loggedInUserId = $_SESSION['user_id'];

// Get patient_id from user_id
$stmt = $pdo->prepare("SELECT patient_id FROM patients WHERE user_id = ?");
$stmt->execute([$loggedInUserId]);
$patient = $stmt->fetch();

if (!$patient) {
    echo "Patient not found.";
    exit;
}

$patientId = $patient['patient_id'];

// Fetch prescriptions with doctor name
$stmt = $pdo->prepare("SELECT u.full_name AS doctor_name, pr.medicine_name, pr.dosage, pr.instructions
                       FROM prescriptions pr
                       JOIN medicalrecords mr ON pr.record_id = mr.record_id
                       JOIN doctors d ON mr.doctor_id = d.doctor_id
                       JOIN users u ON d.user_id = u.user_id
                       WHERE mr.patient_id = ?");
$stmt->execute([$patientId]);
$prescriptions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Prescriptions | Patient Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6fc;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 220px;
            background-color: #2f3e9e;
            color: white;
            height: 100vh;
            padding: 30px 20px;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #1d296c;
        }

        .main-content {
            flex-grow: 1;
            padding: 40px;
        }

        .main-content h2 {
            color: #2f3e9e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        th, td {
            padding: 14px;
            text-align: left;
        }

        th {
            background-color: #3f51b5;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
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
    <h2>📋 My Prescriptions</h2>

    <table>
        <tr>
            <th>Doctor</th>
            <th>Medicine</th>
            <th>Dosage</th>
            <th>Instructions</th>
        </tr>
        <?php foreach ($prescriptions as $pres): ?>
            <tr>
                <td><?= htmlspecialchars($pres['doctor_name']) ?></td>
                <td><?= htmlspecialchars($pres['medicine_name']) ?></td>
                <td><?= htmlspecialchars($pres['dosage']) ?></td>
                <td><?= htmlspecialchars($pres['instructions']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
