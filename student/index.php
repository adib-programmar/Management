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
        echo "<p class='bg-red-500 text-white p-2 rounded mb-4'>Left class successfully</p>";
    } else {
        echo "<p class='bg-red-500 text-white p-2 rounded mb-4'>Error: " . $sql . "<br>" . $conn->error . "</p>";
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
        }
        .card {
            background-color: #1e293b;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-8 p-6">
        <h2 class="text-4xl font-bold mb-8 text-center text-blue-400">Welcome Student</h2>
        <div class="text-center mb-8">
            <button onclick="location.href='join_class.php'" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                <i class="fas fa-plus-circle mr-2"></i>Join Class
            </button>
        </div>
        <h3 class="text-2xl font-semibold mb-6 text-blue-300">Joined Classes</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($class = $joined_classes->fetch_assoc()): ?>
                <div class="card rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h4 class="text-xl font-semibold mb-2"><?= htmlspecialchars($class['class_name']) ?></h4>
                        <div class="flex flex-wrap gap-2">
                            <a href="submit_due.php?class_id=<?= $class['id'] ?>" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded-full transition duration-300">
                                <i class="fas fa-clipboard-list mr-1"></i> Submit Due
                            </a>
                            <a href="group_messages.php?class_id=<?= $class['id'] ?>" class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-2 px-4 rounded-full transition duration-300">
                                <i class="fas fa-comments mr-1"></i> Group Messages
                            </a>
                            <a href="view_result.php?class_id=<?= $class['id'] ?>" class="bg-purple-500 hover:bg-purple-600 text-white text-sm font-bold py-2 px-4 rounded-full transition duration-300">
                                <i class="fas fa-chart-bar mr-1"></i> View Result
                            </a>
                        </div>
                    </div>
                    <form action="" method="POST" class="bg-gray-700 p-4">
                        <input type="hidden" name="class_code" value="<?= htmlspecialchars($class['class_code'] ?? '') ?>">
                        <button type="submit" name="leave_class" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition duration-300">
                            <i class="fas fa-sign-out-alt mr-1"></i> Leave Class
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-full mt-8 inline-block transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
