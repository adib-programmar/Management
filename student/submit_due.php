<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$student_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $due_id = $_POST['due_id'];
    $submission_file = $_FILES['submission_file']['name'];
    $target_dir = "../uploads/submissions/";
    $target_file = $target_dir . basename($submission_file);

    if (move_uploaded_file($_FILES['submission_file']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO submissions (due_id, student_id, submission_file) VALUES ('$due_id', '$student_id', '$target_file')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='bg-green-500 text-white p-4 rounded'>Submission uploaded successfully</div>";
        } else {
            echo "<div class='bg-red-500 text-white p-4 rounded'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } else {
        echo "<div class='bg-red-500 text-white p-4 rounded'>Failed to upload file.</div>";
    }
}

$dues = $conn->query("SELECT * FROM dues WHERE class_id='$class_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Due</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#due_id").change(function() {
                var due_id = $(this).val();
                $.ajax({
                    url: 'get_due_details.php',
                    type: 'GET',
                    data: { due_id: due_id },
                    success: function(response) {
                        var due = JSON.parse(response);
                        $("#due_title").text(due.due_title);
                        $("#due_description").text(due.due_description);
                        $("#due_attachment").attr("href", due.attachment).text("View Attachment");
                        $("#due_date").text(due.due_date);

                        var currentDate = new Date();
                        var dueDate = new Date(due.due_date);

                        if (currentDate > dueDate) {
                            $("#submit_button").prop("disabled", true).css("background-color", "red");
                        } else {
                            $("#submit_button").prop("disabled", false).css("background-color", "");
                        }
                    }
                });
            });

            $("#due_id").change(); // Trigger change to load details for the first due
        });
    </script>
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-2xl">Submit Due</h2>
        <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
            <div class="mb-4">
                <label for="due_id" class="block text-sm font-bold mb-2">Select Due</label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="due_id" name="due_id" required>
                    <?php while ($due = $dues->fetch_assoc()): ?>
                        <option value="<?= $due['id'] ?>"><?= htmlspecialchars($due['due_title']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="due_title" class="block text-sm font-bold mb-2">Title</label>
                <p id="due_title" class="bg-gray-800 p-2 rounded"></p>
            </div>
            <div class="mb-4">
                <label for="due_description" class="block text-sm font-bold mb-2">Description</label>
                <p id="due_description" class="bg-gray-800 p-2 rounded"></p>
            </div>
            <div class="mb-4">
                <label for="due_attachment" class="block text-sm font-bold mb-2">Attachment</label>
                <a id="due_attachment" class="bg-gray-800 p-2 rounded block text-blue-500 underline"></a>
            </div>
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-bold mb-2">Due Date</label>
                <p id="due_date" class="bg-gray-800 p-2 rounded"></p>
            </div>
            <div class="mb-4">
                <label for="submission_file" class="block text-sm font-bold mb-2">Upload Submission File</label>
                <input type="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="submission_file" name="submission_file" required>
            </div>
            <button type="submit" id="submit_button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
        </form>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
