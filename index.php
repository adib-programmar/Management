<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="bg-gray-900 text-white">
    <?php include 'includes/navbar.php'; ?>
    <div class="container mx-auto mt-5">
        <h1 class="text-4xl text-center">Welcome to Student Management System</h1>
        <div class="flex justify-center mt-5">
            <a href="login.php?role=admin" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-2">Admin Login</a>
            <a href="login.php?role=founder" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded m-2">Founder Login</a>
            <a href="login.php?role=student" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded m-2">Student Login</a>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
