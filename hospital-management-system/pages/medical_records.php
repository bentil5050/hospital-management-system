<?php
// pages/medical_records.php
session_start();
require_once '../includes/connect-db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT mr.record_id, mr.diagnosis, mr.treatment_plan,
                            p.full_name AS patient_name,
                            d.full_name AS doctor_name
                     FROM medicalrecords mr
                     JOIN patients pt ON mr.patient_id = pt.patient_id
                     JOIN users p ON pt.patient_id = p.user_id
                     JOIN doctors dt ON mr.doctor_id = dt.doctor_id
                     JOIN users d ON dt.doctor_id = d.user_id
                     ORDER BY mr.record_id DESC");
$records = $stmt->fetchAll();
?>

<h2>Medical Records</h2>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Record ID</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Diagnosis</th>
            <th>Treatment Plan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $record): ?>
        <tr>
            <td><?= htmlspecialchars($record['record_id']) ?></td>
            <td><?= htmlspecialchars($record['patient_name']) ?></td>
            <td><?= htmlspecialchars($record['doctor_name']) ?></td>
            <td><?= htmlspecialchars($record['diagnosis']) ?></td>
            <td><?= htmlspecialchars($record['treatment_plan']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
