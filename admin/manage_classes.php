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
        echo "<p class='bg-green-500 text-white p-2'>Class created successfully</p>";
    } else {
        echo "<p class='bg-red-500 text-white p-2'>Error: " . $sql . "<br>" . $conn->error . "</p>";
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
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl mb-4">Manage Classes</h2>
        <form id="create-class-form" action="" method="POST" class="mb-4">
            <div class="mb-3">
                <label for="class_name" class="block text-sm font-bold mb-2">Class Name</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="class_name" name="class_name" required>
            </div>
            <button type="submit" name="create_class" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Class</button>
        </form>
        <h3 class="text-xl mt-5">Existing Classes</h3>
        <ul id="class-list" class="list-none p-0">
            <!-- JavaScript will populate this list -->
        </ul>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
