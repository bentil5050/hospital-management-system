<?php
require_once '../includes/connect-db.php';
require_once '../includes/auth.php';

// Fetch patients and doctors
$patients = $pdo->query("SELECT patient_id, full_name FROM users JOIN patients ON users.user_id = patients.user_id")->fetchAll(PDO::FETCH_ASSOC);
$doctors = $pdo->query("SELECT doctor_id, full_name FROM users JOIN doctors ON users.user_id = doctors.user_id")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $datetime = $_POST['appointment_datetime'];
    $purpose = $_POST['purpose'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_datetime, purpose, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$patient_id, $doctor_id, $datetime, $purpose, $status]);

    header("Location: appointments.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Appointment</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f6f9fc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            color: #4a3aff;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        select, input[type="datetime-local"], input[type="text"] {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn {
            background-color: #4a3aff;
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background-color: #372be2;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #4a3aff;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>➕ Add New Appointment</h2>
        <form method="post">
            <div class="form-group">
                <label for="patient_id">Patient:</label>
                <select name="patient_id" required>
                    <option value="">-- Select Patient --</option>
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?= $patient['patient_id'] ?>"><?= htmlspecialchars($patient['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="doctor_id">Doctor:</label>
                <select name="doctor_id" required>
                    <option value="">-- Select Doctor --</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?= $doctor['doctor_id'] ?>"><?= htmlspecialchars($doctor['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="appointment_datetime">Date & Time:</label>
                <input type="datetime-local" name="appointment_datetime" required>
            </div>

            <div class="form-group">
                <label for="purpose">Purpose:</label>
                <input type="text" name="purpose" placeholder="E.g., Follow-up, Check-up" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" required>
                    <option value="Booked">Booked</option>
                    <option value="Completed">Completed</option>
                    <option value="Canceled">Canceled</option>
                </select>
            </div>

            <button type="submit" class="btn">Add Appointment</button>
        </form>

        <a href="appointments.php" class="back-link">← Back to Appointments</a>
    </div>
</body>
</html>
