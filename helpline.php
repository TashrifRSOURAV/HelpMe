<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Services</title>

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
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-top: 20px;
            color: #088b8b
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: white;
            font-weight: bold;
        }
        select, input[type="submit"] {
            width: calc(100% - 42px);
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: white;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color:#088b8b;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #1565c0; /* Darker shade of blue */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        p{
            text-align: center;
            color: #cc0000; /* Red */
            font-size: large;
            margin-bottom: 20px;
        }

        .menu-bar {
        
            background-color:#194d85a1;
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
    <h2>Search Services</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="Area">Location:</label>
        <select name="Area" id="Area">
            <option value="Badda">Badda</option>
            <option value="Natun Bazar">Natun Bazar</option>
            <option value="Mirpur">Mirpur</option>
        </select>
        <label for="Service">Service:</label>
        <select name="Service" id="Service">
            <option value="Police">Police</option>
            <option value="Fire Brigade">Fire Brigade</option>
            <option value="Ambulance">Ambulance</option>
        </select>
        <input type="submit" name="search" value="Search">
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $Service = $_POST['Service'];
        $Area = $_POST['Area'];

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

        // Prepare SQL statement to fetch helpline based on location and service
        $sql = "SELECT * FROM helpline WHERE Area = '$Area' AND Service = '$Service'";
        $result = $conn->query($sql);

        // Check if the query was successful and there are rows in the result set
        if ($result !== false && $result->num_rows > 0) {
            echo "<table>
                <tr>
                    <th>Service</th>
                    <th>Mobile Number</th>
                    <th>Email</th>
                </tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . $row["Service"] . "</td>
                    <td>" . $row["phone"] . "</td>
                    <td>" . $row["email"] . "</td>
                </tr>";
            }
            echo "</table>";
        } else {
            // Display a message indicating no helpline was found
            echo "<p>No helpline found with the specified location and service.</p>";
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
