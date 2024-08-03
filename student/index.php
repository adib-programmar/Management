<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}

$student_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['leave_class'])) {
    $class_code = $_POST['class_code'];
    $sql = "UPDATE users SET class_code=NULL, approved=0 WHERE id='$student_id' AND class_code='$class_code'";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='bg-red-500 text-white p-2'>Left class successfully</p>";
    } else {
        echo "<p class='bg-red-500 text-white p-2'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$joined_classes = $conn->query("SELECT c.* FROM classes c JOIN users u ON c.class_code = u.class_code WHERE u.id='$student_id' AND u.approved=1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5 p-5 bg-gray-800 rounded shadow-lg">
        <h2 class="text-2xl font-bold mb-5">Welcome Student</h2>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-5" onclick="location.href='join_class.php'">Join Class</button>
        <h3 class="text-xl mt-5 font-semibold">Joined Classes</h3>
        <ul class="list-none mt-3 p-5 bg-gray-700 rounded shadow-inner">
            <?php while ($class = $joined_classes->fetch_assoc()): ?>
                <li class="bg-green-500 p-3 rounded mb-3 flex justify-between items-center">
                    <?= htmlspecialchars($class['class_name']) ?>
                    <div class="flex space-x-2">
                        <a href="submit_due.php?class_id=<?= $class['id'] ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Submit Due</a>
                        <a href="group_messages.php?class_id=<?= $class['id'] ?>" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Group Messages</a>
                        <a href="view_result.php?class_id=<?= $class['id'] ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">View Result</a>
                        <form action="" method="POST" class="inline">
                            <input type="hidden" name="class_code" value="<?= htmlspecialchars($class['class_code'] ?? '') ?>">
                            <button type="submit" name="leave_class" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Leave Class</button>
                        </form>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
