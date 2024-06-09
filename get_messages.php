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

$mobile_number = $_GET['mobile_number'];
$sql = "SELECT * FROM chat WHERE mobile_number='$mobile_number' ORDER BY timestamp ASC";
$result = $conn->query($sql);

$messages = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

echo json_encode($messages);

$conn->close();
?>
