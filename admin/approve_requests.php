<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve_request'])) {
        $request_id = $_POST['request_id'];
        $sql = "UPDATE users SET approved=1 WHERE id='$request_id'";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='bg-green-500 text-white p-2'>Request approved successfully</p>";
        } else {
            echo "<p class='bg-red-500 text-white p-2'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    } elseif (isset($_POST['reject_request'])) {
        $request_id = $_POST['request_id'];
        $sql = "UPDATE users SET class_code=NULL, approved=0 WHERE id='$request_id'";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='bg-red-500 text-white p-2'>Request rejected successfully</p>";
        } else {
            echo "<p class='bg-red-500 text-white p-2'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
}

$pending_requests = $conn->query("SELECT users.*, classes.class_name FROM users LEFT JOIN classes ON users.class_code = classes.class_code WHERE users.approved=0 AND users.role IN ('student', 'founder')");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="shortcut icon" href="admin.png" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('https://i.ytimg.com/vi/B0_0J9Qfg5k/maxresdefault.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="text-white min-h-screen flex flex-col">
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-8 flex-grow flex items-center justify-center">
        <div class="bg-black bg-opacity-70 p-8 rounded-3xl shadow-2xl backdrop-filter backdrop-blur-lg w-full max-w-4xl">
            <h2 class="text-4xl font-bold text-center mb-8 text-gradient bg-gradient-to-r from-purple-400 via-pink-500 to-red-500">Approve Requests</h2>
            
            <?php if ($pending_requests->num_rows > 0): ?>
                <ul class="space-y-4">
                    <?php while ($request = $pending_requests->fetch_assoc()): ?>
                        <li class="bg-gray-800 bg-opacity-50 p-6 rounded-xl flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                            <span class="text-lg text-center sm:text-left">
                                <span class="font-semibold"><?= htmlspecialchars($request['name'] ?? '') ?></span>
                                <span class="text-gray-400">(<?= htmlspecialchars($request['username'] ?? '') ?>)</span><br>
                                <span class="text-sm text-gray-300">
                                    <?= htmlspecialchars($request['role'] ?? '') ?>
                                    <?= !empty($request['class_name']) ? '- Class: ' . htmlspecialchars($request['class_name']) : '- Login approval' ?>
                                </span>
                            </span>
                            <form action="" method="POST" class="flex space-x-3">
                                <input type="hidden" name="request_id" value="<?= htmlspecialchars($request['id'] ?? '') ?>">
                                <button type="submit" name="approve_request" class="bg-gradient-to-r from-green-400 to-green-600 text-white font-bold py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                    Approve
                                </button>
                                <button type="submit" name="reject_request" class="bg-gradient-to-r from-red-400 to-red-600 text-white font-bold py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                    Reject
                                </button>
                            </form>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="text-xl text-center text-gray-400">No pending requests.</p>
            <?php endif; ?>
            
            <div class="mt-12 text-center">
                <a href="index.php" class="bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-bold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg inline-block focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>