<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$results = $conn->query("SELECT * FROM results WHERE class_id='$class_id'");
if (!$results) {
    die("Error fetching results: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Results</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="admin.png" type="image/x-icon">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #0f172a; color: #e2e8f0; }
        .result-card { transition: all 0.3s ease; }
        .result-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-6">
        <h2 class="text-3xl font-bold mb-8 text-center text-blue-400"><i class="fas fa-graduation-cap mr-2"></i>Class Results</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($result = $results->fetch_assoc()): ?>
                <div class="result-card bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-4 text-blue-300"><?= htmlspecialchars($result['exam_name']) ?></h3>
                        <a href="<?= htmlspecialchars('/Management/' . $result['result_pdf']) ?>" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded-full transition duration-300">
                            <i class="fas fa-file-pdf mr-1"></i> View Result
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-8">
            <a href="view_classes.php" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Back to Classes
            </a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>