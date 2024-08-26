<?php
include '../config.php';
session_start();

// Check if the user is an admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Get the class ID from the URL
$class_id = $_GET['class_id'] ?? null;

// Verify that the class_id is provided
if (!$class_id) {
    die("Error: Class ID is not provided.");
}

// Query to get dues for the specified class ID
$sql = "SELECT * FROM dues WHERE class_id='$class_id'";
$dues = $conn->query($sql);

// Check if the query was successful
if (!$dues) {
    die("Error fetching dues: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Dues</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="admin.png" type="image/x-icon">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
        }
        .due-card {
            transition: all 0.3s ease;
            transform: translateY(0);
        }
        .due-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .attachment {
            color: #38bdf8;
            transition: color 0.3s ease;
        }
        .attachment:hover {
            color: #60a5fa;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-6">
        <h2 class="text-3xl font-bold mb-8 text-center text-blue-400">
            <i class="fas fa-money-check-alt mr-2"></i>Class Dues
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($due = $dues->fetch_assoc()): ?>
                <div class="due-card bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2 text-blue-300">
                            <?= htmlspecialchars($due['due_title']) ?>
                        </h3>
                        <p class="text-gray-400 mb-2">
                            <?= htmlspecialchars($due['due_description']) ?>
                        </p>
                        <p class="text-yellow-400 mb-4">
                            <strong>Due Date:</strong> <?= htmlspecialchars($due['due_date']) ?>
                        </p>
                        <?php if ($due['attachment']): ?>
                            <a href="../uploads/<?= htmlspecialchars($due['attachment']) ?>" 
                               class="attachment inline-flex items-center text-sm font-semibold transition duration-300 ease-in-out">
                                <i class="fas fa-paperclip mr-2"></i>View Attachment
                            </a>
                        <?php else: ?>
                            <p class="text-red-400">No attachment available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-8">
            <a href="view_classes.php" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Back to Classes
            </a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
