<?php
session_start();

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

if (isset($_POST['login'])) {
    // Retrieve data from the login form
    $mobile_number = $_POST['mobile_number'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];

    // Determine which table to check based on the selected user type
    $table = ($userType === 'admin') ? 'admin' : 'donors';

    // Query to fetch user with given mobile number from the database
    $sql = "SELECT * FROM $table WHERE mobile_number='$mobile_number'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, verify password
        $row = $result->fetch_assoc();
        $db_password = $row['password'];

        if ($password === $db_password) {
            // Password is correct, redirect based on the selected user type
            $_SESSION['user_id'] = $mobile_number; // Use mobile number as user ID
            if ($userType === 'user') {
                header("Location: user_newsfeed.php"); // Redirect to user profile page
                exit();
            } elseif ($userType === 'admin') {
                header("Location:newsfeed.php"); // Redirect to admin profile page
                exit();
            }
        } else {
            // Password is incorrect
            echo "<script>alert('Invalid mobile number or password');</script>";
        }
    } else {
        // User with given mobile number not found
        echo "<script>alert('User with mobile number $mobile_number not found');</script>";
    }
} 

// Close the connection
$conn->close();
?>
