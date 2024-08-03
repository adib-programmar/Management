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
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl mb-4">Reset Password</h2>

        <?php if ($error_message): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <?= htmlspecialchars($error_message ?? '', ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="bg-green-500 text-white p-3 rounded mb-4">
                <?= htmlspecialchars($success_message ?? '', ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="user_id" class="block text-sm font-bold mb-2">Select User</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="user_id" name="user_id" required>
                    <option value="">Select a user</option>
                    <?php while ($user = $users_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="new_password" class="block text-sm font-bold mb-2">New Password</label>
                <input type="password" id="new_password" name="new_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Reset Password</button>
        </form>

        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
