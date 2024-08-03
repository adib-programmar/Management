<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-3xl">Welcome, Admin</h2>
        <p class="mt-2">Manage the system using the options below.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-5">
            <div class="bg-gray-800 p-4 rounded shadow">
                <h3 class="text-xl mb-2">Classes</h3>
                <a href="manage_classes.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Manage Classes</a>
            </div>
            <div class="bg-gray-800 p-4 rounded shadow">
                <h3 class="text-xl mb-2">Students</h3>
                <a href="create_student.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Create Student</a>
            </div>
            <div class="bg-gray-800 p-4 rounded shadow">
                <h3 class="text-xl mb-2">Founders</h3>
                <a href="create_founder.php" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Create Founder</a>
            </div>
            <div class="bg-gray-800 p-4 rounded shadow">
                <h3 class="text-xl mb-2">Approve Requests</h3>
                <a href="approve_requests.php" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Approve Join Requests</a>
            </div>
            <div class="bg-gray-800 p-4 rounded shadow">
                <h3 class="text-xl mb-2">Reset Password</h3>
                <a href="reset_password.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Reset User Password</a>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
