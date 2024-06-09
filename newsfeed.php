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
} else {
    echo "<p>User not found.</p>";
    exit(); // Exit if user not found
}

// Handle reward submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_reward'])) {
    $rewarded_user = $_POST['rewarded_user'];
    $reward_value = $_POST['reward_value'];

    // Check if the user is trying to reward themselves
    if ($rewarded_user == $first_name) {
        echo "<p>You cannot reward yourself.</p>";
    } else {
        // Check if the user exists in the donors table
        $check_user_sql = "SELECT * FROM donors WHERE first_name = ?";
        $stmt = $conn->prepare($check_user_sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind parameter and execute the statement
        $stmt->bind_param("s", $rewarded_user);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // User exists, update the reward
                $update_reward_sql = "UPDATE donors SET reward = reward + ? WHERE first_name = ?";
                $stmt = $conn->prepare($update_reward_sql);

                if ($stmt === false) {
                    die("Error preparing statement: " . $conn->error);
                }

                // Bind parameters and execute the statement
                $stmt->bind_param("is", $reward_value, $rewarded_user);
                if ($stmt->execute()) {
                    echo "<p>Reward added successfully.</p>";
                } else {
                    echo "<p>Error adding reward: " . $conn->error . "</p>";
                }

                $stmt->close();
            } else {
                // User does not exist in the donors table, show error
                echo "<p>Error: User does not exist in the donors table.</p>";
            }
        } else {
            echo "<p>Error: Unable to fetch user information.</p>";
        }
    }
}

// Handle filter submission
$posts = []; // Initialize an empty array to store posts
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['filter'])) {
    $filter_location = $_GET['filter_location'];

    // Prepare SQL statement to fetch posts filtered by location
    if ($filter_location === "") {
        $sql = "SELECT * FROM posts";
    } else {
        $sql = "SELECT * FROM posts WHERE location = ?";
    }

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameter and execute the statement
    if ($filter_location !== "") {
        $stmt->bind_param("s", $filter_location);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $posts[] = $row; // Store posts in the array
            }
        } else {
            echo "<p>No posts found for this location.</p>";
        }
    } else {
        echo "<p>Error: Unable to fetch posts.</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Sharing Platform</title>

    <style>
        body {
            font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f2f2f2;
    /* background-image: linear-gradient(135deg, #a9b7e7 10%, #45697c 100%); */

    background-image: url("nahi2.jpg");
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
        }
        .post-container img {
    max-width: 100%;
    border-radius: 10px;
    margin-bottom: 10px;
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
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #fefdfd;
        }

        form {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: white;
            font-weight: bold;
        }

        select, input[type="submit"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #fff8f8a1;
            box-sizing: border-box;

        }

        input[type="submit"] {
            background-color: #1877f2;
            color: white;
            cursor: pointer;
            border: none;
        }

        input[type="submit"]:hover {
            background-color: #1565c0;
        }

        .post-container {
          
            background-color: #bd00c32b;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.58);
    padding: 20px;
    margin-bottom: 20px;
        }

        .post-container p {
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="menu-bar">
<a href="newsfeed.php">Home</a>
    <a href="adminProfile.php">Profile</a>
    <a href="adminNotice.php">Notice</a>
    <a href="postview.php">Post</a>
    <a href="adminSearch.php">Blood</a>
    <a href="adminHelpline.php">Helpline</a>
    <a href="admincontributer.php">Contributor</a>
    <a href="getcomplain.php">Complais Received</a>
    <a href="login.html">Logout</a>
</div>

<div class="container">
    <h1>Welcome to Area Alert</h1>

    <!-- Post Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="post_content">Post Content:</label>
        <textarea name="post_content" id="post_content" rows="6" required></textarea>
        
        <label for="post_location">Location:</label>
        <select name="post_location" id="location">
            <option value="Badda">Badda</option>
            <option value="Natun Bazar">Natun Bazar</option>
            <option value="Mirpur">Mirpur</option>
            <!-- Add more options for other locations -->
        </select>
        
        <input type="submit" name="submit_post" value="Post">
    </form>

    <hr>

    <!-- Filter by Location -->
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="filter_location">Filter by Location:</label>
        <select name="filter_location" id="filter_location">
            <option value="">All Locations</option>
            <option value="Badda">Badda</option>
            <option value="Natun Bazar">Natun Bazar</option>
            <option value="Mirpur">Mirpur</option>
            <!-- Add more options for other locations -->
        </select>
        <input type="submit" name="filter" value="Filter">
    </form>

    <hr>

    <!-- Display Posts -->
    <?php
   if (!empty($posts)) {
    foreach ($posts as $post) {
        echo "<div class='post-container'>";
        echo "<p><strong>User:</strong> " . $post["user_id"] . "</p>";
        echo "<p><strong>Location:</strong> " . $post["location"] . "</p>";
        echo "<p><strong>Content:</strong> " . $post["content"] . "</p>";
        if (!empty($post["post_image"])) {
            echo "<img src='uploads/" . $post["post_image"] . "' alt='Post Image'>";
        }

        // Show total reward for the user
        $user_reward = 0;
        $user_id = $post["user_id"];
        $reward_sql = "SELECT SUM(reward) AS total_reward FROM donors WHERE first_name = '$user_id'";
        $reward_result = $conn->query($reward_sql);
        if ($reward_result->num_rows > 0) {
            $reward_row = $reward_result->fetch_assoc();
            $user_reward = $reward_row['total_reward'];
        }
        echo "<p><strong>Total Reward Points:</strong> " . $user_reward . "</p>";

            // Reward form
            echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
            echo "<label for='reward_value'>Reward:</label>";
            echo "<select name='reward_value' id='reward_value'>";
            echo "<option value='1'>1</option>";
            echo "<option value='2'>2</option>";
            echo "<option value='3'>3</option>";
            echo "<option value='4'>4</option>";
            echo "<option value='5'>5</option>";
            echo "</select>";
            echo "<input type='hidden' name='rewarded_user' value='" . $post["user_id"] . "'>";
            echo "<input type='submit' name='submit_reward' value='Reward'>";
            echo "</form>";

            echo "</div>";
        }
    } else {
        echo "<p>No posts found.</p>";
    }
    ?>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
