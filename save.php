<?php
// Database credentials
$servername = "localhost";
$username = "root";       // Change if your MySQL user is different
$password = "";           // Set your MySQL password
$dbname = "shreshtha";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture form data safely
$email = $_POST['email'];
$message = $_POST['message'];

// Optional: You can validate/sanitize input here

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO `test` (`email`, `message`) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $message);

if ($stmt->execute()) {
    echo "Data submitted successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>
