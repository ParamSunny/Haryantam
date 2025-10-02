<?php
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

// Check if email already exists
$sql = "SELECT * FROM emails WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Email exists → redirect to "already exists" page
    header("Location: exists.php");
    exit();
} else {
    // Insert new name + email
    $sql = "INSERT INTO emails (name, email) VALUES ('$name', '$email')";
    if ($conn->query($sql) === TRUE) {
        // Redirect to thank you page
        header("Location: thanks.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
