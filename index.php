<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap');

        body {
            font-family: "Hind Siliguri", sans-serif;
            background-image: url('https://i.ytimg.com/vi/B0_0J9Qfg5k/maxresdefault.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="text-white min-h-screen flex flex-col">
    <?php include 'includes/navbar.php'; ?>

    <div class="container mx-auto mt-10 flex-grow flex flex-col items-center justify-center px-4">
        <div class="bg-black bg-opacity-70 p-8 rounded-lg shadow-2xl backdrop-filter backdrop-blur-lg">
            <h1 class="text-yellow-400 text-4xl md:text-5xl lg:text-6xl font-bold text-center mb-8 animate-pulse">
                ছাত্র ম্যানেজেন্ট সিস্টেমে স্বাগতম
            </h1>
            <div class="flex flex-col sm:flex-row justify-center mt-8 space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="login.php?role=admin" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">
                    Admin Login
                </a>
                <a href="login.php?role=founder" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">
                    Founder Login
                </a>
                <a href="login.php?role=student" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-lg">
                    Student Login
                </a>
            </div>
        </div>
    </div>

    <footer class="bg-black bg-opacity-70 text-white text-center p-6 mt-10 shadow-inner">
        <p class="text-sm">&copy; 2024 Student Management System</p>
        <p class="text-xl text-yellow-500 mt-2">
            এই ওয়েবসাইটটি তৈরি করেছে 
            <a href="https://github.com/adib-programmar/" class="hover:text-yellow-400 transition duration-300">
                <i class="fab fa-github mr-1"></i>আদিব আহনাফ আজাদ - ডেভেলপার
            </a>
        </p>
    </footer>

    <script src="https://kit.fontawesome.com/21ad1a0bda.js" crossorigin="anonymous"></script>
</body>
</html>