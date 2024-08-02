<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['user_id'];
$classes = $conn->query("SELECT * FROM classes WHERE created_by='$admin_id'");

$class_list = [];
while ($class = $classes->fetch_assoc()) {
    $class_list[] = $class;
}

header('Content-Type: application/json');
echo json_encode($class_list);
?>
