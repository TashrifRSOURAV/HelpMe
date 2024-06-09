<?php
// Connect to your database
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$database = "blood_donors"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the registration form
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$gender = $_POST['gender'];
$blood_group = $_POST['blood_group'];
$mobile_number = $_POST['mobile_number']; // Corrected field name
$email = $_POST['email']; // New field for email
$last_donation_date = $_POST['last_donation_date'];
$location = $_POST['location'];
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

// Validate password and confirm password
if ($password !== $confirm_password) {
    die("Error: Passwords do not match");
}

// Check if the password is empty
if (empty($password)) {
    die("Error: Password cannot be empty");
}

// Insert data into the database
$sql = "INSERT INTO donors (first_name, last_name, gender, blood_group, mobile_number, email, last_donation_date, location, password)
    VALUES ('$first_name', '$last_name', '$gender', '$blood_group', '$mobile_number', '$email', '$last_donation_date', '$location', '$password')";

if ($conn->query($sql) === TRUE) {
    // echo "Registration successful!";
    header("Location: login.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
?>
