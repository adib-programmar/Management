<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$founder_id = $_SESSION['user_id'];

// Handle due deletion
if (isset($_GET['delete_due_id'])) {
    $delete_due_id = $_GET['delete_due_id'];
    $conn->query("DELETE FROM dues WHERE id='$delete_due_id'");
    header("Location: manage_dues.php?class_id=$class_id");
    exit();
}

// Handle due edit
if (isset($_GET['edit_due_id'])) {
    $edit_due_id = $_GET['edit_due_id'];
    $due_to_edit = $conn->query("SELECT * FROM dues WHERE id='$edit_due_id'")->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $due_title = $_POST['due_title'];
    $due_description = $_POST['due_description'];
    $due_date = $_POST['due_date'];
    $attachment = $_FILES['attachment']['name'];

    $target_dir = "../uploads/attachments/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($attachment);

    if (isset($_POST['edit_due_id'])) {
        $edit_due_id = $_POST['edit_due_id'];
        if (!empty($attachment)) {
            move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file);
            $sql = "UPDATE dues SET due_title='$due_title', due_description='$due_description', due_date='$due_date', attachment='$target_file' WHERE id='$edit_due_id'";
        } else {
            $sql = "UPDATE dues SET due_title='$due_title', due_description='$due_description', due_date='$due_date' WHERE id='$edit_due_id'";
        }
    } else {
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
            $sql = "INSERT INTO dues (class_id, due_title, due_description, due_date, attachment, created_by) VALUES ('$class_id', '$due_title', '$due_description', '$due_date', '$target_file', '$founder_id')";
        }
    }

    if ($conn->query($sql) === TRUE) {
        echo "<div class='bg-green-500 text-white p-4 rounded'>Due saved successfully</div>";
    } else {
        echo "<div class='bg-red-500 text-white p-4 rounded'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

$dues = $conn->query("SELECT * FROM dues WHERE class_id='$class_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Dues</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h2 class="text-3xl font-bold mb-5">Manage Dues</h2>
        <form action="" method="POST" enctype="multipart/form-data" class="bg-gray-800 p-8 rounded-lg shadow-lg">
            <input type="hidden" name="edit_due_id" value="<?= isset($due_to_edit) ? $due_to_edit['id'] : '' ?>">
            <div class="mb-4">
                <label for="due_title" class="block text-sm font-bold mb-2">Due Title</label>
                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="due_title" name="due_title" value="<?= isset($due_to_edit) ? $due_to_edit['due_title'] : '' ?>" required>
            </div>
            <div class="mb-4">
                <label for="due_description" class="block text-sm font-bold mb-2">Due Description</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="due_description" name="due_description" rows="3" required><?= isset($due_to_edit) ? $due_to_edit['due_description'] : '' ?></textarea>
            </div>
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-bold mb-2">Due Date</label>
                <input type="datetime-local" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="due_date" name="due_date" value="<?= isset($due_to_edit) ? date('Y-m-d\TH:i', strtotime($due_to_edit['due_date'])) : '' ?>" required>
            </div>
            <div class="mb-4">
                <label for="attachment" class="block text-sm font-bold mb-2">Upload Attachment</label>
                <input type="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline" id="attachment" name="attachment">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"><?= isset($due_to_edit) ? 'Update Due' : 'Create Due' ?></button>
        </form>
        <h3 class="text-2xl font-bold mt-10">Existing Dues</h3>
        <ul class="list-none p-0 mt-5">
            <?php while ($due = $dues->fetch_assoc()): ?>
                <li class="bg-gray-800 mb-4 rounded p-4 flex justify-between items-center">
                    <div>
                        <h4 class="text-xl font-semibold"><?= htmlspecialchars($due['due_title']) ?></h4>
                        <p><?= htmlspecialchars($due['due_description']) ?></p>
                        <p><strong>Due Date:</strong> <?= htmlspecialchars($due['due_date']) ?></p>
                        <?php if (!empty($due['attachment'])): ?>
                            <a href="<?= $due['attachment'] ?>" class="text-blue-500 underline" target="_blank">View Attachment</a>
                        <?php endif; ?>
                    </div>
                    <div>
                        <a href="view_submissions.php?due_id=<?= $due['id'] ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">View Submissions</a>
                        <a href="manage_dues.php?class_id=<?= $class_id ?>&edit_due_id=<?= $due['id'] ?>" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded ml-2">Edit</a>
                        <a href="manage_dues.php?class_id=<?= $class_id ?>&delete_due_id=<?= $due['id'] ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">Delete</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
        <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-10">Back</a>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
