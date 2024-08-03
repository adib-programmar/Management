<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve_request'])) {
        $request_id = $_POST['request_id'];
        $sql = "UPDATE users SET approved=1 WHERE id='$request_id'";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='bg-green-500 text-white p-2'>Request approved successfully</p>";
        } else {
            echo "<p class='bg-red-500 text-white p-2'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    } elseif (isset($_POST['reject_request'])) {
        $request_id = $_POST['request_id'];
        $sql = "UPDATE users SET class_code=NULL, approved=0 WHERE id='$request_id'";
        if ($conn->query($sql) === TRUE) {
            echo "<p class='bg-red-500 text-white p-2'>Request rejected successfully</p>";
        } else {
            echo "<p class='bg-red-500 text-white p-2'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
}

$pending_requests = $conn->query("SELECT users.*, classes.class_name FROM users LEFT JOIN classes ON users.class_code = classes.class_code WHERE users.approved=0 AND users.role IN ('student', 'founder')");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5 p-5 bg-gray-800 rounded shadow-lg">
        <h2 class="text-2xl font-bold mb-5">Approve Requests</h2>
        <?php if ($pending_requests->num_rows > 0): ?>
            <ul class="list-none p-0">
                <?php while ($request = $pending_requests->fetch_assoc()): ?>
                    <li class="bg-gray-700 p-4 rounded mb-2 flex justify-between items-center">
                        <span><?= htmlspecialchars($request['name'] ?? '') ?> (<?= htmlspecialchars($request['username'] ?? '') ?>) - <?= htmlspecialchars($request['role'] ?? '') ?> <?= !empty($request['class_name']) ? '- Class: ' . htmlspecialchars($request['class_name']) : '- Login approval' ?></span>
                        <form action="" method="POST" class="inline">
                            <input type="hidden" name="request_id" value="<?= htmlspecialchars($request['id'] ?? '') ?>">
                            <button type="submit" name="approve_request" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Approve</button>
                            <button type="submit" name="reject_request" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Reject</button>
                        </form>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">No pending requests.</p>
        <?php endif; ?>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4 inline-block">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
