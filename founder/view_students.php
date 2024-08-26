<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];

$class_query = $conn->query("SELECT class_code, class_name FROM classes WHERE id='$class_id'");
$class = $class_query->fetch_assoc();
$class_code = $class['class_code'];
$class_name = $class['class_name'];

$students = $conn->query("
    SELECT *
    FROM users
    WHERE class_code='$class_code' AND role='student'
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - <?= htmlspecialchars($class_name) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="founder.png" type="image/x-icon">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #0f172a; color: #e2e8f0; }
        .student-card { transition: all 0.3s ease; }
        .student-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-6">
        <h2 class="text-3xl font-bold mb-8 text-center text-blue-400"><i class="fas fa-user-graduate mr-2"></i>Students in <?= htmlspecialchars($class_name) ?></h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($student = $students->fetch_assoc()): ?>
                <div class="student-card bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-blue-300"><?= htmlspecialchars($student['name']) ?></h3>
                        <p class="text-gray-400 mb-4"><strong>Username:</strong> <?= htmlspecialchars($student['username']) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-8">
            <a href="index.php" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>