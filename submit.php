<?php
require 'vendor/autoload.php'; // MongoDB
use MongoDB\Client;

// MySQL config
$host     = "localhost";
$username = "root";
$password = "Heth@4304";
$database = "shreshtha_advisory";

// MySQL connection
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize POST data
$fullname    = $conn->real_escape_string($_POST['fullname'] ?? '');
$countrycode = $conn->real_escape_string($_POST['countrycode'] ?? '');
$mobile      = $conn->real_escape_string($_POST['mobile'] ?? '');
$email       = $conn->real_escape_string($_POST['email'] ?? '');
$city        = $conn->real_escape_string($_POST['city'] ?? '');
$service     = $conn->real_escape_string($_POST['service'] ?? '');
$networth    = $conn->real_escape_string($_POST['networth'] ?? '');
$referral    = $conn->real_escape_string($_POST['referral'] ?? '');

// Insert into MySQL
$conn->query("
    CREATE TABLE IF NOT EXISTS leads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(255) NOT NULL,
        countrycode VARCHAR(10),
        mobile VARCHAR(15),
        email VARCHAR(255),
        city VARCHAR(100),
        service VARCHAR(100),
        networth VARCHAR(50),
        referral VARCHAR(100),
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$sql = "INSERT INTO leads (fullname, countrycode, mobile, email, city, service, networth, referral)
        VALUES ('$fullname', '$countrycode', '$mobile', '$email', '$city', '$service', '$networth', '$referral')";
$conn->query($sql);
$conn->close();

// Insert into MongoDB Atlas
try {
    $mongoClient = new Client("mongodb+srv://Heth:gamerhskrocks@shreshtha-db.fsmuwpv.mongodb.net/?retryWrites=true&w=majority&appName=shreshtha-db");
    $mongoCollection = $mongoClient->{"shreshtha-db"}->leads;

    $mongoCollection->insertOne([
        'fullname'    => $fullname,
        'countrycode' => $countrycode,
        'mobile'      => $mobile,
        'email'       => $email,
        'city'        => $city,
        'service'     => $service,
        'networth'    => $networth,
        'referral'    => $referral,
        'submitted_at'=> new MongoDB\BSON\UTCDateTime()
    ]);
} catch (Exception $e) {
    error_log("MongoDB error (leads): " . $e->getMessage());
}

echo "✅ Thank you! Your response has been recorded.";
?>


