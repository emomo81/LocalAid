<!-- Hero Section -->
<header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-fade-in">
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight mb-6 leading-tight">
            Liberia's #1 Home Services <br />
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-300 to-blue-500">Fast, Reliable, & Local.</span>
        </h1>
        <p class="mt-4 text-xl text-gray-300 max-w-2xl mx-auto mb-10">
            Connect with trusted professionals in Monrovia, Paynesville, and beyond. From cleaning to repairs, we've got you covered.
        </p>

        <!-- Search Bar -->
        <div class="max-w-3xl mx-auto glass p-2 rounded-full flex items-center shadow-2xl">
            <div class="flex-grow flex items-center px-4">
                <i class="fa-solid fa-location-dot text-gray-400 mr-3"></i>
                <input type="text" placeholder="e.g. Sinkor, Monrovia"
                    class="bg-transparent border-none focus:ring-0 text-white placeholder-gray-400 w-full">
            </div>
            <div class="h-8 w-px bg-gray-600 mx-2"></div>
            <div class="flex-grow flex items-center px-4">
                <i class="fa-solid fa-magnifying-glass text-gray-400 mr-3"></i>
                <input type="text" placeholder="What help do you need?"
                    class="bg-transparent border-none focus:ring-0 text-white placeholder-gray-400 w-full">
            </div>
            <button
                class="bg-gradient-to-r from-primary-600 to-secondary text-white rounded-full h-12 w-12 flex items-center justify-center hover:scale-105 transition transform shadow-lg">
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>
</header>

<!-- Services Grid -->
<section id="services" class="py-20 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold mb-4">Popular Services</h2>
            <p class="text-gray-400">Whatever you need, we've got you covered.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            // Fetch categories
            // Note: Models are already required in index.php or we can require here if needed.
            // Assuming $db is available from the router (index.php) scope.
            
            if (isset($db) && $db) {
                $category = new Category($db);
                $stmt = $category->readAll();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <!-- Service Card -->
                    <div class="glass p-6 rounded-2xl hover:bg-white/10 transition cursor-pointer group">
                        <div
                            class="<?php echo $row['bg_color_class']; ?> w-14 h-14 rounded-full flex items-center justify-center mb-4 transition">
                            <i
                                class="<?php echo $row['icon_class']; ?> <?php echo $row['color_class']; ?> text-2xl group-hover:text-white transition"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="text-sm text-gray-400"><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-red-500'>Database not initialized properly.</p>";
            }
            ?>

            <!-- More Services Link -->
            <div
                class="glass p-6 rounded-2xl hover:bg-white/10 transition cursor-pointer group flex flex-col items-center justify-center text-center">
                <div
                    class="bg-gray-700/50 w-14 h-14 rounded-full flex items-center justify-center mb-4 group-hover:bg-gray-600 transition">
                    <i class="fa-solid fa-arrow-right text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">View All</h3>
                <p class="text-sm text-gray-400">Explore all 20+ services</p>
            </div>
        </div>
    </div>
</section>