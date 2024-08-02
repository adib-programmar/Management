<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $new_password = password_hash('13037', PASSWORD_BCRYPT);

    $sql = "UPDATE users SET password='$new_password' WHERE id='$user_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Password reset successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$students_and_founders = $conn->query("SELECT * FROM users WHERE role IN ('student', 'founder')");
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
        <h2 class="text-2xl">Reset Password</h2>
        <form action="" method="POST">
            <div class="mb-4">
                <label for="user_id" class="block text-sm font-bold mb-2">Select User</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="user_id" name="user_id" required>
                    <?php while ($user = $students_and_founders->fetch_assoc()): ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['username']) ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Reset Password</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
