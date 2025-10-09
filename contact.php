<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost"; 
$username   = "root";      
$password   = "";    
$dbname     = "email_bouns";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get values from form
$email = $_POST['email'];
$name  = $_POST['name'];

// Prevent SQL injection
$email = $conn->real_escape_string($email);
$name  = $conn->real_escape_string($name);

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

// Check if email already exists
$sql = "SELECT * FROM emails WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Email exists → redirect to "already exists" page
    header("Location: exists.html");
    exit();
} else {
    // Insert new name + email
    $sql = "INSERT INTO emails (name, email) VALUES ('$name', '$email')";
    if ($conn->query($sql) === TRUE) {
        // Redirect to thank you page
        header("Location: thanks.html");
        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'team.haryantam@gmail.com';
            $mail->Password   = 'iknxziumbnayjtln';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('team.haryantam@gmail.com', 'Haryantam');
            $mail->addAddress($email, $name); 

   
            $mail->isHTML(true);
            $mail->Subject = "Welcome to Haryantam You have Earned 1000 Green Points!";
            $mail->Body    = "Hello $name, <br><br>
                            🌱 Thank you for subscribing and showing interest in our idea!  
                            We’re truly excited to have you as part of our growing community.  
                            <br>
                            As a special thank you, we’ve added **1000 Bonus Green Points** 🎁 to your account.  
                            These points can be used once we launch our platform, giving you early rewards for believing in us.  
                            <br>
                            Stay tuned 🚀 – we’ll notify you as soon as we launch exciting features, updates, and exclusive opportunities.  

                            <br><br>
                            With gratitude, <br>
                            <strong>The Haryantam Team</strong> 🌿";

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
