<?php
include '../config.php';
session_start();

// Fetch all classes, regardless of who created them
$classes = $conn->query("SELECT * FROM classes");

$class_list = [];
while ($class = $classes->fetch_assoc()) {
    $class_list[] = $class;
}

header('Content-Type: application/json');
echo json_encode($class_list);
?>
