<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'founder')");
    $stmt->bind_param("ss", $username, $password);
    
    if ($stmt->execute()) {
        $message = "Founder created successfully";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Start a transaction
    $conn->begin_transaction();
    
    try {
        // Delete founder's messages
        $stmt = $conn->prepare("DELETE FROM messages WHERE user_id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();

        // Delete submissions associated with the founder's dues
        $stmt = $conn->prepare("DELETE s FROM submissions s 
                                INNER JOIN dues d ON s.due_id = d.id 
                                WHERE d.created_by = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();

        // Delete founder's dues
        $stmt = $conn->prepare("DELETE FROM dues WHERE created_by = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();

        // Delete founder's classes
        $stmt = $conn->prepare("DELETE FROM classes WHERE created_by = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();

        // Update results to set uploaded_by to NULL
        $stmt = $conn->prepare("UPDATE results SET uploaded_by = NULL WHERE uploaded_by = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        
        // Finally, delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'founder'");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        
        // If all queries succeed, commit the transaction
        $conn->commit();
        $message = "Founder removed successfully. Their classes, messages, dues, and associated submissions have been deleted. Results remain but are no longer associated with the founder.";
    } catch (Exception $e) {
        // If an error occurs, roll back the changes
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
    }
}

$founders = $conn->query("SELECT * FROM users WHERE role='founder'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Founder</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="shortcut icon" href="admin.png" type="image/x-icon">
</head>
<body class="bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 min-h-screen text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto px-4 py-12">
        <?php if (isset($message)): ?>
            <div class="max-w-4xl mx-auto mb-6 bg-green-500 text-white p-4 rounded-xl">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <div class="max-w-4xl mx-auto bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl overflow-hidden shadow-2xl">
            <div class="p-8">
                <h2 class="text-3xl font-extrabold mb-6">Create Founder</h2>
                <form action="" method="POST" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium mb-2">Username</label>
                        <input type="text" id="username" name="username" required class="w-full px-4 py-2 rounded-md bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">Password</label>
                        <input type="password" id="password" name="password" required class="w-full px-4 py-2 rounded-md bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">Create</button>
                </form>
            </div>
        </div>

        <div class="mt-12 max-w-4xl mx-auto bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl overflow-hidden shadow-2xl">
            <div class="p-8">
                <h3 class="text-2xl font-semibold mb-6">Created Founders</h3>
                <ul class="space-y-4">
                    <?php while ($founder = $founders->fetch_assoc()): ?>
                        <li class="flex justify-between items-center bg-gray-800 bg-opacity-50 p-4 rounded-lg">
                            <span class="font-medium"><?= htmlspecialchars($founder['username']) ?></span>
                            <a href="?delete_id=<?= $founder['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105" onclick="return confirm('Are you sure you want to remove this founder? Their account, classes, messages, dues, and associated submissions will be deleted. Results will remain but will no longer be associated with the founder.');">Remove</a>
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