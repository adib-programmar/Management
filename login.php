<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "SELECT * FROM users WHERE username='$username' AND role='$role'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            if ($row['approved'] || $role == 'admin') {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                header("Location: " . $row['role'] . "/index.php");
            } else {
                $error = "Approval pending from admin.";
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl text-center">Login</h2>
        <form action="" method="POST" class="w-1/2 mx-auto mt-5">
            <div class="mb-4">
                <label for="username" class="block text-sm font-bold mb-2">Username</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" name="username" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-bold mb-2">Password</label>
                <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" required>
            </div>
            <input type="hidden" name="role" value="<?= htmlspecialchars($_GET['role']) ?>">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
            <?php if (isset($error)): ?>
                <div class="bg-red-500 text-white font-bold py-2 px-4 rounded mt-3"><?= $error ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
