<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
            background-image: url('bg3.jpg')
        }
        .menu-bar {
            background-color: #1877f2; /* Facebook blue */
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
            background-color: #1565c0; /* Darker shade of blue */
            border-radius: 5px;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #acb4ff33;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.85);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ffffff; /* Facebook blue */
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
            font-weight: bold;
            font-family: 'Times New Roman', Times, serif;
        }
        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 15px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 8px 15px;
            border: none;
            background-color: #003993;
            color: white;
            cursor: pointer;
            border-radius: 20px;
            margin: 0 auto; /* Add this line */
    display: block;
        }

        
        .menu-bar {
            background-color: #1877f2; /* Facebook blue */
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
            background-color: #1565c0; /* Darker shade of blue */
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

    // Retrieve user ID (mobile number) from session
    $mobile_number = $_SESSION['user_id'];

    // Prepare SQL statement to fetch user information based on mobile number
    $sql = "SELECT * FROM admin WHERE mobile_number = '$mobile_number'";
    $result = $conn->query($sql);

    // Check if user exists
    if ($result->num_rows > 0) {
        // Fetch user information
        $row = $result->fetch_assoc();
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $gender = $row['gender'];
        $blood_group = $row['blood_group'];
        $email = $row['email'];
      
        $location = $row['location'];

        // Handle form submission for updating user information
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $gender = $_POST['gender'];
            $blood_group = $_POST['blood_group'];
            $email = $_POST['email'];
            $last_donation_date = $_POST['last_donation_date'];
            $location = $_POST['location'];

            // Prepare SQL statement to update user information
            $update_sql = "UPDATE donors SET first_name='$first_name', last_name='$last_name', gender='$gender', blood_group='$blood_group', email='$email', last_donation_date='$last_donation_date', location='$location' WHERE mobile_number='$mobile_number'";

            if ($conn->query($update_sql) === TRUE) {
                echo "<script>alert('Record updated successfully');</script>";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }

        // Display user information and edit form
        ?>
        <h2>User Profile</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" required><br>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" required><br>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="Male" <?php if ($gender === 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($gender === 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if ($gender === 'Other') echo 'selected'; ?>>Other</option>
            </select><br>

            <label for="blood_group">Blood Group:</label>
            <select name="blood_group" id="blood_group" required>
                <option value="A+" <?php if ($blood_group === 'A+') echo 'selected'; ?>>A+</option>
                <option value="A-" <?php if ($blood_group === 'A-') echo 'selected'; ?>>A-</option>
                <option value="B+" <?php if ($blood_group === 'B+') echo 'selected'; ?>>B+</option>
                <option value="B-" <?php if ($blood_group === 'B-') echo 'selected'; ?>>B-</option>
                <option value="AB+" <?php if ($blood_group === 'AB+') echo 'selected'; ?>>AB+</option>
                <option value="AB-" <?php if ($blood_group === 'AB-') echo 'selected'; ?>>AB-</option>
                <option value="O+" <?php if ($blood_group === 'O+') echo 'selected'; ?>>O+</option>
                <option value="O-" <?php if ($blood_group === 'O-') echo 'selected'; ?>>O-</option>
            </select><br>

            <label for="email">Email:</label>
            <input type="text" name="email" id="email" value="<?php echo $email; ?>" required><br>

            <label for="last_donation_date">Last Donation Date:</label>
            <input type="date" name="last_donation_date" id="last_donation_date" value="<?php echo $last_donation_date; ?>" required><br>

            <label for="location">Location:</label>
            <input type="text" name="location" id="location" value="<?php echo $location; ?>" required><br>

            <input type="submit" value="Save Changes">
        </form>
        <?php
    } else {
        echo "<p>User not found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>
</div>
</body>
</html>
