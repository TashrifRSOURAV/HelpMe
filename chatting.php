<?php
session_start();
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$database = "blood_donors"; // your database name
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.html");
    exit;
}

$mobile_number = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['sendMessage'])) {
        $sender_mobile = $mobile_number;
        $receiver_mobile = $_POST['receiver_mobile'];
        $message = $_POST['message'];

        $sql = "INSERT INTO chat (sender_mobile, receiver_mobile, message) VALUES ('$sender_mobile', '$receiver_mobile', '$message')";

        if ($conn->query($sql) === TRUE) {
            echo "Message sent successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        exit;
    } elseif (isset($_POST['getMessages'])) {
        $contact_mobile = $_POST['contact_mobile'];
        $sql = "SELECT chat.*, donors.first_name AS sender_name 
                FROM chat 
                JOIN donors ON chat.sender_mobile = donors.mobile_number 
                WHERE (sender_mobile='$mobile_number' AND receiver_mobile='$contact_mobile') 
                OR (sender_mobile='$contact_mobile' AND receiver_mobile='$mobile_number') 
                ORDER BY timestamp ASC";
        $result = $conn->query($sql);

        $messages = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Replace "You" with sender's name for sender's messages
                if ($row['sender_mobile'] == $mobile_number) {
                    $row['sender_name'] = 'You';
                }
                $messages[] = $row;
            }
        }

        echo json_encode($messages);
        exit;
    } elseif (isset($_POST['searchUsers'])) {
        $first_name = $_POST['first_name'];
        $sql = "SELECT mobile_number, first_name FROM donors WHERE first_name LIKE '%$first_name%'";
        $result = $conn->query($sql);

        $users = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        echo json_encode($users);
        exit;
    } elseif (isset($_POST['getRecentContacts'])) {
        $sql = "SELECT DISTINCT CASE WHEN sender_mobile = '$mobile_number' THEN receiver_mobile ELSE sender_mobile END AS contact_mobile,
                donors.first_name AS contact_name
                FROM chat
                JOIN donors ON CASE WHEN sender_mobile = '$mobile_number' THEN receiver_mobile ELSE sender_mobile END = donors.mobile_number
                WHERE sender_mobile = '$mobile_number' OR receiver_mobile = '$mobile_number'
                ORDER BY (SELECT MAX(timestamp) FROM chat WHERE (sender_mobile = '$mobile_number' AND receiver_mobile = contact_mobile) 
                OR (sender_mobile = contact_mobile AND receiver_mobile = '$mobile_number')) DESC";
        $result = $conn->query($sql);

        $contacts = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $contacts[] = $row;
            }
        }

        echo json_encode($contacts);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Chat Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e5ddd5;
            margin: 0;
            padding: 0; 
            justify-content: center;
            align-items: center; 
            height: 100vh; 
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-image: url("nahi2.jpg");
        }
        .container {
            display: flex;
            width: 80%;
            height: 80%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 40px;
            margin-left: 150px;
        }
        .sidebar {
            width: 30%;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #ccc;
        }
        .search-bar {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .search-bar input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .contacts {
            flex-grow: 1;
            overflow-y: scroll;
        }
        .contact {
            padding: 15px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }
        .contact:hover {
            background-color: #f1f1f1;
        }
        .chat-box {
            width: 70%;
            background-color: #fcfcfc85;
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            padding: 15px;
            background-color: #088b8b;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid #ccc;
        }
        .messages {
            flex-grow: 1;
            padding: 10px;
            overflow-y: scroll;
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            max-width: 70%;
        }
        .message.sender {
            background-color: lightgreen;
            align-self: flex-end;
            padding-left: 50px;
        
        }
        .message.receiver {
            background-color: #fff;
            align-self: flex-start;
            border-radius: 10px;
        }
        .message .sender-name {
            font-weight: bold;
        }
        .input-box {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
            background-color: #fff;
        }
        .input-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .input-box button {
            padding: 10px;
            background-color: #34b7f1;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
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
        <div class="sidebar">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search users by first name" oninput="searchUsers()">
            </div>
            <div class="contacts" id="contacts"></div>
        </div>
        <div class="chat-box">
            <div class="chat-header" id="chatHeader">Select a user to start chat</div>
            <div class="messages" id="messages"></div>
            <div class="input-box">
                <input type="text" id="messageInput" placeholder="Type a message...">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>
    <script>
    const senderMobile = <?php echo json_encode($mobile_number); ?>;
    let receiverMobile = null;
    let receiverName = '';

    function getMessages() {
        if (!receiverMobile) return;

        const formData = new FormData();
        formData.append('contact_mobile', receiverMobile);
        formData.append('getMessages', true);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const messagesDiv = document.getElementById('messages');
            messagesDiv.innerHTML = '';
            data.forEach(message => {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message');
                messageDiv.classList.add(message.sender_mobile === senderMobile ? 'sender' : 'receiver');
                const senderName = document.createElement('div');
                senderName.classList.add('sender-name');
                senderName.innerText = message.sender_name;
                // Make sender's name bold for unseen messages
                if (message.sender_mobile !== senderMobile && !message.seen) {
                    senderName.style.fontWeight = 'bold';
                }
                messageDiv.appendChild(senderName);
                const messageText = document.createElement('div');
                messageText.innerText = message.message;
                messageDiv.appendChild(messageText);
                messagesDiv.appendChild(messageDiv);
            });
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        });
    }

    function sendMessage() {
        if (!receiverMobile) {
            alert("Please select a user to chat with.");
            return;
        }

        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value;
        if (message.trim() === '') return;

        const formData = new FormData();
        formData.append('sender_mobile', senderMobile);
        formData.append('receiver_mobile', receiverMobile);
        formData.append('message', message);
        formData.append('sendMessage', true);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            messageInput.value = '';
            getMessages();
            updateRecentContacts();
        });
    }

    function searchUsers() {
        const searchInput = document.getElementById('searchInput');
        const firstName = searchInput.value;

        const formData = new FormData();
        formData.append('first_name', firstName);
        formData.append('searchUsers', true);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const contactsDiv = document.getElementById('contacts');
            contactsDiv.innerHTML = '';
            data.forEach(user => {
                const contactDiv = document.createElement('div');
                contactDiv.classList.add('contact');
                contactDiv.innerText = user.first_name;
                contactDiv.onclick = () => {
                    receiverMobile = user.mobile_number;
                    receiverName = user.first_name;
                    document.getElementById('chatHeader').innerText = ` ${receiverName}`;
                    getMessages();
                };
                contactsDiv.appendChild(contactDiv);
            });
        });
    }

    function getRecentContacts() {
        const formData = new FormData();
        formData.append('getRecentContacts', true);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const contactsDiv = document.getElementById('contacts');
            contactsDiv.innerHTML = '';
            data.forEach(contact => {
                const contactDiv = document.createElement('div');
                contactDiv.classList.add('contact');
                contactDiv.innerText = contact.contact_name;
                contactDiv.onclick = () => {
                    receiverMobile = contact.contact_mobile;
                    receiverName = contact.contact_name;
                    document.getElementById('chatHeader').innerText = ` ${receiverName}`;
                    getMessages();
                };
                contactsDiv.appendChild(contactDiv);
            });
        });
    }

    function updateRecentContacts() {
        getRecentContacts();
    }

    function fetchMessagesPeriodically() {
        setInterval(() => {
            getMessages();
            updateRecentContacts();
        }, 3000);
    }

    getRecentContacts();
    fetchMessagesPeriodically();


    
</script>

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
