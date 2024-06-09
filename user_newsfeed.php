<?php
session_start();

// Initialize $post_success variable
$post_success = false;

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
$sql = "SELECT * FROM donors WHERE mobile_number = '$mobile_number'";
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

// Handle form submission for posting
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_post'])) {
    // Retrieve post data from the form
    $post_content = $_POST['post_content'];
    $post_location = $_POST['post_location'];
    $post_image = ''; // Default empty image value
    
    // Check if an image file is selected
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
        // Check file type
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = pathinfo($_FILES['post_image']['name'], PATHINFO_EXTENSION);
        if (in_array($file_extension, $allowed_types)) {
            // Move the uploaded file to a temporary location
            $temp_file = $_FILES['post_image']['tmp_name'];
            // Generate a unique name for the image file
            $post_image = uniqid() . '.' . $file_extension;
            // Move the uploaded file to the uploads directory
            move_uploaded_file($temp_file, 'uploads/' . $post_image);
        }
    }
    
    // Prepare SQL statement to insert the new post into the database
    $insert_sql = "INSERT INTO posts (user_id, mobile_number,location, content, post_image) VALUES (?, ?, ?, ?,?)";
    $stmt = $conn->prepare($insert_sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("sssss", $first_name,$mobile_number, $post_location, $post_content, $post_image);
    if ($stmt->execute()) {
        $post_success = true;
    } else {
        echo "<p>Error adding post: " . $conn->error . "</p>";
    }

    $stmt->close();
}

// Handle filter submission
$posts = []; // Initialize an empty array to store posts
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['filter'])) {
    $filter_location = $_GET['filter_location'];

    // Prepare SQL statement to fetch posts filtered by location
    if ($filter_location === "") {
        $sql = "SELECT * FROM posts ORDER BY timestamp_column DESC"; // Change timestamp_column to the actual timestamp column name
    } else {
        $sql = "SELECT * FROM posts WHERE location = ? ORDER BY timestamp_column DESC"; // Change timestamp_column to the actual timestamp column name
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F8F0E3;
            background-size: cover;
    background-position: center;
    background-attachment: fixed;
    /* background-image: url("newblck2.jpg"); */
    background-image: url("nahi2.jpg");

    /* background-image: url("BCKKK.jpg"); */

/* 
    background-image: url("tvintagehand.jpg"); */


    /* background-image: linear-gradient(135deg, #FAB2FF 10%, #6f61fb 100%); */
    /* background-image: linear-gradient(135deg, #FAB2FF 10%, #1904E5 100%); */

    /* background-image: url("bgcolors.jpeg"); */
        }

        .menu-bar {
            background-color: #194d85a1; /* Facebook blue */
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
            background-color: #0a94dc; /* Darker shade of blue */
            border-radius: 5px;
        }

        .container {
            display: flex;
            justify-content: space-between;
            max-width: 1420px;
            margin: 20px auto;
            padding: 20px;
        }

        /* .post-form {
            flex: 1;
            margin-right: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 40px;
            margin-bottom: 40px;
            background-color: rgba(255, 255, 255, 0.5);
        } */


        .post-form {
    flex: 1;
    margin-top: 17px;
    margin-right: 20px;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    margin-bottom: 40px;
    border: 2px solid rgba(0, 0, 0, 0.7);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.post-form:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}


        .post-form label {
            display: block;
            margin-bottom: 10px;
            color:#fdfdfd;
            font-weight: bold;
            margin-top: 8px;
        }

        .post-form textarea, 
        .post-form select, 
        .post-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;

            border-radius: 40px;
            border: 1px solid #ccc;
            background-color: white;
            box-sizing: border-box;
            margin-top: 15px;
        }

        .post-form input[type="submit"] {
            background-color:rgb(0 16 31);
            color: white;
            cursor: pointer;
            border: none;
            margin-top: 92px;
        }

        .post-form input[type="submit"]:hover {
            background-color: #1565c0;
        }

        .post-feed-container {
            flex: 2;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
            position: relative; /* Position relative for child elements */
        }

        .post-feed {
            padding-right: 20px; /* Adjust for scrollbar width */
        }

        .post-feed .filter-form {
            position: sticky;
            top: 20px; /* Adjust as needed */
            background-color:rgba(22, 22, 22, 5);
            padding: 20px;
            border-radius: 40px;
            border: 2px solid rgba(0, 0, 0, 0.7);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 60); */
          
        }

        .post-container {
    background-color: #ffffff;
    background-image: url("");
    margin-top: 50px;
    padding: 20px;
    border-radius: 40px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-left: -100%; /* Start off-screen */
    animation: slideInLeft 0.5s ease forwards;
    animation-fill-mode: both; /* Ensure final styles persist */

    background-color: rgba(33, 33, 34, 0.5);


    border: 2px solid rgba(0, 0, 0, 0.7);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

@keyframes slideInLeft {
    to {
        margin-left: 0; /* Slide in to left */
    }
}





        .post-container p {
            margin: 10px 0;
            color: white;
        }

        .post-container img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }


        .active-page {
            background-color: greenyellow;
            border-radius: 5px;
            color:black;
        }

    </style>
    <!-- Your HTML head content here -->
</head>
<body>

<!-- Your HTML body content here -->




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
    <div class="post-form">
        <!-- <h2>Post Something</h2> -->

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="post_content">Post Content:</label>
            <textarea name="post_content" id="post_content" rows="6" required></textarea>
            
            <label for="post_location">Location:</label>
            <select name="post_location" id="location">
                <option value="Badda">Badda</option>
                <option value="Natun Bazar">Natun Bazar</option>
                <option value="Mirpur">Mirpur</option>
                <!-- Add more options for other locations -->
            </select>
            
            <label for="post_image">Upload Image (Optional):</label>
            <input type="file" name="post_image" id="post_image">
            
            <input type="submit" name="submit_post" value="Post">
            <?php if ($post_success) { ?>
                <p>Post added successfully.</p>
            <?php } ?>
        </form>
    </div>

    <div class="post-feed-container">
        <div class="post-feed">
          <h2 style="color: white;">Posts</h2>
            <!-- Filter by Location -->
            <div class="filter-form">
                <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="filter_location" style="color: white;">Filter by Location:</label>

                    <select name="filter_location" id="filter_location">
                        <option value="">All Locations</option>
                        <option value="Badda">Badda</option>
                        <option value="Natun Bazar">Natun Bazar</option>
                        <option value="Mirpur">Mirpur</option>
                        <!-- Add more options for other locations -->
                    </select>
                    <input type="submit" name="filter" value="Filter">
                </form>
            </div>

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

                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</div>

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

<?php
// Check if $conn is defined and not null before closing the connection
if (isset($conn) && !is_null($conn)) {
    // Close the database connection
    $conn->close();
}
?>
