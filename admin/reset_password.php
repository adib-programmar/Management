<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (empty($user_id) || empty($new_password)) {
        $error_message = 'Please select a user and enter a new password.';
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param('si', $hashed_password, $user_id);

        if ($stmt->execute()) {
            $success_message = 'Password reset successfully';
        } else {
            $error_message = 'Error: ' . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch users to populate the dropdown
$users_result = $conn->query("SELECT id, username FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="shortcut icon" href="admin.png" type="image/x-icon">
</head>
<body class="bg-gradient-to-r from-red-900 to-orange-900 text-white min-h-screen">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-8 text-center">Reset Password</h2>

        <div class="max-w-md mx-auto bg-white bg-opacity-10 backdrop-filter backdrop-blur-lg rounded-xl p-8 shadow-2xl">
            <?php if ($error_message): ?>
                <div class="bg-red-500 bg-opacity-75 text-white p-4 rounded-lg mb-6">
                    <?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="bg-green-500 bg-opacity-75 text-white p-4 rounded-lg mb-6">
                    <?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div>
                    <label for="user_id" class="block text-sm font-medium mb-2">Select User</label>
                    <select class="w-full px-4 py-2 rounded-md bg-gray-800 border border-gray-700 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50" id="user_id" name="user_id" required>
                        <option value="">Select a user</option>
                        <?php while ($user = $users_result->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-medium mb-2">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="w-full px-4 py-2 rounded-md bg-gray-800 border border-gray-700 focus:border-orange-500 focus:ring focus:ring-orange-200 focus:ring-opacity-50" required>
                </div>
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">Reset Password</button>
            </form>
        </div>

        <div class="mt-8 text-center">
            <a href="index.php" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-md transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105">Back to Dashboard</a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>