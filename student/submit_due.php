<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$student_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $due_id = $_POST['due_id'];
    $submission_file = $_FILES['submission_file']['name'];
    $target_dir = "../uploads/submissions/";
    $target_file = $target_dir . basename($submission_file);
    move_uploaded_file($_FILES['submission_file']['tmp_name'], $target_file);

    $sql = "INSERT INTO submissions (due_id, student_id, submission_file) VALUES ('$due_id', '$student_id', '$target_file')";
    if ($conn->query($sql) === TRUE) {
        echo "Submission uploaded successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$dues = $conn->query("SELECT * FROM dues WHERE class_id='$class_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Due</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Submit Due</h2>
        <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
            <div class="mb-4">
                <label for="due_id" class="block text-sm font-bold mb-2">Select Due</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="due_id" name="due_id" required>
                    <?php while ($due = $dues->fetch_assoc()): ?>
                        <option value="<?= $due['id'] ?>"><?= htmlspecialchars($due['due_title']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="submission_file" class="block text-sm font-bold mb-2">Upload Submission File</label>
                <input type="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="submission_file" name="submission_file" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
        </form>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
