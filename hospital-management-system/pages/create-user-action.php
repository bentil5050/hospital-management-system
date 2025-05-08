<?php
require_once '../includes/connect-db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone_number'] ?? '';
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!$fullName || !$username || !$email || !$phone || !$role || !$password || !$confirmPassword) {
        die("All fields are required.");
    }

    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    try {
        // Check for duplicate username
        $checkUser = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $checkUser->execute([$username]);
        if ($checkUser->rowCount() > 0) {
            die("Username already exists.");
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into users table
        $stmt = $pdo->prepare("INSERT INTO users (full_name, username, email, phone_number, role, password_hash) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fullName, $username, $email, $phone, $role, $hashedPassword]);
        $userId = $pdo->lastInsertId();

        if ($role === 'Patient') {
            $insertPatient = $pdo->prepare("INSERT INTO patients (user_id, date_of_birth, gender, blood_type) 
                                            VALUES (?, '2000-01-01', 'Male', 'O+')");
            $insertPatient->execute([$userId]);

        } elseif ($role === 'Doctor') {
            $insertDoctor = $pdo->prepare("INSERT INTO doctors (user_id, specialization_id, department_id, availability_schedule, contact_number) 
                                           VALUES (?, 1, 1, 'Mon-Fri: 9AM-5PM', ?)");
            $insertDoctor->execute([$userId, $phone]);
        }

        header("Location: dashboard.php");
        exit;

    } catch (PDOException $e) {
        echo "Error creating user: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
