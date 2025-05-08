<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | HospitalSys</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6fc;
            margin: 0;
        }

        .sidebar {
            width: 240px;
            background-color: #2f3e9e;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            padding-top: 30px;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            display: block;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #1d296c;
        }

        .main-content {
            margin-left: 240px;
            padding: 40px;
        }

        .dashboard-header {
            font-size: 28px;
            color: #2f3e9e;
            margin-bottom: 10px;
        }

        .dashboard-sub {
            font-size: 16px;
            color: #333;
            margin-bottom: 30px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card i {
            font-size: 30px;
            color: #2f3e9e;
            margin-bottom: 10px;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #2f3e9e;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>HospitalSys</h2>
        <a href="create-user.php"><i class="fas fa-user-plus"></i> Create New User</a>
        <a href="manage-doctors.php"><i class="fas fa-user-md"></i> Manage Doctors</a>
        <a href="manage-patients.php"><i class="fas fa-procedures"></i> Manage Patients</a>
        <a href="appointments.php"><i class="fas fa-calendar-check"></i> View Appointments</a>
        <a href="manage-payments.php"><i class="fas fa-credit-card"></i> Manage Payments</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="dashboard-header">Welcome to HospitalSys</div>
        <div class="dashboard-sub">You are logged in as <strong>Admin</strong>.</div>

        <div class="card-grid">
            <div class="card">
                <i class="fas fa-user-plus"></i>
                <div>Create New User</div>
                <a href="create-user.php">Go</a>
            </div>
            <div class="card">
                <i class="fas fa-user-md"></i>
                <div>Manage Doctors</div>
                <a href="manage-doctors.php">Go</a>
            </div>
            <div class="card">
                <i class="fas fa-procedures"></i>
                <div>Manage Patients</div>
                <a href="manage-patients.php">Go</a>
            </div>
            <div class="card">
                <i class="fas fa-calendar-check"></i>
                <div>View Appointments</div>
                <a href="appointments.php">Go</a>
            </div>
            <div class="card">
                <i class="fas fa-credit-card"></i>
                <div>Manage Payments</div>
                <a href="manage-payments.php">Go</a>
            </div>
        </div>
    </div>
</body>
</html>
