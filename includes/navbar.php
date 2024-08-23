<nav class="bg-gradient-to-r from-blue-900 to-purple-900 shadow-lg p-4 ">
    <div class="container mx-auto flex justify-between items-center">
        <a class="text-white font-bold text-xl" href="#">Student Management</a>
        <div class="flex space-x-4">
            <?php if (isset($_SESSION['role'])): ?>
                <a href="../logout.php" class="text-white hover:text-gray-400">Logout</a>
                <a href="index.php" class="text-white hover:text-gray-400">Dashboard</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
