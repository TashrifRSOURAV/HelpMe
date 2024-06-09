<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
            background-image: url("newblck2.jpg");
             background-size: cover;
    background-position: center;
    background-attachment: fixed;
        }
        .menu-bar {
            overflow: hidden;
            background-color: #3d3f3f; /* Facebook blue */
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
            background-color: #003993; /* Darker shade of blue */
            border-radius: 5px;
        }
        .profile-img {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
        }
        .profile-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: white; /* Facebook blue */
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: white;
            font-weight: bold;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 8px 15px;
            border: none;
            background-color: #1565c0; /* Facebook blue */
            color: #ffffff;
            cursor: pointer;
            border-radius: 5px;
        }
        /* Profile picture styles */
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            overflow: hidden;
            background-color: #ddd; /* Light grey */
        }
        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

    
    

    <!-- <a href="profile.php" class="profile-img">
        <?php 
            session_start();
            if (isset($_SESSION['user_id'])) {
                $mobile_number = $_SESSION['user_id'];
                $servername = "localhost";
                $username = "root";
                $password = ""; 
                $database = "blood_donors"; 
                $conn = new mysqli($servername, $username, $password, $database);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT profilepicture FROM donors WHERE mobile_number = '$mobile_number'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (!empty($row['profilepicture'])) {
                        echo '<img src="'.$row['profilepicture'].'" alt="Profile Picture">';
                    } else {
                        echo '<img src="default_profile_picture.jpg" alt="Default Profile Picture">';
                    }
                } else {
                    echo '<img src="default_profile_picture.jpg" alt="Default Profile Picture">';
                }
                $conn->close();
            } else {
                echo '<img src="default_profile_picture.jpg" alt="Default Profile Picture">';
            }
        ?>
    </a> -->
</div>
<div class="container">
    <?php
  
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_SESSION['user_id'])) {
           
            $servername = "localhost";
            $username = "root"; 
            $password = ""; 
            $database = "blood_donors"; 
            $conn = new mysqli($servername, $username, $password, $database);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

           
            $mobile_number = $_SESSION['user_id'];

           
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $gender = $_POST['gender'];
            $blood_group = $_POST['blood_group'];
            $email = $_POST['email'];
            $last_donation_date = $_POST['last_donation_date'];
            $location = $_POST['location'];

            
            $profile_picture = '';
            if ($_FILES['profile_picture']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['profile_picture']['tmp_name'])) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $new_file_name = uniqid() . '.' . $imageFileType;
                $target_file = $target_dir . $new_file_name;
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                    $profile_picture = $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

           
            $sql = "UPDATE donors SET first_name='$first_name', last_name='$last_name', gender='$gender', blood_group='$blood_group', email='$email', last_donation_date='$last_donation_date', location='$location'";
            if (!empty($profile_picture)) {
                $sql .= ", profilepicture='$profile_picture'";
            }
            $sql .= " WHERE mobile_number='$mobile_number'";

            if ($conn->query($sql) === TRUE) {
                echo "<p>User information updated successfully.</p>";
            } else {
                echo "Error updating user information: " . $conn->error;
            }

            $conn->close();
        }
    }
    ?>
    <h2>User Profile </h2>
    <?php
   
    if (isset($_SESSION['user_id'])) {
        
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $database = "blood_donors"; 
        $conn = new mysqli($servername, $username, $password, $database);
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
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $blood_group = $row['blood_group'];
            $email = $row['email'];
            $last_donation_date = $row['last_donation_date'];
            $location = $row['location'];
            ?>
            <!-- Profile picture -->
            <div class="profile-picture">
                <?php if(!empty($row['profilepicture'])): ?>
                    <img src="<?php echo $row['profilepicture']; ?>" alt="Profile Picture">
                <?php else: ?>
                    <img src="default_profile_picture.jpg" alt="Default Profile Picture">
                <?php endif; ?>
            </div>
            <!-- End of Profile picture -->

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <label for="profile_picture">Update Profile Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture" accept="image/*"><br>

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

        $conn->close();
    } else {
        echo "<p>User session not found.</p>";
    }
    ?>
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
