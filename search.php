<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Blood Donors</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-image: url("BCKKK.jpg");
    /* background-image: url("newblck2.jpg"); */
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color:rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 120PX;
        }
        h2 {
            text-align: center;
            margin-top: 0;
            color: #cc0000;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #666;
            font-weight: bold;
        }
        select, input[type="submit"] {
            width: calc(100% - 42px);
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f2f2f2;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #cc0000;
            color: #fff;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #990000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #cc0000;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e6e6e6;
        }
        p {
            text-align: center;
            color: #cc0000;
            font-size: large;
            margin-bottom: 20px;
        }
        .menu-bar {
            background-color: #3d3f3f;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        .menu-bar a {
            display: inline-block;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
        }
        .menu-bar a:hover {
            background-color: #1565c0;
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
        <a href="search.php"><i class="fas fa-search"></i> Blood</a>
        <a href="helpline.php"><i class="fas fa-phone-alt"></i> Helpline</a>
        <a href="complains.php"><i class="fas fa-exclamation-circle"></i> Complaint</a>
        <a href="contributer.php"><i class="fas fa-hands-helping"></i> Contributor</a>
        <a href="chatting.php"><i class="fas fa-comments"></i> Chat</a>
        <a href="save_ride.php">Ride</a>
        <a href="login.html"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>


<div class="container">
    <h2>Search Blood Donors</h2>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="location">Location:</label>
        <select name="location" id="location">
            <option value="Badda">Badda</option>
            <option value="Natun Bazar">Natun Bazar</option>
            <option value="Mirpur">Mirpur</option>
        </select>
        <label for="blood_group">Blood Group:</label>
        <select name="blood_group" id="blood_group">
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select>
        <input type="submit" name="search" value="Search">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $location = $_POST['location'];
        $blood_group = $_POST['blood_group'];

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

        // Prepare SQL statement to fetch blood donors based on location and blood group
        $sql = "SELECT * FROM donors WHERE location = '$location' AND blood_group = '$blood_group'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                <tr>
                    <th>Name</th>
                    <th>Mobile Number</th>
                    <th>Blood Group</th>
                    <th>Last Donation Date</th>
                </tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["first_name"] . " " . $row["last_name"] . "</td>
                    <td>" . $row["mobile_number"] . "</td>
                    <td>" . $row["blood_group"] . "</td>
                    <td>" . $row["last_donation_date"] . "</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No donors found with the specified location and blood group.</p>";
        }

        // Close the connection
        $conn->close();
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
