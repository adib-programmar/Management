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
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Publish Results</h2>
        <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
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
        <h3 class="text-xl mt-5">Published Results</h3>
        <ul class="list-group">
            <?php while ($result = $results->fetch_assoc()): ?>
                <li class="list-group-item">
                    <h5><?= htmlspecialchars($result['exam_name']) ?></h5>
                    <a href="<?= $result['result_pdf'] ?>" target="_blank" class="text-blue-500 underline">View Result</a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
