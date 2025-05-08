<?php
session_start();
require_once '../includes/auth.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Create New User</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      background: #f8faff;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    .form-container {
      max-width: 650px;
      margin: 40px auto;
      background: #fff;
      padding: 30px 40px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    h2 {
      text-align: center;
      color: #3b5bdb;
      margin-bottom: 25px;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: space-between;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
      width: 48%;
      padding: 12px;
      border: 1px solid #d1d1d1;
      border-radius: 6px;
      font-size: 14px;
      transition: border 0.3s;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus,
    select:focus {
      border-color: #3b5bdb;
      outline: none;
    }

    .full-width {
      width: 100%;
    }

    .form-actions {
      margin-top: 20px;
      text-align: center;
      width: 100%;
    }

    .btn {
      background-color: #3b5bdb;
      color: white;
      padding: 12px 28px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 15px;
      font-weight: bold;
      transition: background 0.3s;
    }

    .btn:hover {
      background-color: #2f4ec3;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
      display: block;
    }

    .back-link a {
      color: #3b5bdb;
      text-decoration: none;
      font-weight: bold;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2>Create New User</h2>
    <form method="POST" action="create-user-action.php">
      <input type="text" name="full_name" placeholder="Full Name" required class="full-width">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="phone_number" placeholder="Phone Number" required>
      <select name="role" required>
        <option value="">Select Role</option>
        <option value="Admin">Admin</option>
        <option value="Doctor">Doctor</option>
        <option value="Patient">Patient</option>
      </select>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <div class="form-actions">
        <button type="submit" class="btn">Create User</button>
      </div>
    </form>
    <div class="back-link">
      <a href="dashboard.php">← Back to Dashboard</a>
    </div>
  </div>

</body>
</html>
