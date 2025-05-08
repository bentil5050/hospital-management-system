<?php
// pages/manage-patients.php
require_once '../includes/auth.php';
require_once '../includes/connect-db.php';
include '../includes/header.php';
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Patients List</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'Patient'");
                    $stmt->execute();
                    $patients = $stmt->fetchAll();

                    if ($patients) {
                        foreach ($patients as $patient) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($patient['full_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($patient['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($patient['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($patient['phone_number']) . "</td>";
                            echo "<td>
                                <a href='edit-user.php?id={$patient['user_id']}' class='btn btn-sm btn-warning'>Edit</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No patients found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <a href="dashboard.php" class="btn btn-secondary mt-3">← Back to Dashboard</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
