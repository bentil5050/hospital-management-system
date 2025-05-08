<?php
require_once '../includes/connect-db.php';
require_once '../includes/auth.php';

$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id) {
    die("Invalid appointment ID.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datetime = $_POST['appointment_datetime'];
    $purpose = $_POST['purpose'];
    $status = $_POST['status'];

    $update_sql = "UPDATE appointments 
                   SET appointment_datetime = ?, purpose = ?, status = ? 
                   WHERE appointment_id = ?";
    $stmt = $pdo->prepare($update_sql);
    $stmt->execute([$datetime, $purpose, $status, $appointment_id]);

    header("Location: appointments.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM appointments WHERE appointment_id = ?");
$stmt->execute([$appointment_id]);
$appointment = $stmt->fetch();

if (!$appointment) {
    die("Appointment not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #3f51b5;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            background: #3f51b5;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 6px;
            font-size: 16px;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #3f51b5;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Appointment</h2>
        <form method="POST">
            <label for="appointment_datetime">Date & Time:</label>
            <input type="datetime-local" name="appointment_datetime" required value="<?= date('Y-m-d\TH:i', strtotime($appointment['appointment_datetime'])) ?>">

            <label for="purpose">Purpose:</label>
            <textarea name="purpose" rows="3"><?= htmlspecialchars($appointment['purpose']) ?></textarea>

            <label for="status">Status:</label>
            <select name="status">
                <option value="Booked" <?= $appointment['status'] === 'Booked' ? 'selected' : '' ?>>Booked</option>
                <option value="Completed" <?= $appointment['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                <option value="Canceled" <?= $appointment['status'] === 'Canceled' ? 'selected' : '' ?>>Canceled</option>
            </select>

            <button type="submit">Update Appointment</button>
        </form>

        <a href="appointments.php">← Back to Appointments</a>
    </div>
</body>
</html>
