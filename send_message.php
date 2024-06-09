<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "blood_donors";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobile_number = $_POST['mobile_number'];
    $message = $_POST['message'];

    $sql = "INSERT INTO chat (mobile_number, message) VALUES ('$mobile_number', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Message sent successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
