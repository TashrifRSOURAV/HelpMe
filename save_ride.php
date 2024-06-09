<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Ride Share and Search</title>
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
            display: flex;
            justify-content: space-between;
        }
        .box {
            width: 46%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: rgba(22, 22, 22, 0.2);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: white;
            margin: 10px 0;
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 90%; /* Set the width to 100% for consistency */
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 93%;
        }

        button {
            background-color: #088b8b;
            color: white;
            border: 2px;
            cursor: pointer;
            margin: 20px auto; /* Center horizontally with top margin */
            display: block; /* Ensure button is displayed as a block element */
            width: 80px;
            height: 40px;
            border-radius: 10px;
        }
        button:hover {
            background-color:rgb(0 0 0 / 46%);
        }
        table {
            margin: 0 auto; /* Center the table */
            width: 1350px; /* Set the width */
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(22, 22, 22, 0.2);
            color: white;
            background: #000;
        }

        table, th, td {
            border: 1px solid #ccc;
            text-align: center; /* Center the text within table cells */
            margin-bottom: 50px;
        }

        th, td {
            padding: 10px;
        }

        .menu-bar {
            overflow: hidden;
            background-color:#194d85a1;
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
        <div class="box">
            <h2>Ride Share</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <label for="service">Service:</label>
                <select name="service" id="service" required>
                    <option value="Uber">Uber</option>
                    <option value="Pathau">Pathau</option>
                </select>
                <label for="amount">Amount:</label>
                <input type="number" name="amount" id="amount" required>

                <label for="ride_from">From:</label>
                <select name="ride_from" id="ride_from" required>
                    <option value="Mirpur">Mirpur</option>
                    <option value="Natunbazar">Natunbazar</option>
                    <option value="Badda">Badda</option>
                </select>

                <label for="ride_to">To:</label>
                <select name="ride_to" id="ride_to" required>
                    <option value="Mirpur">Mirpur</option>
                    <option value="Natunbazar">Natunbazar</option>
                    <option value="Badda">Badda</option>
                </select>

                <label for="vehicle_number">Vehicle Number:</label>
                <input type="text" name="vehicle_number" id="vehicle_number" required>

                <button type="submit">Submit</button>
            </form>
        </div>

        <div class="box">
            <h2>Search Rides</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                <label for="search_service">Service:</label>
                <select name="search_service" id="search_service">
                    <option value="">Select</option>
                    <option value="Uber">Uber</option>
                    <option value="Pathau">Pathau</option>
                </select>

                <label for="search_ride_from">From:</label>
                <select name="search_ride_from" id="search_ride_from">
                    <option value="">Select</option>
                    <option value="Mirpur">Mirpur</option>
                    <option value="Natunbazar">Natunbazar</option>
                    <option value="Badda">Badda</option>
                </select>

                <label for="search_ride_to">To:</label>
                <select name="search_ride_to" id="search_ride_to">
                    <option value="">Select</option>
                    <option value="Mirpur">Mirpur</option>
                    <option value="Natunbazar">Natunbazar</option>
                    <option value="Badda">Badda</option>
                </select>

                <button type="submit">Search</button>
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
        $mobile_number = $_SESSION['user_id'];
        $servername = "localhost";
        $username = "root"; 
        $password = ""; 
        $database = "blood_donors"; 

        $conn = new mysqli($servername, $username, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $service = $_POST['service'];
        $amount = $_POST['amount'];
        $ride_from = $_POST['ride_from'];
        $ride_to = $_POST['ride_to'];
        $vehicle_number = $_POST['vehicle_number'];

        $stmt = $conn->prepare("INSERT INTO rides (service, amount, ride_from, ride_to, vehicle_number, mobile_number) VALUES (?, ?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("sissss", $service, $amount, $ride_from, $ride_to, $vehicle_number, $mobile_number);

        $result = $stmt->execute();

        if ($result === false) {
            die("Error executing statement: " . $stmt->error);
        }

        echo "New records created successfully";

        $stmt->close();
        $conn->close();
    }
    ?>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "blood_donors";
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $search_service = '';
    $search_ride_from = '';
    $search_ride_to = '';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && (!empty($_GET['search_service']) || !empty($_GET['search_ride_from']) || !empty($_GET['search_ride_to']))) {
        $search_service = isset($_GET['search_service']) ? $_GET['search_service'] : '';
        $search_ride_from = isset($_GET['search_ride_from']) ? $_GET['search_ride_from'] : '';
        $search_ride_to = isset($_GET['search_ride_to']) ? $_GET['search_ride_to'] : '';

        $sql = "SELECT * FROM rides WHERE 1";
        if (!empty($search_service)) {
            $sql .= " AND service = '$search_service'";
        }
        if (!empty($search_ride_from)) {
            $sql .= " AND ride_from = '$search_ride_from'";
        }
        if (!empty($search_ride_to)) {
            $sql .= " AND ride_to = '$search_ride_to'";
        }

        $result = $conn->query($sql);

        if ($result === false) {
            die("Error executing query: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Service</th><th>Amount</th><th>From</th><th>To</th><th>Vehicle Number</th><th>Mobile Number</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["service"] . "</td>";
                echo "<td>" . $row["amount"] . "</td>";
                echo "<td>" . $row["ride_from"] . "</td>";
                echo "<td>" . $row["ride_to"] . "</td>";
                echo "<td>" . $row["vehicle_number"] . "</td>";
                echo "<td>" . $row["mobile_number"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No rides found";
        }

        $conn->close();
    }
    ?>

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
