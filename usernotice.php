<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Sharing Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-size: cover;
    background-position: center;
    background-attachment: fixed;
            /* background-color: #f2f2f2; */
            background-image:url("nahi2.jpg")
        }
   
    
        .container {
            max-width: 1423px;
            margin: 50px auto;
            padding: 20px;
            /* background-color: #a57fe3; */
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
         
            border: 3px solid;
            border-radius: 10px;
            padding: 20px;
            background-color:rgba(22, 22, 22, 0.2);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            color: white;
            
    margin-top: 25px;
    margin-left: 20px;
    margin-right: 20px;
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
<h1 style="text-align: center; color: white;">Notice</h1>




    <div class="filter-dropdown">
        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="filter_location" style="color: white;">Filter by Location:</label>

            <select name="filter_location" id="filter_location">
                <option value="All">All</option>
                <option value="Badda">Badda</option>
                <option value="Natun Bazar">Natun Bazar</option>
                <option value="Mirpur">Mirpur</option>
               
            </select>
            <input type="submit" value="Filter">
        </form>
    </div>

    <div class="post-card">
        <?php
      
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $database = "blood_donors"; 
        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

       
        $filter_location = isset($_GET['filter_location']) ? $_GET['filter_location'] : 'All';
        if ($filter_location != 'All') {
            $sql_filter = " WHERE location = '$filter_location'";
        } else {
            $sql_filter = "";
        }

       
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
