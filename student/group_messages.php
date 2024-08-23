<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$student_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST['message'];

    $sql = "INSERT INTO messages (class_id, user_id, message) VALUES ('$class_id', '$student_id', '$message')";
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
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #1a202c;
            color: #e2e8f0;
        }
        .chat-container {
            height: calc(100vh - 200px);
            background: linear-gradient(120deg, #2a4365, #1a202c);
            border-radius: 0.75rem;
            overflow-y: auto;
            padding: 1rem;
        }
        .message-bubble {
            max-width: 70%;
            word-wrap: break-word;
            padding: 0.75rem;
            border-radius: 1rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            font-size: 0.875rem;
        }
        .message-bubble.student {
            background-color: #4a5568;
            color: #edf2f7;
        }
        .message-bubble.other {
            background-color: #2c5282;
            color: #e2e8f0;
        }
        .message-input {
            background-color: #2d3748;
            border-radius: 1rem;
            padding: 0.75rem;
            font-size: 0.875rem;
            color: #e2e8f0;
        }
        .message-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #2b6cb0;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-4 p-4">
        <h2 class="text-3xl font-bold mb-4 text-center text-blue-400"><i class="fas fa-comments mr-2"></i>Group Chat</h2>
        <div class="bg-gray-800 rounded-lg shadow-xl">
            <div class="chat-container scrollbar-hide">
                <?php while ($msg = $messages->fetch_assoc()): ?>
                    <div class="flex <?= $msg['user_id'] == $student_id ? 'justify-end' : 'justify-start' ?> mb-4">
                        <div class="message-bubble <?= $msg['user_id'] == $student_id ? 'student' : 'other' ?>">
                            <p class="font-bold"><?= htmlspecialchars($msg['username']) ?></p>
                            <p class="mt-2"><?= htmlspecialchars($msg['message']) ?></p>
                            <p class="text-xs text-gray-400 mt-1"><?= date('H:i', strtotime($msg['created_at'])) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <form action="" method="POST" class="bg-gray-900 p-4 flex items-center">
                <input type="text" name="message" class="message-input flex-grow mr-4" placeholder="Type a message..." required>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
        <a href="index.php" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mt-4 inline-block transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
