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
    <link rel="shortcut icon" href="student.png" type="image/x-icon">

</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8 bg-gray-800 rounded-lg shadow-xl">
        <h2 class="text-4xl font-extrabold mb-8 text-blue-400">Your Results</h2>
        <ul class="space-y-6">
            <?php while ($result = $results->fetch_assoc()): ?>
                <li class="bg-gray-700 p-6 rounded-lg shadow-lg flex items-center justify-between">
                    <div>
                        <h5 class="text-2xl font-bold text-yellow-300 mb-2"><?= htmlspecialchars($result['exam_name']) ?></h5>
                        <p class="text-gray-400"><?= htmlspecialchars('Class ID: ' . $class_id) ?></p>
                    </div>
                    <a href="<?= htmlspecialchars('/Management/' . $result['result_pdf']) ?>" target="_blank" class="text-blue-400 flex items-center gap-2 underline hover:text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        View Result
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="index.php" class="mt-8 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-300">
            Back
        </a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
