<!-- Hero Section -->
<header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
    <!-- Animated Background Blobs -->
    <div
        class="absolute top-0 left-1/2 w-96 h-96 bg-cyan-500/30 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob -translate-x-1/2">
    </div>
    <div
        class="absolute top-0 right-1/4 w-96 h-96 bg-purple-500/30 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
    </div>
    <div
        class="absolute -bottom-32 left-1/4 w-96 h-96 bg-blue-500/30 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-fade-in">
        <h1 class="text-5xl sm:text-7xl font-extrabold tracking-tight mb-8 leading-tight font-display">
            Liberia's #1 Home Services <br />
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500 drop-shadow-sm">Fast,
                Reliable, &
                Local.</span>
        </h1>
        <p class="mt-4 text-xl sm:text-2xl text-gray-300 max-w-2xl mx-auto mb-12 font-light">
            Connect with trusted professionals in Monrovia, Paynesville, and beyond.
        </p>

        <!-- Search Bar -->
        <form action="index.php" method="GET"
            class="max-w-3xl mx-auto glass p-2 rounded-full flex items-center shadow-2xl border border-white/20 backdrop-blur-xl relative overflow-hidden group hover:border-white/40 transition">

            <div
                class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition duration-500">
            </div>

            <input type="hidden" name="page" value="services">
            <div class="flex-grow flex items-center px-6 border-r border-white/10 z-10">
                <i class="fa-solid fa-location-dot text-cyan-400 mr-3 text-lg"></i>
                <select name="location"
                    class="bg-transparent border-none focus:ring-0 text-white placeholder-gray-400 w-full cursor-pointer font-medium appearance-none">
                    <option value="" class="bg-gray-900 text-gray-400">Select County</option>
                    <?php
                    $counties = [
                        'Bomi',
                        'Bong',
                        'Gbarpolu',
                        'Grand Bassa',
                        'Grand Cape Mount',
                        'Grand Gedeh',
                        'Grand Kru',
                        'Lofa',
                        'Margibi',
                        'Maryland',
                        'Montserrado',
                        'Nimba',
                        'River Cess',
                        'River Gee',
                        'Sinoe'
                    ];
                    foreach ($counties as $c) {
                        echo "<option value='$c' class='bg-gray-900'>$c</option>";
                    }
                    ?>
                </select>
                <i class="fa-solid fa-chevron-down text-gray-500 text-xs ml-2"></i>
            </div>

            <div class="flex-grow flex items-center px-6 z-10">
                <i class="fa-solid fa-magnifying-glass text-cyan-400 mr-3 text-lg"></i>
                <input type="text" name="q" placeholder="What help do you need?"
                    class="bg-transparent border-none focus:ring-0 text-white placeholder-gray-400 w-full font-medium">
            </div>
            <button type="submit"
                class="z-10 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white rounded-full h-14 w-14 flex items-center justify-center shadow-lg shadow-cyan-500/30 transition transform hover:scale-105">
                <i class="fa-solid fa-arrow-right text-xl"></i>
            </button>
        </form>
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
                    <a href="index.php?page=services&q=<?php echo urlencode($row['name']); ?>"
                        class="glass p-6 rounded-2xl hover:bg-white/10 transition cursor-pointer group block">
                        <div
                            class="<?php echo $row['bg_color_class']; ?> w-14 h-14 rounded-full flex items-center justify-center mb-4 transition">
                            <i
                                class="<?php echo $row['icon_class']; ?> <?php echo $row['color_class']; ?> text-2xl group-hover:text-white transition"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p class="text-sm text-gray-400"><?php echo htmlspecialchars($row['description']); ?></p>
                    </a>
                    <?php
                }
            } else {
                echo "<p class='text-red-500'>Database not initialized properly.</p>";
            }
            ?>

            <!-- More Services Link -->
            <a href="index.php?page=services"
                class="glass p-6 rounded-2xl hover:bg-white/10 transition cursor-pointer group flex flex-col items-center justify-center text-center">
                <div
                    class="bg-gray-700/50 w-14 h-14 rounded-full flex items-center justify-center mb-4 group-hover:bg-gray-600 transition">
                    <i class="fa-solid fa-arrow-right text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">View All</h3>
                <p class="text-sm text-gray-400">Explore all 20+ services</p>
            </a>
        </div>
    </div>
</section>