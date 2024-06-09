<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-image: url("nahi2.jpg");
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color:rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 155px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: white;
        }
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }
        input[type="file"] {
            margin-top: 5px;
            color: white;
        }
        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #088b8b;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #1565c0;
        }
        .menu-bar {
            background-color:#194d85a1;
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

        .active-page {
            background-color: greenyellow;
            border-radius: 5px;
            color:black;
        }
    </style>
</head>
<body>
<div class="menu-bar">


        <a href="user_newsfeed.php"><i class="fas fa-home"></i> Home</a>
        <a href="usernotice.php"><i class="fas fa-bell"></i> Notice</a>
        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
        <a href="postview.php"><i class="fas fa-pencil-alt"></i> Post</a>
        <a href="search.php"><i class="fas fa-tint"></i> Blood</a>
        <a href="helpline.php"><i class="fas fa-phone-alt"></i> Helpline</a>
        <a href="complains.php"><i class="fas fa-exclamation-circle"></i> Complaint</a>
        <a href="contributer.php"><i class="fas fa-hands-helping"></i> Contributor</a>
        <a href="chatting.php"><i class="fas fa-comments"></i> Chat</a>
        <a href="save_ride.php"><i class="fas fa-car"></i> Ride</a>
        <a href="login.html"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="container">
    
<h2 style="color: white;">Share Your Complaint</h2>

    <form method="post" enctype="multipart/form-data">
        <label for="complaint_text">Complaint Text:</label>
        <textarea id="complaint_text" name="complaint_text" rows="4" required></textarea>

        <label for="complaint_image">Upload Image (optional):</label>
        <input type="file" id="complaint_image" name="complaint_image">

        <input type="submit" value="Submit Complaint">
    </form>
</div>

<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
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

// Retrieve user information to use first_name as user_id
$mobile_number = $_SESSION['user_id'];
$sql_user = "SELECT first_name FROM donors WHERE mobile_number = '$mobile_number'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $user_id = $row_user['first_name']; // Using first_name as user_id
} else {
    echo "Error: User not found.";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_text = $_POST['complaint_text'];
    $complaint_image = isset($_FILES['complaint_image']['name']) ? $_FILES['complaint_image']['name'] : NULL;

    // Check if the image was uploaded
    if (!empty($complaint_image)) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["complaint_image"]["name"]);

        // Check if the target directory exists, create it if not
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Check if the file was successfully uploaded
        if (move_uploaded_file($_FILES["complaint_image"]["tmp_name"], $target_file)) {
            // Image uploaded successfully
        } else {
            echo "Sorry, there was an error uploading your file.";
            $complaint_image = NULL; // Set image to NULL to handle database insertion
        }
    }

    // Prepare and execute SQL statement to insert complaint into database
    $sql = "INSERT INTO complain (first_name, mobile_number, complaints, cimage) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        if ($complaint_image !== NULL) {
            // If image uploaded, bind four parameters
            $stmt->bind_param("ssss", $user_id, $mobile_number, $complaint_text, $complaint_image);
        } else {
            // If no image uploaded, bind three parameters
            $stmt->bind_param("sss", $user_id, $mobile_number, $complaint_text);
        }
        if ($stmt->execute()) {
            echo "<p>Complaint submitted successfully.</p>";
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
    }
}

// Close the database connection
$conn->close();
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var menuItems = document.querySelectorAll('.menu-bar a');
        var currentPath = window.location.pathname.split('/').pop();

        menuItems.forEach(function(item) {
            if (item.getAttribute('href') === currentPath) {
                item.classList.add('active-page');
            }
        });
    });
</script>
</body>
</html>
