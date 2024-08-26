<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$founder_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];

    $sql = "INSERT INTO messages (class_id, user_id, message) VALUES ('$class_id', '$founder_id', '$message')";
    if ($conn->query($sql) === TRUE) {
        echo "Message sent successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$messages = $conn->query("SELECT m.*, u.username FROM messages m JOIN users u ON m.user_id = u.id WHERE m.class_id='$class_id' ORDER BY m.created_at ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Messages</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="founder.png" type="image/x-icon">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1a202c;
            color: #e2e8f0;
        }
        .chat-container {
            height: calc(100vh - 200px);
            background-image: url('https://th.bing.com/th/id/OIP.r6uS0dq8NhL0IAGhWJBuwgHaNK?rs=1&pid=ImgDetMain');
            background-size: cover;
            background-position: top;
        }
        .message-bubble {
            max-width: 70%;
            word-wrap: break-word;
        }
        .message-input {
            background-color: #2d3748;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-4 p-4">
        <h2 class="text-3xl font-bold mb-4 text-center text-blue-400"><i class="fas fa-comments mr-2"></i>Group Chat</h2>
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <div class="chat-container overflow-y-auto p-4">
                <?php while ($msg = $messages->fetch_assoc()): ?>
                    <div class="flex <?= $msg['user_id'] == $founder_id ? 'justify-end' : 'justify-start' ?> mb-4">
                        <div class="message-bubble <?= $msg['user_id'] == $founder_id ? 'bg-blue-500' : 'bg-gray-700' ?> rounded-lg p-3 shadow">
                            <p class="text-sm font-bold mb-1"><?= htmlspecialchars($msg['username']) ?></p>
                            <p class="text-white"><?= htmlspecialchars($msg['message']) ?></p>
                            <p class="text-xs text-gray-300 mt-1"><?= date('H:i', strtotime($msg['created_at'])) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <form action="" method="POST" class="bg-gray-900 p-4">
                <div class="flex items-center">
                    <input type="text" name="message" class="message-input flex-grow mr-4 rounded-full py-2 px-4 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Type a message..." required>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
        <a href="index.php" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mt-4 inline-block transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>