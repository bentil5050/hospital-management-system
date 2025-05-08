<?php
require_once '../includes/connect-db.php';
require_once '../includes/auth.php';

// Fetch doctors
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'Doctor'");
$stmt->execute();
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctors List</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f7fb;
      margin: 0;
      padding: 40px;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.08);
      padding: 30px;
    }

    h2 {
      color: #2f3e9e;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
    }

    th {
      background-color: #2f3e9e;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .action-btn {
      background-color: #4CAF50;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      text-decoration: none;
    }

    .back-link {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #2f3e9e;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Doctors List</h2>
    <table>
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($doctors as $doctor): ?>
        <tr>
          <td><?= htmlspecialchars($doctor['full_name']) ?></td>
          <td><?= htmlspecialchars($doctor['username']) ?></td>
          <td><?= htmlspecialchars($doctor['email']) ?></td>
          <td><?= htmlspecialchars($doctor['phone_number']) ?></td>
          <td>
            <a href="edit-user.php?id=<?= $doctor['user_id'] ?>" class="action-btn">Edit</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
  </div>
</body>
</html>
