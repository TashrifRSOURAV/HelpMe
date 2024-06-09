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

// Handle filter submission
$filter_location = "";
$order_by = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_filter'])) {
    $filter_location = $_POST['filter_location'];
    $order_by = $_POST['order_by'];
}

// Prepare SQL statement to fetch top contributors based on filter and order
$sql = "SELECT * FROM donors";

if (!empty($filter_location)) {
    $sql .= " WHERE location = '$filter_location'";
}

if (!empty($order_by)) {
    $sql .= " ORDER BY reward $order_by";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Contributors</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            background-image: url("nahi2.jpg");

            font-family: Arial, sans-serif;
            background-size: cover;
    background-position: center;
    background-attachment: fixed;


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

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #088b8b;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            margin-right: 10px;
            color:#e2e0e0
        }

        select, input[type="submit"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f0f2f5;
            cursor: pointer;
        }

        .contributor-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #c7c7c7fc;
    color: black;

        }

        .contributor-table th, .contributor-table td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        .contributor-table th {
            background-color: #f2f2f2;
        }

        .contributor-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .contributor-table tbody tr:hover {
            background-color: #e9e9e9;
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
    <h1>Top Contributors</h1>

    <!-- Filter Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="filter_location">Location:</label>
        <select name="filter_location" id="filter_location">
            <option value="">All Locations</option>
            <option value="Badda"<?php if ($filter_location == "Badda") echo " selected"; ?>>Badda</option>
            <option value="Natun Bazar"<?php if ($filter_location == "Natun Bazar") echo " selected"; ?>>Natun Bazar</option>
            <option value="Mirpur"<?php if ($filter_location == "Mirpur") echo " selected"; ?>>Mirpur</option>
            <!-- Add more options for other locations -->
        </select>

        <label for="order_by">Order By:</label>
        <select name="order_by" id="order_by">
            <option value="ASC"<?php if ($order_by == "ASC") echo " selected"; ?>>Ascending</option>
            <option value="DESC"<?php if ($order_by == "DESC") echo " selected"; ?>>Descending</option>
        </select>

        <input type="submit" name="submit_filter" value="Filter">
    </form>

    <!-- Contributor Table -->
    <table class="contributor-table">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Location</th>
                <th>Reward</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['first_name'] . "</td>";
                    echo "<td>" . $row['last_name'] . "</td>";
                    echo "<td>" . $row['gender'] . "</td>";
                    echo "<td>" . $row['location'] . "</td>";
                    echo "<td>" . $row['reward'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No contributors found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
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
// Close the database connection
$conn->close();
?>
