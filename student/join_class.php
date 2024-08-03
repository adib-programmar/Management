<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_code = $_POST['class_code'];
    $student_id = $_SESSION['user_id'];

    $sql = "UPDATE users SET class_code='$class_code', approved=0 WHERE id='$student_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Join request sent successfully. Waiting for admin approval.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Class</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Join Class</h2>
        <form action="" method="POST" class="w-1/2 mx-auto mt-5">
            <div class="mb-4">
                <label for="class_code" class="block text-sm font-bold mb-2">Class Code</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="class_code" name="class_code" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Join</button>
        </form>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
