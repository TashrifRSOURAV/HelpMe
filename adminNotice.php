<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Sharing Platform</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-size: cover;
    background-position: center;
    background-attachment: fixed;
            background-image: url("nahi2.jpg");
        }
       
        .container {
            max-width: 1423px;
            margin: 50px auto;
            padding: 20px;
            /* background-color: #596fddad; */
            border-radius: 10px;
            
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .post-card {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            margin-bottom: 20px;
        }
        .card {
            flex: 0 0 300px;
            margin-right: 20px;
            border: 3px solid;
            border-radius: 10px;
            padding: 20px;
            background-color:#ededed91;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            transition: transform 0.3s;
    margin-top: 25px;
    margin-bottom: 25px;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        /* Style for the textarea */
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box;
            background-color: #e1e1e1bd;
            margin-bottom: 20px;
            resize: none; /* Prevent resizing */
            font-family: Arial, sans-serif; /* Match the body font */
        }
        /* Style for the filter dropdown */
        .filter-dropdown {
            margin-bottom: 20px;
        }
        .filter-dropdown label {
            font-weight: bold;
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




        .post-button {
    display: block;
    margin: 0 auto;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    background-color: #1877f2;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.post-button:hover {
    background-color: #1565c0;
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
    <h2 style="text-align: center ;color: white;">Post Notice</h2>
    <?php
    // Start session
    session_start();

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

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_notice'])) {
        // Process form data
        $notice_content = $_POST['notice_content'];
        $notice_location = $_POST['notice_location'];

        // Check if an image is uploaded
        if ($_FILES['notice_image']['size'] > 0) {
            $target_dir = "uploads/";
            $image_name = uniqid() . "_" . basename($_FILES["notice_image"]["name"]);
            $target_file = $target_dir . $image_name;
            if (move_uploaded_file($_FILES["notice_image"]["tmp_name"], $target_file)) {
                // Image uploaded successfully
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            $image_name = ""; // No image uploaded
        }

        // Insert the notice into the database
        $sql = "INSERT INTO notice (first_name, notices, images, location) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $_SESSION['user_id'], $notice_content, $image_name, $notice_location);
        if ($stmt->execute()) {
            echo "<p>Notice posted successfully.</p>";
        } else {
            echo "<p>Error posting notice: " . $conn->error . "</p>";
        }
        $stmt->close();
    }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="notice_content">Notice Content:</label>
        <textarea name="notice_content" id="notice_content" rows="6" required></textarea>
        <label for="notice_location">Location:</label>
        <select name="notice_location" id="notice_location">
            <option value="Badda">Badda</option>
            <option value="Natun Bazar">Natun Bazar</option>
            <option value="Mirpur">Mirpur</option>
            <!-- Add more options for other locations -->
        </select>
        <label for="notice_image">Image:</label>
        <input type="file" name="notice_image" id="notice_image">
        <!-- <input type="submit" name="submit_notice" value="Post"> -->
        <input type="submit" name="submit_notice" value="Post" class="post-button">

    </form>

    <!-- Filter by Location -->
    <!-- <div class="filter-dropdown">
        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="filter_location">Filter by Location:</label>
            <select name="filter_location" id="filter_location">
                <option value="All">All</option>
                <option value="Badda">Badda</option>
                <option value="Natun Bazar">Natun Bazar</option>
                <option value="Mirpur">Mirpur</option>
                Add more options for other locations -->
            <!-- </select>
            <input type="submit" value="Filter">
        </form>
    </div> --> 

    <hr>

    <h2 style="text-align: center ;color: white;">Previous Notices</h2>
    <div class="filter-dropdown">
        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="filter_location">Filter by Location:</label>
            <select name="filter_location" id="filter_location">
                <option value="All">All</option>
                <option value="Badda">Badda</option>
                <option value="Natun Bazar">Natun Bazar</option>
                <option value="Mirpur">Mirpur</option>
                <!-- Add more options for other locations -->
            </select>
            <input type="submit" value="Filter">
        </form>
    </div>
    <div class="post-card">
        <?php
        // Filter by Location
        $filter_location = isset($_GET['filter_location']) ? $_GET['filter_location'] : 'All';
        if ($filter_location != 'All') {
            $sql_filter = " WHERE location = '$filter_location'";
        } else {
            $sql_filter = "";
        }

        // Fetch previous notices from the database
        $sql = "SELECT * FROM notice" . $sql_filter;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card'>";
                echo "<p><strong>User:</strong> " . $row['first_name'] . "</p>";
                echo "<p><strong>Location:</strong> " . $row['location'] . "</p>";
                echo "<p><strong>Content:</strong> " . $row['notices'] . "</p>";
                if (!empty($row['images'])) {
                    echo "<img src='uploads/" . $row['images'] . "' alt='Notice Image'>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No notices found.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
