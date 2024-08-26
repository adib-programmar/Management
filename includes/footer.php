<!-- Tailwind CSS (assumed included in the project) -->
<footer class="bg-gradient-to-r from-purple-800 to-indigo-900 text-white relative overflow-hidden">
  <!-- Glowing Radial Background -->
  <div class="absolute inset-0 bg-gradient-radial from-lime-400/20 via-transparent to-transparent opacity-30 transform-gpu blur-2xl scale-150"></div>
  
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Branding Section -->
    <div class="text-center space-y-6">
      <h3 class="text-4xl font-extrabold tracking-widest text-lime-400 drop-shadow-lg">
        Student Management System
      </h3>
      <p class="text-gray-300">&copy; 2024 All rights reserved</p>
    </div>

    <!-- Developer Credit -->
    <div class="mt-12">
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
