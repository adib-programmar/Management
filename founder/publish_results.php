<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$founder_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exam_name = $_POST['exam_name'];
    $result_pdf = $_FILES['result_pdf']['name'];
    $file_type = strtolower(pathinfo($result_pdf, PATHINFO_EXTENSION));
    $mime_type = mime_content_type($_FILES['result_pdf']['tmp_name']);

    // Check if the uploaded file is a PDF
    if ($file_type !== 'pdf' || $mime_type !== 'application/pdf') {
        echo "<p class='bg-red-500 text-white p-4 rounded-lg'>Error: Only PDF files are allowed.</p>";
    } else {
        $target_dir = __DIR__ . "/../uploads/results/";
        $target_file = $target_dir . basename($result_pdf);

        if (move_uploaded_file($_FILES['result_pdf']['tmp_name'], $target_file)) {
            // Save the relative path to the database
            $relative_path = "uploads/results/" . basename($result_pdf);
            $sql = "INSERT INTO results (class_id, exam_name, result_pdf, uploaded_by) VALUES ('$class_id', '$exam_name', '$relative_path', '$founder_id')";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='bg-green-500 text-white p-4 rounded-lg'>Result published successfully.</p>";
            } else {
                echo "<p class='bg-red-500 text-white p-4 rounded-lg'>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        } else {
            echo "<p class='bg-red-500 text-white p-4 rounded-lg'>Error uploading the file.</p>";
        }
    }
}

$results = $conn->query("SELECT * FROM results WHERE class_id='$class_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publish Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #1a202c;
            color: #e2e8f0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.25rem;
            font-weight: bold;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
        }
        .btn-primary {
            background-color: #4299e1;
            color: white;
        }
        .btn-primary:hover {
            background-color: #3182ce;
        }
        .card {
            background-color: #2d3748;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15);
        }
        .input-field {
            background-color: #2d3748;
            color: #a0aec0;
            border: 1px solid #4a5568;
            padding: 0.75rem;
            border-radius: 0.375rem;
            transition: border-color 0.2s;
        }
        .input-field:focus {
            border-color: #4299e1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-6">
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold mb-6 text-center text-yellow-400">Publish Exam Results</h2>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="exam_name" class="block text-sm font-semibold mb-2">Exam Name</label>
                    <input type="text" id="exam_name" name="exam_name" class="input-field w-full" required>
                </div>
                <div>
                    <label for="result_pdf" class="block text-sm font-semibold mb-2">Upload Result PDF</label>
                    <input type="file" id="result_pdf" name="result_pdf" class="input-field w-full" required>
                </div>
                <button type="submit" class="btn btn-primary w-full">Publish Result</button>
            </form>
        </div>

        <div class="mt-10">
            <h3 class="text-2xl font-semibold mb-4">Published Results</h3>
            <ul class="space-y-4">
                <?php while ($result = $results->fetch_assoc()): ?>
                    <li class="card flex items-center justify-between">
                        <div>
                            <h5 class="text-lg font-bold text-yellow-300"><?= htmlspecialchars($result['exam_name']) ?></h5>
                            <a href="<?= htmlspecialchars('/Management/' . $result['result_pdf']) ?>" target="_blank" class="text-blue-400 underline hover:text-blue-600">View Result</a>
                        </div>
                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <div class="mt-10 text-center">
            <a href="index.php" class="btn bg-gray-500 hover:bg-gray-700 text-white">Back to Dashboard</a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
