<?php
include '../config.php';
session_start();

// Ensure user is a student
if ($_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}

// Check if due_id is provided
if (isset($_GET['due_id'])) {
    $due_id = $_GET['due_id'];

    // Prepare and execute the SQL statement to fetch due details
    $stmt = $conn->prepare("SELECT due_title, due_description, due_date, attachment FROM dues WHERE id = ?");
    $stmt->bind_param("i", $due_id);
    $stmt->execute();
    $stmt->bind_result($due_title, $due_description, $due_date, $attachment);
    $stmt->fetch();
    $stmt->close();

    // Return the due details as a JSON object
    echo json_encode([
        'due_title' => htmlspecialchars($due_title),
        'due_description' => htmlspecialchars($due_description),
        'due_date' => htmlspecialchars($due_date),
        'attachment' => htmlspecialchars($attachment)
    ]);
}
?>
