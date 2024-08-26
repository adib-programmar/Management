<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $due_id = $_POST['due_id'];

    // Fetch due and class information
    $due_info = $conn->query("SELECT d.due_title, d.due_description, d.due_date, c.class_name, c.class_code 
                              FROM dues d 
                              JOIN classes c ON d.class_id = c.id 
                              WHERE d.id='$due_id'")->fetch_assoc();

    // Fetch submissions
    $submissions = $conn->query("SELECT s.*, u.name, u.username 
                                 FROM submissions s 
                                 JOIN users u ON s.student_id = u.id 
                                 WHERE s.due_id='$due_id'");

    // Fetch students who haven't submitted
    $not_submitted = $conn->query("SELECT u.name, u.username 
                                   FROM users u 
                                   LEFT JOIN submissions s ON u.id = s.student_id AND s.due_id = '$due_id'
                                   WHERE u.role = 'student' AND u.class_code = '{$due_info['class_code']}'
                                   AND s.id IS NULL");

    $filename = 'submissions_' . preg_replace('/[^A-Za-z0-9]/', '_', $due_info['due_title']) . '_' . date('Y-m-d') . '.csv';

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=' . $filename);

    $output = fopen('php://output', 'w');

    // Write CSV header
    fputcsv($output, ['Due Title', 'Due Description', 'Due Date', 'Class Name', 'Class Code', 'Student Name', 'Username', 'Submission Status', 'File URL']);

    // Write submitted entries
    while ($submission = $submissions->fetch_assoc()) {
        fputcsv($output, [
            $due_info['due_title'],
            $due_info['due_description'],
            $due_info['due_date'],
            $due_info['class_name'],
            $due_info['class_code'],
            $submission['name'],
            $submission['username'],
            'Submitted',
            $submission['submission_file']
        ]);
    }

    // Write not submitted entries
    while ($student = $not_submitted->fetch_assoc()) {
        fputcsv($output, [
            $due_info['due_title'],
            $due_info['due_description'],
            $due_info['due_date'],
            $due_info['class_name'],
            $due_info['class_code'],
            $student['name'],
            $student['username'],
            'Not Submitted',
            'N/A'
        ]);
    }

    fclose($output);
    exit();
}
?>