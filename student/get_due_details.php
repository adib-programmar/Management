<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['due_id'])) {
    $due_id = $_GET['due_id'];
    $due = $conn->query("SELECT * FROM dues WHERE id='$due_id'")->fetch_assoc();

    echo json_encode([
        'due_title' => htmlspecialchars($due['due_title']),
        'due_description' => htmlspecialchars($due['due_description']),
        'attachment' => htmlspecialchars($due['attachment']),
        'due_date' => htmlspecialchars($due['due_date'])
    ]);
}
?>
