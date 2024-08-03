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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-white">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-3xl font-bold text-center mb-6"><i class="fas fa-sign-in-alt"></i> Login</h2>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-bold mb-2"><i class="fas fa-user"></i> Username</label>
                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" name="username" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-bold mb-2"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" required>
                </div>
                <input type="hidden" name="role" value="<?= htmlspecialchars($_GET['role']) ?>">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"><i class="fas fa-sign-in-alt"></i> Login</button>
                <?php if (isset($error)): ?>
                    <div class="bg-red-500 text-white font-bold py-2 px-4 rounded mt-3"><?= $error ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
