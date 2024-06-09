<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$database = "blood_donors"; // your database name
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch complaints from the database
$sql = "SELECT * FROM complain";
$result = $conn->query($sql);

// Initialize an array to store complaints
$complaints = [];

if ($result->num_rows > 0) {
    // Fetch all rows of the result as an associative array
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
} else {
    echo "<p>No complaints found.</p>";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-image: url("nahi2.jpg");
            margin: 0; /* Reset margin */
            padding: 0; /* Reset padding */
        }

        .menu-bar {
            background-color: #194d85a1;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
        }

        .menu-bar a {
            display: inline-block;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
        }

        .menu-bar a:hover {
            background-color: #0a94dc;
            border-radius: 5px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: white;
        }

        .complaint-container {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .complaint-container p {
            margin: 10px 0;
        }

        .complaint-container img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="menu-bar">
    <a href="newsfeed.php">Home</a>
    <a href="adminProfile.php">Profile</a>
    <a href="adminNotice.php">Notice</a>
    <a href="adminSearch.php">Blood</a>
    <a href="adminHelpline.php">Helpline</a>
    <a href="admincontributer.php">Contributor</a>
    <a href="getcomplain.php">Complaint Received</a>
    <a href="login.html">Logout</a>
</div>

<div class="container">
    <h1>Complaints</h1>

    <?php foreach ($complaints as $complaint): ?>
        <div class="complaint-container">
            <p><strong>Name:</strong> <?php echo $complaint['first_name']; ?></p>
            <p><strong>Mobile Number:</strong> <?php echo $complaint['mobile_number']; ?></p>
            <p><strong>Complaint:</strong> <?php echo $complaint['complaints']; ?></p>
            <?php if (!empty($complaint['cimage'])): ?>
                <img src="uploads/<?php echo $complaint['cimage']; ?>" alt="Complaint Image">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
