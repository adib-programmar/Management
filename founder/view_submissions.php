<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

$due_id = $_GET['due_id'];
$submissions = $conn->query("SELECT s.*, u.name, u.username FROM submissions s JOIN users u ON s.student_id = u.id WHERE s.due_id='$due_id'");

// Fetch due information
$due = $conn->query("SELECT * FROM dues WHERE id='$due_id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Submissions</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Submissions for <?= htmlspecialchars($due['due_title']) ?></h2>
        <form method="post" action="download_submissions.php">
            <input type="hidden" name="due_id" value="<?= $due_id ?>">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">Download as CSV</button>
        </form>
        <ul class="list-none p-0 mt-5">
            <?php while ($submission = $submissions->fetch_assoc()): ?>
                <li class="bg-gray-800 mb-2 rounded p-4">
                    <strong><?= htmlspecialchars($submission['name']) ?> (<?= htmlspecialchars($submission['username']) ?>):</strong>
                    <a href="<?= htmlspecialchars($submission['submission_file']) ?>" class="text-blue-500 underline" target="_blank">View Submission</a>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="manage_dues.php?class_id=<?= htmlspecialchars($due['class_id']) ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
