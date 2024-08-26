<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_class'])) {
    $class_name = $_POST['class_name'];
    $class_code = uniqid('class_');

    $sql = "INSERT INTO classes (class_name, class_code, created_by) VALUES ('$class_name', '$class_code', '$admin_id')";
    if ($conn->query($sql) === TRUE) {
        $success_message = "Class created successfully";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="shortcut icon" href="admin.png" type="image/x-icon">
</head>
<body class="bg-gradient-to-r from-blue-900 to-purple-900 text-white min-h-screen">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-8 text-center">Manage Classes</h2>
        
        <?php if (isset($success_message)): ?>
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 text-center"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6 text-center"><?= $error_message ?></div>
        <?php endif; ?>
        
        <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl p-8 shadow-2xl mb-8">
            <h3 class="text-2xl font-semibold mb-4">Create New Class</h3>
            <form id="create-class-form" action="" method="POST">
                <div class="mb-4">
                    <label for="class_name" class="block text-sm font-medium mb-2">Class Name</label>
                    <input type="text" class="w-full px-4 py-2 rounded-md bg-gray-800 border border-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" id="class_name" name="class_name" required>
                </div>
                <button type="submit" name="create_class" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">Create Class</button>
            </form>
        </div>
        
        <div class="bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl p-8 shadow-2xl">
            <h3 class="text-2xl font-semibold mb-4">Existing Classes</h3>
            <ul id="class-list" class="space-y-4">
                <!-- JavaScript will populate this list -->
            </ul>
        </div>
        
        <div class="mt-8 text-center">
            <a href="index.php" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">Back to Dashboard</a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>