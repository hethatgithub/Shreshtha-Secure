<?php
// Set your email address
$to = "shreshthaadvisory23@gmail.com";

// Get form values
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars(trim($_POST['message']));

// Validate email and message
if ($email && !empty($message)) {
    $subject = "New message from Shreshtha Advisory contact form";
    $body = "You have received a new message from: $email\n\nMessage:\n$message";
    $headers = "From: $email\r\nReply-To: $email\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "<h2>Thank you! Your message has been sent.</h2>";
    } else {
        echo "<h2>Oops! Something went wrong. Please try again later.</h2>";
    }
} else {
    echo "<h2>Please provide a valid email and message.</h2>";
}
?>
