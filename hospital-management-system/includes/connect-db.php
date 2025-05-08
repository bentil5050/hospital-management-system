<?php
// File: includes/connect-db.php

$host = 'localhost';                // Server host (usually localhost)
$dbname = 'hospital_database';     // Your database name
$username = 'root';                // MySQL username
$password = '';                    // MySQL password (empty by default in XAMPP)
$charset = 'utf8mb4';              // Character encoding

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return data as associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
