<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];

// Fetch the class code based on the class_id
$class_query = $conn->query("SELECT class_code FROM classes WHERE id='$class_id'");
$class = $class_query->fetch_assoc();
$class_code = $class['class_code'];

// Handle delete student request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student_id'])) {
    $student_id = $_POST['delete_student_id'];
    $conn->query("DELETE FROM users WHERE id='$student_id'");
    header("Location: view_students.php?class_id=$class_id");
    exit();
}

// Fetch students based on the class_code
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
    <title>View Students</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Students</h2>
        <ul class="list-none p-0 mt-5">
            <?php while ($student = $students->fetch_assoc()): ?>
                <li class="bg-gray-800 mb-2 rounded p-4 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl"><?= htmlspecialchars($student['name']) ?></h3>
                        <p><strong>Email:</strong> <?= htmlspecialchars($student['username']) ?></p>
                    </div>
                    <form method="POST" class="inline-block">
                        <input type="hidden" name="delete_student_id" value="<?= $student['id'] ?>">
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="view_classes.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
