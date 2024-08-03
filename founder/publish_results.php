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
    $exam_name = $_POST['exam_name'];
    $result_pdf = $_FILES['result_pdf']['name'];
    $target_dir = "../uploads/results/";
    $target_file = $target_dir . basename($result_pdf);
    move_uploaded_file($_FILES['result_pdf']['tmp_name'], $target_file);

    $sql = "INSERT INTO results (class_id, exam_name, result_pdf, uploaded_by) VALUES ('$class_id', '$exam_name', '$target_file', '$founder_id')";
    if ($conn->query($sql) === TRUE) {
        echo "Result published successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$results = $conn->query("SELECT * FROM results WHERE class_id='$class_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publish Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-6 bg-gray-800 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-6">Publish Results</h2>
        <form action="" method="POST" enctype="multipart/form-data" class="mb-6">
            <div class="mb-4">
                <label for="exam_name" class="block text-sm font-bold mb-2">Exam Name</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="exam_name" name="exam_name" required>
            </div>
            <div class="mb-4">
                <label for="result_pdf" class="block text-sm font-bold mb-2">Upload Result PDF</label>
                <input type="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="result_pdf" name="result_pdf" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Publish</button>
        </form>
        <h3 class="text-xl font-semibold mt-6 mb-4">Published Results</h3>
        <ul class="space-y-4">
            <?php while ($result = $results->fetch_assoc()): ?>
                <li class="bg-gray-700 p-4 rounded shadow-md">
                    <h5 class="text-lg font-bold text-yellow-300"><?= htmlspecialchars($result['exam_name']) ?></h5>
                    <a href="<?= htmlspecialchars($result['result_pdf']) ?>" target="_blank" class="text-blue-400 underline hover:text-blue-600">View Result</a>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-6 inline-block">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
