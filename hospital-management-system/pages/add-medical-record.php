<?php
session_start();
require_once '../includes/connect-db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header('Location: ../pages/login.php');
    exit();
}

$loggedInUserId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT doctor_id FROM doctors WHERE user_id = ?");
$stmt->execute([$loggedInUserId]);
$doctor = $stmt->fetch();
$doctorId = $doctor['doctor_id'] ?? null;

if (!$doctorId) {
    echo "Doctor not found.";
    exit();
}

// Fetch only users who are patients
$stmt = $pdo->query("
    SELECT patients.patient_id, users.full_name
    FROM patients
    JOIN users ON patients.user_id = users.user_id
    WHERE users.role = 'Patient'
");
$patients = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosis = $_POST['diagnosis'];
    $doctor_id = $doctorId;
    $patient_id = $_POST['patient_id'];
	$treatment_plan = $_POST['treatment_plan']; //TODO - NEED TO GET RECORD ID BASED ON SELECTED PATIENT ID

    $stmt = $pdo->prepare("INSERT INTO medicalrecords (diagnosis, doctor_id, patient_id, treatment_plan) VALUES (?, ?, ?, ?)");
    $stmt->execute([$diagnosis, $doctor_id, $patient_id, $treatment_plan]);

    header("Location: doctor-medical-records.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Medical Record</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6fc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 50px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        h2 {
            color: #2f3e9e;
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 15px 0 8px;
        }
        select, input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
        }
        button {
            background-color: #2f3e9e;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 25px;
            display: block;
            width: 100%;
        }
        button:hover {
            background-color: #1e2f87;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #2f3e9e;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>➕ Add Medical Record</h2>

    <form method="post">
        <label for="patient_id">Select Patient</label>
        <select name="patient_id" id="patient_id" required>
            <option value="">-- Choose a Patient --</option>
            <?php foreach ($patients as $patient): ?>
                <option value="<?= $patient['patient_id'] ?>">
                    <?= htmlspecialchars($patient['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="diagnosis">Diagnosis</label>
        <input type="text" name="diagnosis" id="diagnosis" required>

        <label for="treatment_plan">Treatment Plan</label>
        <textarea name="treatment_plan" id="treatment_plan" rows="5" required></textarea>

        <button type="submit">Save Medical Record</button>
    </form>

    <div class="back-link">
        <a href="doctor-medical-records.php">← Back to Medical Records</a>
    </div>
</div>

</body>
</html>
