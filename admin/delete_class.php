<?php
include '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $class_id = $data['class_id'];

    $sql = "DELETE FROM classes WHERE id='$class_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Class deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
