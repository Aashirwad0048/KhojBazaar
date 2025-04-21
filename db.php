<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'login_db';

// Connect to MySQL server
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS `$db`";
if ($conn->query($sql) === TRUE) {
    echo "✅ Database '$db' created or already exists.<br>";
} else {
    die("❌ Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($db);

// Create users table
$tableSql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($tableSql) === TRUE) {
    echo "✅ Table 'users' created or already exists.<br>";
} else {
    die("❌ Error creating table: " . $conn->error);
}

// Insert a test user (only if it doesn't exist)
$username = 'admin';
$email = 'admin@example.com';
$plainPassword = 'admin123';
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Check if the user already exists
$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $username, $email, $hashedPassword);
    if ($insert->execute()) {
        echo "✅ Test user 'admin' added (Username: <b>admin</b>, Password: <b>$plainPassword</b>).<br>";
    } else {
        echo "❌ Error inserting user: " . $insert->error;
    }
    $insert->close();
} else {
    echo "ℹ️ Test user 'admin' already exists.<br>";
}

$check->close();
$conn->close();
?>
