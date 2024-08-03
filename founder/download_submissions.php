<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $due_id = $_POST['due_id'];

    $submissions = $conn->query("SELECT s.*, u.name, u.username FROM submissions s JOIN users u ON s.student_id = u.id WHERE s.due_id='$due_id'");

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=submissions.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Student Name', 'Username', 'Submission File']);

    while ($submission = $submissions->fetch_assoc()) {
        fputcsv($output, [$submission['name'], $submission['username'], $submission['submission_file']]);
    }

    fclose($output);
    exit();
}
?>
