<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$results = $conn->query("SELECT * FROM results WHERE class_id='$class_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Result</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-6 bg-gray-800 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-6">Results</h2>
        <ul class="space-y-4">
            <?php while ($result = $results->fetch_assoc()): ?>
                <li class="bg-gray-700 p-4 rounded-lg shadow-md">
                    <h5 class="text-xl font-bold text-yellow-300"><?= htmlspecialchars($result['exam_name']) ?></h5>
                    <a href="<?= htmlspecialchars($result['result_pdf']) ?>" target="_blank" class="text-blue-400 underline hover:text-blue-600">View Result</a>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-6 inline-block">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
