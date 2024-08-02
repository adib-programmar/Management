<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

$founder_id = $_SESSION['user_id'];
$joined_classes = $conn->query("SELECT c.* FROM classes c JOIN users u ON c.class_code = u.class_code WHERE u.id='$founder_id' AND u.approved=1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Founder Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Welcome Founder</h2>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-5" onclick="location.href='join_class.php'">Join Class</button>
        <h3 class="text-xl mt-5">Joined Classes</h3>
        <ul class="list-group">
            <?php while ($class = $joined_classes->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($class['class_name']) ?>
                    <a href="manage_dues.php?class_id=<?= $class['id'] ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Manage Dues</a>
                    <a href="publish_results.php?class_id=<?= $class['id'] ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Publish Results</a>
                    <a href="group_messages.php?class_id=<?= $class['id'] ?>" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Group Messages</a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
