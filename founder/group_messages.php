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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Group Messages</h2>
        <form action="" method="POST" class="mb-4">
            <div class="mb-4">
                <label for="message" class="block text-sm font-bold mb-2">Message</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="message" name="message" rows="3" required></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Send</button>
        </form>
        <h3 class="text-xl mt-5">Messages</h3>
        <ul class="list-group">
            <?php while ($msg = $messages->fetch_assoc()): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($msg['username']) ?>:</strong>
                    <p><?= htmlspecialchars($msg['message']) ?></p>
                    <small class="text-gray-500"><?= $msg['created_at'] ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
