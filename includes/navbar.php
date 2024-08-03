<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a class="text-white font-bold text-xl" href="#">Student Management</a>
        <div class="flex space-x-4">
            <?php if (isset($_SESSION['role'])): ?>
                <a href="../logout.php" class="text-white">Logout</a>
                <a href="index.php" class="text-white">Dashboard</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
