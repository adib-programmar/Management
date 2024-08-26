<?php
include '../config.php';
session_start();

// Check if the user is the founder
if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$founder_id = $_SESSION['user_id'];

// Handle due deletion
if (isset($_GET['delete_due_id'])) {
    $delete_due_id = $_GET['delete_due_id'];
    
    // Start a transaction
    $conn->begin_transaction();
    
    try {
        // First, delete associated submissions
        $conn->query("DELETE FROM submissions WHERE due_id='$delete_due_id'");
        
        // Then, delete the due
        $conn->query("DELETE FROM dues WHERE id='$delete_due_id'");
        
        // If both queries are successful, commit the transaction
        $conn->commit();
        
        header("Location: manage_dues.php?class_id=$class_id");
        exit();
    } catch (Exception $e) {
        // If an error occurs, roll back the changes
        $conn->rollback();
        echo "<div class='bg-red-500 text-white p-4 rounded'>Error: Unable to delete the due. Please try again later.</div>";
    }
}

// Handle due edit (fetch the data to be edited)
if (isset($_GET['edit_due_id'])) {
    $edit_due_id = $_GET['edit_due_id'];
    $due_to_edit = $conn->query("SELECT * FROM dues WHERE id='$edit_due_id'")->fetch_assoc();
}

// Handle form submission (create or update a due)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $due_title = $_POST['due_title'];
    $due_description = $_POST['due_description'];
    $due_date = $_POST['due_date'];

    $target_dir = "../uploads/attachments/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $attachment_sql = "";
    if (!empty($_FILES['attachment']['name'])) {
        $attachment = $_FILES['attachment']['name'];
        $target_file = $target_dir . basename($attachment);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is a PDF
        if ($file_type != "pdf") {
            echo "<div class='bg-red-500 text-white p-4 rounded'>Error: Only PDF files are allowed.</div>";
            $upload_error = true;
        } else {
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
                $attachment_sql = ", attachment='$target_file'";
            }
        }
    }

    if (!isset($upload_error)) {
        if (isset($_POST['edit_due_id']) && !empty($_POST['edit_due_id'])) {
            // Edit due
            $edit_due_id = $_POST['edit_due_id'];
            $sql = "UPDATE dues SET due_title='$due_title', due_description='$due_description', due_date='$due_date'$attachment_sql WHERE id='$edit_due_id'";
        } else {
            // Create new due
            $sql = "INSERT INTO dues (class_id, due_title, due_description, due_date, created_by$attachment_sql) VALUES ('$class_id', '$due_title', '$due_description', '$due_date', '$founder_id'$attachment_sql)";
        }

        if ($conn->query($sql) === TRUE) {
            echo "<div class='bg-green-500 text-white p-4 rounded'>Due saved successfully</div>";
        } else {
            echo "<div class='bg-red-500 text-white p-4 rounded'>Error: " . $conn->error . "</div>";
        }
    }
}

// Fetch dues for the class
$dues = $conn->query("SELECT * FROM dues WHERE class_id='$class_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Dues</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="shortcut icon" href="founder.png" type="image/x-icon">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .hover-scale {
            transition: transform 0.3s ease-in-out;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="min-h-screen text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto px-4 py-12">
        <div class="glass-morphism p-8 shadow-2xl mb-12">
            <h2 class="text-4xl font-bold text-center mb-8">Manage Dues</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="edit_due_id" value="<?= isset($due_to_edit) ? $due_to_edit['id'] : '' ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="due_title" class="block text-lg font-semibold mb-2">Due Title</label>
                        <input type="text" class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-20 focus:bg-opacity-30 focus:outline-none transition-all duration-300" id="due_title" name="due_title" value="<?= isset($due_to_edit) ? htmlspecialchars($due_to_edit['due_title']) : '' ?>" required>
                    </div>
                    <div>
                        <label for="due_date" class="block text-lg font-semibold mb-2">Due Date (12 hours standered *Will be auto convert into 24 hours*)</label>
                        <input type="datetime-local" class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-20 focus:bg-opacity-30 focus:outline-none transition-all duration-300" id="due_date" name="due_date" value="<?= isset($due_to_edit) ? date('Y-m-d\TH:i', strtotime($due_to_edit['due_date'])) : '' ?>" required>
                    </div>
                </div>
                <div>
                    <label for="due_description" class="block text-lg font-semibold mb-2">Due Description</label>
                    <textarea class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-20 focus:bg-opacity-30 focus:outline-none transition-all duration-300" id="due_description" name="due_description" rows="4" required><?= isset($due_to_edit) ? htmlspecialchars($due_to_edit['due_description']) : '' ?></textarea>
                </div>
                <div>
                    <label for="attachment" class="block text-lg font-semibold mb-2">Upload PDF Attachment (Optional)</label>
                    <input type="file" class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-20 focus:bg-opacity-30 focus:outline-none transition-all duration-300" id="attachment" name="attachment" accept=".pdf">
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                        <?= isset($due_to_edit) ? 'Update Due' : 'Create Due' ?>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-12">
            <h3 class="text-3xl font-bold mb-6 text-center">Existing Dues</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($due = $dues->fetch_assoc()): ?>
                    <div class="glass-morphism p-6 hover-scale">
                        <h4 class="text-2xl font-bold mb-2"><?= htmlspecialchars($due['due_title']) ?></h4>
                        <p class="text-gray-300 mb-4"><?= htmlspecialchars($due['due_description']) ?></p>
                        <p class="text-gray-300 mb-4"><i class="fas fa-calendar-alt mr-2"></i><?= htmlspecialchars($due['due_date']) ?></p>
                        <?php if (!empty($due['attachment'])): ?>
                            <a href="<?= htmlspecialchars($due['attachment']) ?>" class="inline-block text-indigo-400 hover:text-indigo-300 mb-4" target="_blank">
                                <i class="fas fa-file-pdf mr-2"></i>View PDF
                            </a>
                        <?php endif; ?>
                        <div class="flex justify-between mt-4">
                            <a href="view_submissions.php?due_id=<?= $due['id'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                                <i class="fas fa-eye mr-2"></i>View
                            </a>
                            <a href="manage_dues.php?class_id=<?= $class_id ?>&edit_due_id=<?= $due['id'] ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </a>
                            <a href="manage_dues.php?class_id=<?= $class_id ?>&delete_due_id=<?= $due['id'] ?>" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                                <i class="fas fa-trash-alt mr-2"></i>Delete
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="mt-10 text-center">
            <a href="index.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
</body>
</html>