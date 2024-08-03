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

    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'founder')";
    if ($conn->query($sql) === TRUE) {
        echo "Founder created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM users WHERE id='$delete_id' AND role='founder'";
    if ($conn->query($sql) === TRUE) {
        echo "Founder deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Create Founder</h2>
        <form action="" method="POST" class="w-full max-w-lg mx-auto mt-5">
            <div class="mb-4">
                <label for="username" class="block text-sm font-bold mb-2">Username</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" name="username" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-bold mb-2">Password</label>
                <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" name="password" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
        </form>
        <h3 class="text-xl mt-5">Created Founders</h3>
        <ul class="list-group mt-4">
            <?php while ($founder = $founders->fetch_assoc()): ?>
                <li class="list-group-item flex justify-between items-center bg-gray-800 p-4 rounded mb-2">
                    <div>
                        <strong><?= htmlspecialchars($founder['username']) ?></strong>
                    </div>
                    <a href="?delete_id=<?= $founder['id'] ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</a>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>