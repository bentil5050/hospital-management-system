<?php
require_once '../includes/connect-db.php';
require_once '../includes/auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointments</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .appointments-container {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .appointments-container h2 {
            color: #3f51b5;
            font-size: 28px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .top-buttons {
            display: flex;
            gap: 10px;
        }

        .add-btn,
        .export-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
        }

        .add-btn:hover {
            background-color: #218838;
        }

        .export-btn {
            background-color: #3f51b5;
        }

        .export-btn:hover {
            background-color: #2c3c9e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #3f51b5;
            color: #fff;
            text-align: left;
            padding: 12px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        a.action-btn {
            padding: 6px 12px;
            margin-right: 5px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }

        a.edit {
            background-color: #0288d1;
        }

        a.delete {
            background-color: #e53935;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #3f51b5;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="appointments-container">
    <h2>
        <span>📅 All Appointments</span>
        <?php if ($_SESSION['role'] === 'Admin'): ?>
            <span class="top-buttons">
                <a href="add-appointment.php" class="add-btn">➕ Add New Appointment</a>
                <a href="export-appointments.php" class="export-btn">⬇ Export CSV</a>
            </span>
        <?php endif; ?>
    </h2>

    <?php
    try {
        $stmt = $pdo->query("
            SELECT a.appointment_id, u1.full_name AS patient_name, u2.full_name AS doctor_name, 
                   a.appointment_datetime, a.purpose, a.status
            FROM appointments a
            JOIN patients p ON a.patient_id = p.patient_id
            JOIN users u1 ON p.user_id = u1.user_id
            JOIN doctors d ON a.doctor_id = d.doctor_id
            JOIN users u2 ON d.user_id = u2.user_id
            ORDER BY a.appointment_datetime DESC
        ");

        $appointments = $stmt->fetchAll();

        if ($appointments && count($appointments) > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date & Time</th>
                    <th>Purpose</th>
                    <th>Status</th>";
            if ($_SESSION['role'] === 'Admin') {
                echo "<th>Actions</th>";
            }
            echo "</tr>";

            foreach ($appointments as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['doctor_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['appointment_datetime']) . "</td>";
                echo "<td>" . htmlspecialchars($row['purpose']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                if ($_SESSION['role'] === 'Admin') {
                    echo "<td>
                            <a class='action-btn edit' href='edit-appointment.php?id=" . $row['appointment_id'] . "'>Edit</a>
                            <a class='action-btn delete' href='delete-appointment.php?id=" . $row['appointment_id'] . "' onclick=\"return confirm('Are you sure you want to delete this appointment?');\">Delete</a>
                          </td>";
                }
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No appointments found.</p>";
        }
    } catch (PDOException $e) {
        echo "Error fetching appointments: " . $e->getMessage();
    }
    ?>

    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>
