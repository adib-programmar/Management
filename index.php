<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" href="manegement/png" type="image/ico">
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

        .fade-in {
            animation: fadeIn ease 3s;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .hover-glow {
            transition: box-shadow 0.3s ease;
        }

        .hover-glow:hover {
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.8), 0 0 40px rgba(255, 255, 255, 0.6);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }
    </style>
</head>
<body class="text-white min-h-screen flex flex-col fade-in">
    <?php include 'includes/navbar.php'; ?>

    <div class="container mx-auto mt-10 flex-grow flex flex-col items-center justify-center px-4">
        <div class="bg-black bg-opacity-70 p-8 rounded-lg shadow-2xl backdrop-filter backdrop-blur-lg">
            <h1 class="text-yellow-400 text-4xl md:text-5xl lg:text-6xl font-bold text-center mb-8 floating">
                ছাত্র ম্যানেজেন্ট সিস্টেমে স্বাগতম
            </h1>
            <div class="flex flex-col sm:flex-row justify-center mt-8 space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="login.php?role=admin" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover-glow">
                    Admin Login
                </a>
                <a href="login.php?role=founder" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover-glow">
                    Founder Login
                </a>
                <a href="login.php?role=student" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105 hover-glow">
                    Student Login
                </a>
            </div>
        </div>
    </div>

    <!-- Tailwind CSS (assumed included in the project) -->
<footer class="text-white opacity-50 bg-gradient-to-r from-purple-800 to-indigo-900 text-white relative overflow-hidden" style="height: 50%;">
  <!-- Glowing Radial Background -->
  <div class="absolute inset-0 bg-gradient-radial from-lime-400/20 via-transparent to-transparent opacity-50 transform-gpu blur-2xl scale-150"></div>
  
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"> <!-- Reduced padding for height adjustment -->
    <!-- Branding Section -->
    <div class="text-center space-y-4">
      <h3 class="text-3xl font-extrabold tracking-widest text-lime-400 drop-shadow-lg">
        Student Management System
      </h3>
      <p class="text-gray-300">&copy; 2024 All rights reserved</p>
    </div>

    <!-- Developer Credit -->
    <div class="mt-6">
      <p class="text-center text-gray-300">
        এই ওয়েবসাইটটি তৈরি করেছে 
        <a href="https://github.com/adib-programmar/" class="text-lime-400 hover:text-lime-300 transition duration-300">
          আদিব আহনাফ আজাদ - ডেভেলপার
          <i class="fab fa-github ml-1 hover:animate-bounce"></i>
        </a>
      </p>
    </div>
  </div>
</footer>

<!-- External CSS -->
<style>
  footer {
    position: relative;
    overflow: hidden;

  }

  footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1), transparent);
    animation: rotate 15s linear infinite;
    z-index: 0;
  }

  @keyframes rotate {
    from {
      transform: rotate(0deg);
    }
    to {
      transform: rotate(360deg);
    }
  }

  footer h3 {
    position: relative;
    z-index: 1;
  }

  footer p, footer a {
    position: relative;
    z-index: 1;
  }
</style>


    <script src="https://kit.fontawesome.com/21ad1a0bda.js" crossorigin="anonymous"></script>
</body>
</html>
