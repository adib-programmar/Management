<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch all classes, regardless of who created them
$classes = $conn->query("SELECT * FROM classes");
if (!$classes) {
    die("Error fetching classes: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Created Classes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl mb-4">Created Classes</h2>
        <ul class="list-none p-0 mt-5">
            <?php while ($class = $classes->fetch_assoc()): ?>
                <li class="bg-gray-800 mb-2 rounded p-4">
                    <h3 class="text-xl"><?= htmlspecialchars($class['class_name']) ?></h3>
                    <p><strong>Class Code:</strong> <?= htmlspecialchars($class['class_code']) ?></p>
                    <a href="view_dues.php?class_id=<?= $class['id'] ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-2 inline-block">Manage Dues</a>
                    <a href="view_results.php?class_id=<?= $class['id'] ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 inline-block">Publish Results</a>
                    <a href="group_messages.php?class_id=<?= $class['id'] ?>" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded mt-2 inline-block">Group Messages</a>
                    <a href="view_students.php?class_id=<?= $class['id'] ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mt-2 inline-block">View Students</a>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
