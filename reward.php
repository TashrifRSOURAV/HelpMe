<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "blood_donors"; // Your MySQL database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$first_name = "";
$rating = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];

    // Search for the person by first name
    $sql = "SELECT * FROM donors WHERE first_name = '$first_name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Data found, display information and rating option
        $row = $result->fetch_assoc();
        $rating = $row['reward'];
    }
}

// Update rating
if (isset($_POST['submit_rating'])) {
    $rating = $_POST['rating'];

    // Update rating in the database
    $update_sql = "UPDATE donors SET reward = '$rating' WHERE first_name = '$first_name'";
    if ($conn->query($update_sql) === TRUE) {
        echo "Rating updated successfully!";
    } else {
        echo "Error updating rating: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search and Rate</title>
</head>
<body>
    <h2>Search and Rate</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="first_name">Search by First Name:</label>
        <input type="text" name="first_name" id="first_name" required value="<?php echo $first_name; ?>"><br>
        <input type="submit" value="Search">
    </form>

    <?php if ($rating !== "") { ?>
        <h3><?php echo $first_name ?>'s Information:</h3>
        <p>First Name: <?php echo $first_name; ?></p>
        <p>Last Name: <?php echo $row['last_name']; ?></p>
        <p>Reward: <?php echo $rating; ?></p>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="rating">Rate <?php echo $first_name ?>:</label>
            <select name="rating" id="rating">
                <option value="1" <?php if ($rating == "1") echo "selected"; ?>>1</option>
                <option value="2" <?php if ($rating == "2") echo "selected"; ?>>2</option>
                <option value="3" <?php if ($rating == "3") echo "selected"; ?>>3</option>
                <option value="4" <?php if ($rating == "4") echo "selected"; ?>>4</option>
                <option value="5" <?php if ($rating == "5") echo "selected"; ?>>5</option>
            </select><br>
            <input type="submit" name="submit_rating" value="Submit Rating">
        </form>
    <?php } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<p>No data found for the provided first name.</p>";
    } ?>
</body>
</html>

<?php
$conn->close();
?>
