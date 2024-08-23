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
        <h2 class="text-4xl font-bold mb-8 text-center text-blue-400">Welcome Founder</h2>
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
                        <h4 class="text-xl font-semibold mb-2"><?= htmlspecialchars($class['class_name'] ?? '') ?></h4>
                        <p class="text-sm text-gray-400 mb-4">Founder: <?= htmlspecialchars($class['founder_username'] ?? 'Unknown') ?></p>
                        <div class="flex flex-wrap gap-2">
                            <a href="manage_dues.php?class_id=<?= $class['id'] ?>" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded-full transition duration-300">
                                <i class="fas fa-money-bill-wave mr-1"></i> Manage Dues
                            </a>
                            <a href="publish_results.php?class_id=<?= $class['id'] ?>" class="bg-purple-500 hover:bg-purple-600 text-white text-sm font-bold py-2 px-4 rounded-full transition duration-300">
                                <i class="fas fa-chart-bar mr-1"></i> Publish Results
                            </a>
                            <a href="group_messages.php?class_id=<?= $class['id'] ?>" class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-2 px-4 rounded-full transition duration-300">
                                <i class="fas fa-comments mr-1"></i> Group Chat
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
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>