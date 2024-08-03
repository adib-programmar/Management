<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve_request'])) {
    $request_id = $_POST['request_id'];

    $sql = "UPDATE users SET approved=1 WHERE id='$request_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Request approved successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$pending_requests = $conn->query("SELECT * FROM users WHERE approved=0 AND role IN ('student', 'founder')");
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
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Approve Requests</h2>
        <ul class="list-group">
            <?php while ($request = $pending_requests->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($request['name']) ?> (<?= htmlspecialchars($request['username']) ?>)
                    <form action="" method="POST" class="d-inline">
                        <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                        <button type="submit" name="approve_request" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Approve</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
