<?php
include '../config.php';
session_start();

if ($_SESSION['role'] != 'founder') {
    header("Location: ../index.php");
    exit();
}

$due_id = $_GET['due_id'];

// Fetch due information
$due = $conn->query("SELECT d.*, c.class_name, c.class_code FROM dues d JOIN classes c ON d.class_id = c.id WHERE d.id='$due_id'")->fetch_assoc();

// Fetch submissions (including from students who have left)
$submissions = $conn->query("SELECT s.*, u.name, u.username, u.class_code 
    FROM submissions s 
    JOIN users u ON s.student_id = u.id 
    WHERE s.due_id='$due_id'");

// Fetch students who haven't submitted (only current class members)
$not_submitted = $conn->query("SELECT u.name, u.username 
    FROM users u 
    LEFT JOIN submissions s ON u.id = s.student_id AND s.due_id = '$due_id'
    WHERE u.role = 'student' AND u.class_code = '{$due['class_code']}'
    AND s.id IS NULL");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Submissions - <?= htmlspecialchars($due['due_title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="shortcut icon" href="founder.png" type="image/x-icon">
    <style>
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>

<body class="min-h-screen text-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto px-4 py-12">
        <div class="glass-morphism p-8 shadow-2xl mb-12 fade-in">
            <h1 class="text-4xl font-bold text-center mb-8">Submissions for <?= htmlspecialchars($due['due_title']) ?>
            </h1>
            <div class="text-center mb-8">
                <p class="text-xl"><i class="fas fa-chalkboard mr-2"></i>Class:
                    <?= htmlspecialchars($due['class_name']) ?>
                </p>
                <p class="text-xl"><i class="far fa-calendar-alt mr-2"></i>Due Date:
                    <?= htmlspecialchars($due['due_date']) ?>
                </p>
            </div>
            <form method="post" action="download_submissions.php" class="text-center">
                <input type="hidden" name="due_id" value="<?= $due_id ?>">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                    <i class="fas fa-download mr-2"></i>Download as CSV
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="glass-morphism p-8 shadow-2xl fade-in">
                <h2 class="text-3xl font-bold mb-6"><i class="fas fa-check-circle mr-2"></i>Submitted</h2>
                <ul class="space-y-4">
                    <?php while ($submission = $submissions->fetch_assoc()): ?>
                        <li class="bg-white bg-opacity-10 rounded-lg p-4 hover-scale">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold"><?= htmlspecialchars($submission['name']) ?>
                                        (<?= htmlspecialchars($submission['username']) ?>)
                                    </p>
                                    <?php if ($submission['class_code'] != $due['class_code']): ?>
                                        <p class="text-yellow-300 text-sm"><i class="fas fa-exclamation-triangle mr-1"></i>Removed/Left Class</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="<?= htmlspecialchars('../uploads/submissions/' . $submission['submission_file']) ?>"
                                class="text-blue-300 hover:text-blue-100 underline mt-2 inline-block" target="_blank">
                                <i class="fas fa-file-pdf mr-1"></i>View Submission
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <div class="glass-morphism p-8 shadow-2xl fade-in">
                <h2 class="text-3xl font-bold mb-6"><i class="fas fa-exclamation-circle mr-2"></i>Not Submitted</h2>
                <ul class="space-y-4">
                    <?php while ($student = $not_submitted->fetch_assoc()): ?>
                        <li class="bg-white bg-opacity-10 rounded-lg p-4 hover-scale">
                            <p class="font-semibold"><?= htmlspecialchars($student['name']) ?>
                                (<?= htmlspecialchars($student['username']) ?>)
                            </p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="manage_dues.php?class_id=<?= htmlspecialchars($due['class_id']) ?>"
                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-110">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dues
            </a>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
</body>

</html>