<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

$founder_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['leave_class'])) {
    $class_code = $_POST['class_code'];
    $sql = "UPDATE users SET class_code=NULL, approved=0 WHERE id='$founder_id' AND class_code='$class_code'";
    if ($conn->query($sql) === TRUE) {
        echo "<p class='bg-red-500 text-white p-2 rounded mb-4'>Left class successfully</p>";
    } else {
        echo "<p class='bg-red-500 text-white p-2 rounded mb-4'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$joined_classes = $conn->query("SELECT c.*, 
                                       (SELECT username FROM users WHERE role='founder' AND class_code=c.class_code LIMIT 1) AS founder_username
                                FROM classes c 
                                JOIN users u ON c.class_code = u.class_code 
                                WHERE u.id='$founder_id' AND u.approved=1");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Founder Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5 p-5 bg-gray-800 rounded-lg shadow-lg">
        <h2 class="text-3xl font-bold mb-5 text-center">Welcome Founder</h2>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110" onclick="location.href='join_class.php'">Join Class</button>
        <h3 class="text-2xl mt-8 mb-4 font-semibold">Joined Classes</h3>
        <ul class="space-y-4">
            <?php while ($class = $joined_classes->fetch_assoc()): ?>
                <li class="bg-gray-700 p-4 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="text-xl font-semibold"><?= htmlspecialchars($class['class_name'] ?? '') ?></h4>
                        <span class="text-sm text-gray-400">Founder: <?= htmlspecialchars($class['founder_username'] ?? 'Unknown') ?></span>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <a href="manage_dues.php?class_id=<?= $class['id'] ?>" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Manage Dues</a>
                        <a href="publish_results.php?class_id=<?= $class['id'] ?>" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Publish Results</a>
                        <a href="group_messages.php?class_id=<?= $class['id'] ?>" class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Group Messages</a>
                        <form action="" method="POST" class="inline">
                            <input type="hidden" name="class_code" value="<?= htmlspecialchars($class['class_code'] ?? '') ?>">
                            <button type="submit" name="leave_class" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">Leave Class</button>
                        </form>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full mt-6 inline-block transition duration-300">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>