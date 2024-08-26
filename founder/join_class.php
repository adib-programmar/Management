<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_code = $_POST['class_code'];
    $founder_id = $_SESSION['user_id'];

    $sql = "UPDATE users SET class_code='$class_code', approved=0 WHERE id='$founder_id'";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='bg-green-500 text-white p-4 rounded mb-4'>Join request sent successfully. Waiting for admin approval.</p>";
    } else {
        echo "<p class='bg-red-500 text-white p-4 rounded mb-4'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Class</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="founder.png" type="image/x-icon">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-6">
        <h2 class="text-3xl font-bold mb-8 text-center text-blue-400"><i class="fas fa-door-open mr-2"></i>Join Class</h2>
        <form action="" method="POST" class="max-w-md mx-auto bg-gray-800 p-8 rounded-lg shadow-xl">
            <div class="mb-6">
                <label for="class_code" class="block text-sm font-bold mb-2 text-blue-300">
                    <i class="fas fa-key mr-2"></i>Class Code
                </label>
                <input type="text" class="w-full bg-gray-700 text-white rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-gray-600 focus:ring-2 focus:ring-blue-500" id="class_code" name="class_code" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">
                <i class="fas fa-sign-in-alt mr-2"></i>Join Class
            </button>
        </form>
        <div class="text-center mt-6">
            <a href="index.php" class="inline-block bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>