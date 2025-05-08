<?php
session_start();
require_once '../includes/connect-db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Force CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="appointments_sorted_by_date.csv"');

$output = fopen('php://output', 'w');

// CSV column headers
fputcsv($output, ['Appointment ID', 'Patient ID', 'Doctor ID', 'Date & Time', 'Purpose', 'Status']);

// Fetch appointments sorted by date
$stmt = $pdo->query("SELECT * FROM appointments ORDER BY appointment_datetime DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>
