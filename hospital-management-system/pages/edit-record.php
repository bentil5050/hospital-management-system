<?php
session_start();
require_once '../includes/connect-db.php';
require_once '../includes/auth.php';

if ($_SESSION['role'] !== 'Doctor') {
    header('Location: login.php');
    exit();
}

$recordId = $_GET['id'] ?? null;
$doctorId = null;
$stmt = $pdo->prepare("SELECT doctor_id FROM doctors WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$doctorId = $stmt->fetchColumn();

if (!$recordId || !$doctorId) {
    echo "<p>Invalid access.</p>";
    exit();
}

$stmt = $pdo->prepare("SELECT r.*, u.full_name FROM medicalrecords r
                      JOIN patients p ON r.patient_id = p.patient_id
                      JOIN users u ON p.user_id = u.user_id
                      WHERE r.record_id = ? AND r.doctor_id = ?");
$stmt->execute([$recordId, $doctorId]);
$record = $stmt->fetch();

if (!$record) {
    echo "<p>Record not found or you do not have access.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosis = $_POST['diagnosis'];
    $treatment_plan = $_POST['treatment_plan'];

    $updateStmt = $pdo->prepare("UPDATE medicalrecords SET diagnosis = ?, treatment_plan = ? WHERE record_id = ? AND doctor_id = ?");
    $updateStmt->execute([$diagnosis, $treatment_plan, $recordId, $doctorId]);

    header("Location: doctor-medical-records.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Medical Record | HospitalSys</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fc;
            margin: 0;
            padding: 40px;
        }
        .form-box {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2f3e9e;
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        textarea, input[type="text"] {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 8px;
        }
        button {
            background-color: #2f3e9e;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2f3e9e;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Edit Medical Record for <?= htmlspecialchars($record['full_name']) ?></h2>

        <form method="POST">
            <label for="diagnosis">Diagnosis</label>
            <textarea name="diagnosis" id="diagnosis" rows="4" required><?= htmlspecialchars($record['diagnosis']) ?></textarea>

            <label for="treatment_plan">Treatment Plan</label>
            <textarea name="treatment_plan" id="treatment_plan" rows="4" required><?= htmlspecialchars($record['treatment_plan']) ?></textarea>

            <button type="submit">Update Record</button>
        </form>

        <a href="doctor-medical-records.php">&larr; Back to Records</a>
    </div>
</body>
</html>
