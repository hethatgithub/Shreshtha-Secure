<?php
require 'vendor/autoload.php'; // MongoDB library
use MongoDB\Client;

// MySQL config
$host     = "localhost";
$username = "root";
$password = "Heth@4304";
$database = "shreshtha_advisory";

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("❌ MySQL connection failed: " . $conn->connect_error);
}

// Sanitize form data
$email   = $conn->real_escape_string($_POST['email'] ?? '');
$message = $conn->real_escape_string($_POST['message'] ?? '');

if (!$email || !$message) {
    echo "❌ Email and message are required.";
    exit;
}

// Insert into MySQL
$conn->query("
    CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$sql = "INSERT INTO messages (email, message) VALUES ('$email', '$message')";
$conn->query($sql);
$conn->close();

// Insert into MongoDB Atlas
try {
    $mongoClient = new Client("mongodb+srv://Heth:gamerhskrocks@shreshtha-db.fsmuwpv.mongodb.net/?retryWrites=true&w=majority&appName=shreshtha-db");
    $mongoCollection = $mongoClient->{"shreshtha-db"}->messages;

    $mongoCollection->insertOne([
        'email' => $email,
        'message' => $message,
        'submitted_at' => new MongoDB\BSON\UTCDateTime()
    ]);
} catch (Exception $e) {
    error_log("MongoDB error (messages): " . $e->getMessage());
}

echo "✅ Your message has been received.";
?>


