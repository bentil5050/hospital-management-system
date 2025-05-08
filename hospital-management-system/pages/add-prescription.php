<?php
session_start();
require_once '../includes/connect-db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header('Location: login.php');
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

// Fetch only users who are patients
$stmt = $pdo->prepare("
    SELECT u.full_name as patient_name, mr.diagnosis, mr.record_id
    FROM patients p
    JOIN medicalrecords mr on mr.patient_id = p.patient_id
	JOIN doctors d on d.doctor_id = mr.doctor_id
	JOIN users u on p.user_id = u.user_id
	WHERE mr.doctor_id = ?
	AND u.role = 'Patient'");
$stmt->execute([$doctorId]);
$records = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dosage = $_POST['dosage'];
    $medication = $_POST['medication'];
    $instructions = $_POST['instructions'];
	$record_id = $_POST['record_id']; //TODO - NEED TO GET RECORD ID BASED ON SELECTED PATIENT ID

    $stmt = $pdo->prepare("INSERT INTO prescriptions (dosage, instructions, medicine_name, record_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$dosage, $instructions, $medication, $record_id]);

    header("Location: doctor-prescriptions.php");
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
        <h2>➕ Add Prescription</h2>
		
		<form method="post">
			<div class="form-group">
				<label for="record_id">Select Medical Record</label>
				<select name="record_id" id="record_id" required>
					<option value="">-- Choose a Patient --</option>
					<?php foreach ($records as $record): ?>
						<option value="<?= htmlspecialchars($record['record_id']) ?>">
							<?= htmlspecialchars($record['patient_name'] . ' - ' . $record['diagnosis']) ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

            <div class="form-group">
                <label for="medication">Medication:</label>
                <input type="text" name="medication" required><br>
            </div>

            <div class="form-group">
                <label for="dosage">Dosage:</label>
                <input type="text" name="dosage" required><br>
            </div>

            <div class="form-group">
                <label for="instructions">Instructions:</label>
                <textarea name="instructions" required></textarea><br>
            </div>

            <button type="submit">Add Prescription</button>
        </form>

		<div class="back-link">
			<a href="doctor-prescriptions.php" class="back-link">Back to Prescriptions</a>
		</div>
	</div>
</body>
</html>
