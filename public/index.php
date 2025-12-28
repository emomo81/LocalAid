<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalAid - Premium Home Services</title>
    <link href="css/style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom gradient background */
        body {
            background-color: #EEF2FF;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            background-attachment: fixed;
            min-height: 100vh;
        }
    </style>
</head>
<body class="text-white">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center glass rounded-full px-6 py-3">
            <a href="#" class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary-500 to-secondary">LocalAid</a>
            <div class="hidden md:flex space-x-8 text-sm font-medium text-gray-200">
                <a href="#" class="hover:text-white transition">Home</a>
                <a href="#services" class="hover:text-white transition">Services</a>
                <a href="#" class="hover:text-white transition">Become a Pro</a>
                <a href="#" class="hover:text-white transition">Login</a>
            </div>
            <a href="#" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-full text-sm font-semibold transition shadow-lg shadow-primary-500/30">Get Started</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight mb-6 leading-tight">
                Your Home, <br/>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-teal-300 to-blue-500">Perfectly Cared For.</span>
            </h1>
            <p class="mt-4 text-xl text-gray-300 max-w-2xl mx-auto mb-10">
                Connect with top-rated local professionals for cleaning, cooking, laundry, and more. Trusted service at your fingertips.
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-3xl mx-auto glass p-2 rounded-full flex items-center shadow-2xl">
                <div class="flex-grow flex items-center px-4">
                    <i class="fa-solid fa-location-dot text-gray-400 mr-3"></i>
                    <input type="text" placeholder="Location" class="bg-transparent border-none focus:ring-0 text-white placeholder-gray-400 w-full">
                </div>
                <div class="h-8 w-px bg-gray-600 mx-2"></div>
                <div class="flex-grow flex items-center px-4">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 mr-3"></i>
                    <input type="text" placeholder="What help do you need?" class="bg-transparent border-none focus:ring-0 text-white placeholder-gray-400 w-full">
                </div>
                <button class="bg-gradient-to-r from-primary-600 to-secondary text-white rounded-full h-12 w-12 flex items-center justify-center hover:scale-105 transition transform shadow-lg">
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
                <!-- Service Card 1 -->
                <div class="glass p-6 rounded-2xl hover:bg-white/10 transition cursor-pointer group">
                    <div class="bg-teal-500/20 w-14 h-14 rounded-full flex items-center justify-center mb-4 group-hover:bg-teal-500 transition">
                        <i class="fa-solid fa-broom text-teal-400 text-2xl group-hover:text-white transition"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Home Cleaning</h3>
                    <p class="text-sm text-gray-400">Spotless cleaning for every room.</p>
                </div>

                <!-- Service Card 2 -->
                <div class="glass p-6 rounded-2xl hover:bg-white/10 transition cursor-pointer group">
                    <div class="bg-orange-500/20 w-14 h-14 rounded-full flex items-center justify-center mb-4 group-hover:bg-orange-500 transition">
                        <i class="fa-solid fa-utensils text-orange-400 text-2xl group-hover:text-white transition"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Cooking</h3>
                    <p class="text-sm text-gray-400">Delicious home-cooked meals.</p>
                </div>

                <!-- Service Card 3 -->
                <div class="glass p-6 rounded-2xl hover:bg-white/10 transition cursor-pointer group">
                    <div class="bg-blue-500/20 w-14 h-14 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-500 transition">
                        <i class="fa-solid fa-shirt text-blue-400 text-2xl group-hover:text-white transition"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Laundry</h3>
                    <p class="text-sm text-gray-400">Washing, folding, and ironing.</p>
                </div>

                <!-- Service Card 4 -->
                <div class="glass p-6 rounded-2xl hover:bg-white/10 transition cursor-pointer group">
                    <div class="bg-purple-500/20 w-14 h-14 rounded-full flex items-center justify-center mb-4 group-hover:bg-purple-500 transition">
                        <i class="fa-solid fa-users text-purple-400 text-2xl group-hover:text-white transition"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">More Services</h3>
                    <p class="text-sm text-gray-400">Explore all available professionals.</p>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
