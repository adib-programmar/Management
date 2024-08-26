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
    <link rel="shortcut icon" href="student.png" type="image/x-icon">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
        }
        .chat-container {
            height: calc(100vh - 200px);
            background: linear-gradient(120deg, #1e293b, #0f172a);
            border-radius: 1rem;
            overflow-y: auto;
            padding: 1rem;
            scrollbar-width: thin;
            scrollbar-color: #4b5563 #1f2937;
        }
        .chat-container::-webkit-scrollbar {
            width: 8px;
        }
        .chat-container::-webkit-scrollbar-track {
            background: #1f2937;
        }
        .chat-container::-webkit-scrollbar-thumb {
            background-color: #4b5563;
            border-radius: 20px;
            border: 3px solid #1f2937;
        }
        .message-bubble {
            max-width: 70%;
            word-wrap: break-word;
            padding: 0.75rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 0.875rem;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.5s ease forwards;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .message-bubble.student {
            background-color: #3b82f6;
            color: #ffffff;
        }
        .message-bubble.other {
            background-color: #4b5563;
            color: #ffffff;
        }
        .message-input {
            background-color: #1e293b;
            border-radius: 2rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            color: #e2e8f0;
            transition: all 0.3s ease;
        }
        .message-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #3b82f6;
        }
        .send-button {
            transition: all 0.3s ease;
        }
        .send-button:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-4 p-4">
        <h2 class="text-3xl font-bold mb-4 text-center text-blue-400">
            <i class="fas fa-comments mr-2"></i>Group Chat
        </h2>
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <div class="chat-container" id="chat-container">
                <?php while ($msg = $messages->fetch_assoc()): ?>
                    <div class="flex <?= $msg['user_id'] == $student_id ? 'justify-end' : 'justify-start' ?> mb-4">
                        <div class="message-bubble <?= $msg['user_id'] == $student_id ? 'student' : 'other' ?>">
                            <p class="font-bold"><?= htmlspecialchars($msg['username']) ?></p>
                            <p class="mt-2"><?= htmlspecialchars($msg['message']) ?></p>
                            <p class="text-xs text-gray-300 mt-1"><?= date('H:i', strtotime($msg['created_at'])) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <form action="" method="POST" class="bg-gray-900 p-4 flex items-center">
                <input type="text" name="message" class="message-input flex-grow mr-4" placeholder="Type a message..." required>
                <button type="submit" class="send-button bg-blue-500 hover:bg-blue-600 text-white rounded-full p-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
        <a href="index.php" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mt-4 inline-block transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const chatContainer = document.getElementById('chat-container');
            chatContainer.scrollTop = chatContainer.scrollHeight;

            const form = document.querySelector('form');
            const input = document.querySelector('input[name="message"]');

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if (input.value.trim()) {
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData
                    }).then(response => response.text())
                    .then(data => {
                        console.log(data);
                        const newMessage = document.createElement('div');
                        newMessage.className = 'flex justify-end mb-4';
                        newMessage.innerHTML = `
                            <div class="message-bubble student">
                                <p class="font-bold"><?= $_SESSION['username'] ?></p>
                                <p class="mt-2">${input.value}</p>
                                <p class="text-xs text-gray-300 mt-1">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                            </div>
                        `;
                        chatContainer.appendChild(newMessage);
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                        input.value = '';
                    });
                }
            });
        });
    </script>
</body>
</html>