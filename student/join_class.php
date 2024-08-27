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
        echo "<div class='text-center text-green-500 font-bold mt-6'>Join request sent successfully. Waiting for admin approval.</div>";
    } else {
        echo "<div class='text-center text-red-500 font-bold mt-6'>Error: " . $sql . "<br>" . $conn->error . "</div>";
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
    <link rel="shortcut icon" href="student.png" type="image/x-icon">

</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-8 bg-gray-800 rounded-lg shadow-xl max-w-lg">
        <h2 class="text-3xl font-extrabold text-center text-blue-400 mb-8">Join a Class</h2>
        <form action="" method="POST" class="space-y-6">
            <div>
                <label for="class_code" class="block text-lg font-medium text-yellow-300 mb-2">Class Code</label>
                <input type="text" id="class_code" name="class_code" required 
                    class="w-full p-4 rounded-lg bg-gray-700 text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-300">Join Class</button>
            <h3 text-center text-green-500 font-bold mt-6>Dont send request while joining in one class. It will be removed you from your joined classs. One account can join in only one account.</h3>
        </form>
        <a href="index.php" class="block text-center mt-6 text-sm text-gray-400 hover:text-gray-200 transition-colors duration-300">Back to Home</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
