<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $class = $_POST['class'];

    $sql = "INSERT INTO users (username, password, role, name, class_code) VALUES (?, ?, 'student', ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ssss', $username, $password, $name, $class);
        if ($stmt->execute()) {
            echo "Student created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Step 1: Delete messages related to the student
    $sql = "DELETE FROM messages WHERE user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $delete_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    // Step 2: Delete the student
    $sql = "DELETE FROM users WHERE id = ? AND role = 'student'";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $delete_id);
        if ($stmt->execute()) {
            echo "Student deleted successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}

$students = $conn->query("SELECT * FROM users WHERE role='student'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="shortcut icon" href="admin.png" type="image/x-icon">
</head>
<body class="bg-gradient-to-br from-gray-900 via-green-900 to-teal-900 min-h-screen text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl overflow-hidden shadow-2xl">
            <div class="p-8">
                <h2 class="text-3xl font-extrabold mb-6">Create Student</h2>
                <form action="" method="POST" class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Name</label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2 rounded-md bg-gray-800 border border-gray-700 focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium mb-2">Username</label>
                        <input type="text" id="username" name="username" required class="w-full px-4 py-2 rounded-md bg-gray-800 border border-gray-700 focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">Password</label>
                        <input type="password" id="password" name="password" required class="w-full px-4 py-2 rounded-md bg-gray-800 border border-gray-700 focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="class" class="block text-sm font-medium mb-2">Class</label>
                        <input type="text" id="class" name="class" required class="w-full px-4 py-2 rounded-md bg-gray-800 border border-gray-700 focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                    </div>
                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">Create</button>
                </form>
            </div>
        </div>

        <div class="mt-12 max-w-4xl mx-auto bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl overflow-hidden shadow-2xl">
            <div class="p-8">
                <h3 class="text-2xl font-semibold mb-6">Created Students</h3>
                <ul class="space-y-4">
                    <?php while ($student = $students->fetch_assoc()): ?>
                        <li class="flex justify-between items-center bg-gray-800 bg-opacity-50 p-4 rounded-lg">
                            <div>
                                <span class="font-medium"><?= htmlspecialchars($student['name']) ?>:</span>
                                <span class="ml-2"><?= htmlspecialchars($student['username']) ?></span>
                                <span class="ml-2 text-teal-400">(<?= htmlspecialchars($student['class_code']) ?>)</span>
                            </div>
                            <a href="?delete_id=<?= $student['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="index.php" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">Back to Dashboard</a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
