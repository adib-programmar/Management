<?php
include '../config.php';
session_start();

// Ensure the user is a student
if ($_SESSION['role'] != 'student') {
    header("Location: ../index.php");
    exit();
}

$class_id = $_GET['class_id'];
$student_id = $_SESSION['user_id'];

// Handle file submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $due_id = $_POST['due_id'];
    $submission_id = $_POST['submission_id'];

    $target_dir = "../uploads/submissions/";
    $file_name = basename($_FILES["submission_file"]["name"]);
    $target_file = $target_dir . $file_name;
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["submission_file"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($fileType != "pdf") {
        echo "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["submission_file"]["tmp_name"], $target_file)) {
            if ($submission_id) {
                // Update existing submission
                $sql = "UPDATE submissions SET submission_file = '$file_name' WHERE id = '$submission_id'";
            } else {
                // Insert new submission
                $sql = "INSERT INTO submissions (due_id, student_id, submission_file) VALUES ('$due_id', '$student_id', '$file_name')";
            }

            if ($conn->query($sql) === TRUE) {
                echo "The file ". htmlspecialchars( basename( $_FILES["submission_file"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch all dues for the specific class
$dues = $conn->query("SELECT * FROM dues WHERE class_id='$class_id' ORDER BY due_date DESC");

// Fetch student's submissions
$submissions = $conn->query("SELECT s.*, d.due_title, d.due_date FROM submissions s JOIN dues d ON s.due_id = d.id WHERE s.student_id='$student_id' AND d.class_id='$class_id' ORDER BY d.due_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Due</title>
    <link rel="shortcut icon" href="student.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .glass-morphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .neon-text {
            text-shadow: 0 0 10px #00ffff, 0 0 20px #00ffff, 0 0 30px #00ffff, 0 0 40px #00ffff;
        }
        .hover-scale {
            transition: transform 0.3s ease-in-out;
        }
        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
    <script>
        $(document).ready(function() {
            function updateDueDetails(dueId) {
                $.ajax({
                    url: 'get_due_details.php',
                    type: 'GET',
                    data: { due_id: dueId },
                    success: function(response) {
                        var due = JSON.parse(response);
                        $("#due_title").text(due.due_title);
                        $("#due_description").text(due.due_description);
                        $("#due_attachment").attr("href", due.attachment).text(due.attachment ? "View Attachment" : "No Attachment");
                        
                        var dueDate = moment.tz(due.due_date, "YYYY-MM-DD HH:mm:ss", "Asia/Dhaka");
                        $("#due_date").text(dueDate.format('MMMM D, YYYY HH:mm:ss'));

                        function updateTimeLeft() {
                            var now = moment().tz("Asia/Dhaka");
                            var duration = moment.duration(dueDate.diff(now));
                            
                            if (duration.asSeconds() > 0) {
                                var days = Math.floor(duration.asDays());
                                var hours = duration.hours();
                                var minutes = duration.minutes();
                                var seconds = duration.seconds();
                                
                                var timeLeftText = '';
                                if (days > 0) timeLeftText += days + "d ";
                                timeLeftText += hours.toString().padStart(2, '0') + "h ";
                                timeLeftText += minutes.toString().padStart(2, '0') + "m ";
                                timeLeftText += seconds.toString().padStart(2, '0') + "s";
                                
                                $("#time_left").html('<i class="fas fa-clock"></i> ' + timeLeftText);
                                $("#submit_button").prop("disabled", false).removeClass("bg-red-500").addClass("bg-gradient-to-r from-green-400 to-blue-500");
                            } else {
                                $("#time_left").html('<i class="fas fa-times-circle"></i> Time ended');
                                $("#submit_button").prop("disabled", true).removeClass("bg-gradient-to-r from-green-400 to-blue-500").addClass("bg-red-500");
                            }
                        }

                        updateTimeLeft();
                        setInterval(updateTimeLeft, 1000);
                    }
                });
            }

            $(".view-due-btn").click(function() {
                var dueId = $(this).data("due-id");
                $("#due_id").val(dueId);
                updateDueDetails(dueId);
                $("#submission-form").removeClass("hidden");
            });

            $(".edit-submission-btn").click(function() {
                var dueId = $(this).data("due-id");
                var submissionId = $(this).data("submission-id");
                $("#due_id").val(dueId);
                $("#submission_id").val(submissionId);
                updateDueDetails(dueId);
                $("#submission-form").removeClass("hidden");
            });
        });
    </script>
</head>
<body class="text-white min-h-screen">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-10 p-6">
        <h2 class="text-5xl font-bold mb-12 text-center neon-text"><i class="fas fa-tasks mr-2"></i>Submit Due</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <?php while ($due = $dues->fetch_assoc()): ?>
                <div class="glass-morphism p-6 hover-scale">
                    <h3 class="text-2xl font-semibold mb-3 text-white"><i class="fas fa-file-alt mr-2"></i><?= htmlspecialchars($due['due_title']) ?></h3>
                    <p class="text-gray-300 mb-3"><?= htmlspecialchars(substr($due['due_description'], 0, 100)) ?>...</p>
                    <p class="text-yellow-300 mb-4"><i class="far fa-calendar-alt mr-2"></i><strong>Due Date:</strong> <?= htmlspecialchars($due['due_date']) ?></p>
                    <button class="view-due-btn w-full bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg" data-due-id="<?= $due['id'] ?>">
                        <i class="fas fa-eye mr-2"></i>View Details
                    </button>
                </div>
            <?php endwhile; ?>
        </div>

        <div id="submission-form" class="glass-morphism p-8 mb-12 hidden">
            <h3 class="text-3xl font-semibold mb-6 text-center neon-text"><i class="fas fa-info-circle mr-2"></i>Due Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="mb-3"><i class="fas fa-heading mr-2"></i><strong>Title:</strong> <span id="due_title" class="text-blue-300"></span></p>
                    <p class="mb-3"><i class="fas fa-align-left mr-2"></i><strong>Description:</strong> <span id="due_description" class="text-gray-300"></span></p>
                    <p class="mb-3"><i class="far fa-calendar-alt mr-2"></i><strong>Due Date:</strong> <span id="due_date" class="text-yellow-300"></span></p>
                </div>
                <div>
                    <p class="mb-3"><i class="fas fa-hourglass-half mr-2"></i><strong>Time Left:</strong> <span id="time_left" class="text-green-300 font-bold"></span></p>
                    <p class="mb-3"><i class="fas fa-paperclip mr-2"></i><strong>Attachment:</strong> <a id="due_attachment" class="text-blue-300 underline" target="_blank"></a></p>
                </div>
            </div>

            <form action="" method="POST" enctype="multipart/form-data" class="mt-8">
                <input type="hidden" id="due_id" name="due_id">
                <input type="hidden" id="submission_id" name="submission_id">
                <div class="mb-6">
                    <label for="submission_file" class="block text-lg font-bold mb-2"><i class="fas fa-file-pdf mr-2"></i>Upload Submission File (PDF only)</label>
                    <input type="file" class="w-full p-3 rounded-lg bg-white bg-opacity-20 focus:bg-opacity-30 focus:outline-none transition-all duration-300" id="submission_file" name="submission_file" accept=".pdf" required>
                </div>
                <button type="submit" id="submit_button" class="w-full bg-gradient-to-r from-green-400 to-blue-500 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>Submit
                </button>
            </form>
        </div>

        <div class="glass-morphism p-8">
            <h3 class="text-3xl font-semibold mb-6 text-center neon-text"><i class="fas fa-history mr-2"></i>Submission History</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white bg-opacity-10">
                            <th class="text-left py-3 px-4 rounded-tl-lg">Due Title</th>
                            <th class="text-left py-3 px-4">Submission Date</th>
                            <th class="text-left py-3 px-4 rounded-tr-lg">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($submission = $submissions->fetch_assoc()): ?>
                            <tr class="border-b border-white border-opacity-20">
                                <td class="py-3 px-4"><?= htmlspecialchars($submission['due_title']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($submission['submission_file']) ?></td>
                                <td class="py-3 px-4">
                                    <?php
                                    $due_date = new DateTime($submission['due_date'], new DateTimeZone('Asia/Dhaka'));
                                    $current_date = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
                                    if ($due_date > $current_date):
                                    ?>
                                        <button class="edit-submission-btn bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg" data-due-id="<?= $submission['due_id'] ?>" data-submission-id="<?= $submission['id'] ?>">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>