<?php
require_once '../includes/connect-db.php';
require_once '../includes/auth.php';

$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id) {
    die("Invalid appointment ID.");
}

$stmt = $pdo->prepare("DELETE FROM appointments WHERE appointment_id = ?");
$stmt->execute([$appointment_id]);

header("Location: appointments.php");
exit;
?>
