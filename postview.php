<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Post Management</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-image: url("nahi2.jpg");
        }
        .container {
            max-width: 1423px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .post-card {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .card {
            width: 300px;
            border: 3px solid;
            border-radius: 10px;
            padding: 20px;
            background-color: rgba(22, 22, 22, 0.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            margin: 20px;
            color: white;
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
        .card textarea {
            width: 100%;
            height: 80px;
            margin-bottom: 10px;
        }
        .filter-dropdown {
            margin-bottom: 20px;
        }
        .filter-dropdown label {
            font-weight: bold;
        }
        .menu-bar {
            overflow: hidden;
            background-color: #194d85a1;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            position: relative;
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
        .input-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            margin-bottom: 20px;
        }
        .input-group {
            flex: 1;
            text-align: center;
        }
        .input-group label {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            color: #000000;
        }
        .input-group select, 
        .input-group input[type='file'] {
            display: block;
            margin-bottom: 10px;
            margin-left: 30px;
        }
        .active-page {
            background-color: greenyellow;
            border-radius: 5px;
            color: black;
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
    <h2 style="color: white; text-align: center;">Your Posts</h2>

    <div class="post-card">
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root"; // your database username
        $password = ""; // your database password
        $database = "blood_donors"; // your database name
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Define the target directory
        $target_dir = "uploads/";

        // Handle form submission for update and delete
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['update'])) {
                // Update the post
                $mobile_number = $_POST['mobile_number'];
                $new_content = $_POST['content'];
                $old_content = $_POST['post_content'];
                $new_location = $_POST['location'];

                // Handle image upload
                $target_file = '';
                if (!empty($_FILES["post_image"]["name"])) {
                    $target_file = $target_dir . basename($_FILES["post_image"]["name"]);
                    if (move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file)) {
                        // File uploaded successfully
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }

                $sql = "UPDATE posts SET content='$new_content', location='$new_location'";
                if (!empty($target_file)) {
                    $sql .= ", post_image='$target_file'";
                }
                $sql .= " WHERE mobile_number='$mobile_number' AND content='$old_content'";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Post updated successfully.</p>";
                } else {
                    echo "Error updating post: " . $conn->error;
                }
            } elseif (isset($_POST['delete'])) {
                // Delete the post
                $mobile_number = $_POST['mobile_number'];
                $content = $_POST['post_content'];

                $sql = "DELETE FROM posts WHERE mobile_number='$mobile_number' AND content='$content'";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>Post deleted successfully.</p>";
                } else {
                    echo "Error deleting post: " . $conn->error;
                }
            }
        }

        // Retrieve posts associated with the logged-in user's mobile number
        if (isset($_SESSION['user_id'])) {
            $mobile_number = $_SESSION['user_id'];

            $sql = "SELECT * FROM posts WHERE mobile_number = '$mobile_number'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card'>";
                    echo "<form method='post' action='' enctype='multipart/form-data'>";
                    echo "<input type='hidden' name='mobile_number' value='" . $mobile_number . "'>";
                    echo "<input type='hidden' name='post_content' value='" . $row['content'] . "'>";
                    echo "<label for='content'>Content:</label>";
                    echo "<textarea name='content'>" . $row['content'] . "</textarea><br>";
                    echo "<label for='location'>Location:</label>";
                    echo "<select name='location'>";
                    echo "<option value='Badda' " . ($row['location'] == 'Badda' ? 'selected' : '') . ">Badda</option>";
                    echo "<option value='Natun Bazar' " . ($row['location'] == 'Natun Bazar' ? 'selected' : '') . ">Natun Bazar</option>";
                    echo "<option value='Mirpur' " . ($row['location'] == 'Mirpur' ? 'selected' : '') . ">Mirpur</option>";
                    echo "</select><br><br>";
                    echo "<label for='post_image'>Post Image:</label>";
                    echo "<img src='uploads/" . $row['post_image'] . "' alt='Post Image'><br>";
                    echo "<input type='file' name='post_image'><br><br>";
                    echo "<input type='submit' name='update' value='Update'> &nbsp;&nbsp;&nbsp;&nbsp;";
                    echo "<input type='submit' name='delete' value='Delete'>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No posts found.</p>";
            }
        } else {        echo "<p>User session not found.</p>";
        }

        // Close the database connection
        $conn->close();
        ?>
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

           
