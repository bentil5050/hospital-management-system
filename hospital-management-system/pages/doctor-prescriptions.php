<?php
session_start();
require_once '../includes/connect-db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header('Location: ../pages/login.php');
    exit();
}

// Get logged-in doctor_id
$loggedInUserId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT doctor_id FROM doctors WHERE user_id = ?");
$stmt->execute([$loggedInUserId]);
$doctor = $stmt->fetch();
$doctorId = $doctor['doctor_id'] ?? null;

if (!$doctorId) {
    echo "Doctor not found.";
    exit();
}

// ✅ Only get prescriptions for patient users
$stmt = $pdo->prepare("
    SELECT u.full_name AS patient_name, pr.medicine_name, pr.dosage, pr.instructions
    FROM prescriptions pr
    JOIN medicalrecords mr ON pr.record_id = mr.record_id
    JOIN doctors d ON mr.doctor_id = d.doctor_id
    JOIN patients p ON mr.patient_id = p.patient_id
    JOIN users u ON p.user_id = u.user_id
    WHERE mr.doctor_id = ? AND u.role = 'Patient'
");
$stmt->execute([$doctorId]);
$prescriptions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prescriptions | Doctor Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
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

        .record-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .record-header h2 {
            color: #2f3e9e;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .add-button {
            padding: 12px 24px;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            font-size: 16px;
            text-decoration: none;
            border-radius: 6px;
        }

        .add-button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #2f3e9e;
            color: white;
            text-align: left;
            padding: 14px;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #e0e0e0;
        }

        tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        .btn-edit {
            padding: 6px 14px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-edit:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Doctor Panel</h2>
    <a href="doctor-dashboard.php">Dashboard</a>
    <a href="doctor-medical-records.php">Medical Records</a>
    <a href="doctor-prescriptions.php">Prescriptions</a>
    <a href="../pages/logout.php">Logout</a>
</div>

<div class="main-content">
    <div class="record-header">
        <h2>📋 Prescriptions</h2>
        <a href="add-prescription.php" class="add-button">+ Add New Prescription</a>
    </div>

    <table>
        <thead>
        <tr>
            <th>Patient</th>
            <th>Medicine</th>
            <th>Dosage</th>
            <th>Instructions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($prescriptions as $pres): ?>
            <tr>
                <td><?= htmlspecialchars($pres['patient_name']) ?></td>
                <td><?= htmlspecialchars($pres['medicine_name']) ?></td>
                <td><?= htmlspecialchars($pres['dosage']) ?></td>
                <td><?= htmlspecialchars($pres['instructions']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
