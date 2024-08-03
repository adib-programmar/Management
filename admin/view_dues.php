<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$dues = $conn->query("SELECT * FROM dues WHERE class_id='$class_id'");
if (!$dues) {
    die("Error fetching dues: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Dues</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Dues</h2>
        <ul class="list-none p-0 mt-5">
            <?php while ($due = $dues->fetch_assoc()): ?>
                <li class="bg-gray-800 mb-2 rounded p-4">
                    <h3 class="text-xl"><?= htmlspecialchars($due['due_title']) ?></h3>
                    <p><?= htmlspecialchars($due['due_description']) ?></p>
                    <p><strong>Due Date:</strong> <?= htmlspecialchars($due['due_date']) ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="view_classes.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
