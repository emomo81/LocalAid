<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalAid - Premium Home Services</title>
    <!-- Use base path for CSS to work in sub-routes like /login -->
    <base href="/localaid/LocalAid/public/">
    <link href="css/style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            /* Modern Deep Slate Theme */
            background-color: #0f172a;
            /* slate-900 */
            background-image:
                radial-gradient(at 0% 0%, hsla(222, 47%, 11%, 1) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(215, 27%, 15%, 1) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(222, 47%, 13%, 1) 0, transparent 50%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Modern Glass Effect */
        .glass {
            background: rgba(30, 41, 59, 0.4);
            /* slate-800 with opacity */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Scrollbar for Webkit */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>

<body class="text-white flex flex-col min-h-screen">

    <!-- Navigation -->
    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center glass rounded-full px-6 py-3 relative">
            <a href="index.php"
                class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary-500 to-secondary hover:opacity-80 transition">LocalAid</a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8 text-sm font-medium text-gray-200">
                <a href="index.php" class="hover:text-white transition">Home</a>
                <a href="index.php?page=services" class="hover:text-white transition">Services</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php?page=dashboard" class="hover:text-white transition">Dashboard</a>
                    <a href="index.php?page=chat" class="hover:text-white transition"><i
                            class="fa-regular fa-comments"></i></a>

                    <!-- Notification Bell -->
                    <?php
                    // Fetch Notifications
                    require_once(__DIR__ . '/../../src/models/Notification.php');
                    // Assume $db connection is available globally or creating new
                    // In typical MVC this is passed to view, but for header include we might need to lazy load if not set
                    if (!isset($db)) {
                        require_once(__DIR__ . '/../../config/database.php');
                        $database = new Database();
                        $db = $database->getConnection();
                    }

                    $notifModel = new Notification($db);
                    $unreadCount = $notifModel->getUnreadCount($_SESSION['user_id']);
                    $notifications = $notifModel->getUserNotifications($_SESSION['user_id'], 5);
                    ?>
                    <div class="relative group">
                        <button class="hover:text-white transition relative">
                            <i class="fa-regular fa-bell"></i>
                            <?php if ($unreadCount > 0): ?>
                                <span
                                    class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                    <?php echo $unreadCount; ?>
                                </span>
                            <?php endif; ?>
                        </button>

                        <!-- Dropdown -->
                        <div
                            class="absolute right-0 mt-2 w-64 glass rounded-xl shadow-xl hidden group-hover:block overflow-hidden z-50">
                            <div class="p-3 border-b border-gray-700 font-semibold text-sm flex justify-between">
                                <span>Notifications</span>
                                <!--<a href="#" class="text-xs text-primary-400">Mark all read</a>-->
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <?php if ($notifications->rowCount() > 0): ?>
                                    <?php while ($notif = $notifications->fetch(PDO::FETCH_ASSOC)): ?>
                                        <a href="<?php echo $notif['link']; ?>"
                                            class="block p-3 hover:bg-white/5 border-b border-gray-700/50 transition <?php echo !$notif['is_read'] ? 'bg-primary-900/20' : ''; ?>">
                                            <p class="text-sm text-gray-200"><?php echo htmlspecialchars($notif['message']); ?></p>
                                            <span
                                                class="text-xs text-gray-500"><?php echo date('M j, g:i a', strtotime($notif['created_at'])); ?></span>
                                        </a>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="p-4 text-center text-gray-500 text-sm">No new notifications</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="index.php?page=admin" class="hover:text-amber-400 transition">Admin</a>
                    <?php endif; ?>
                    <span class="text-teal-400">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="index.php?page=logout" class="hover:text-red-400 transition">Logout</a>
                <?php else: ?>
                    <a href="index.php?page=register" class="hover:text-white transition">Become a Pro</a>
                    <a href="index.php?page=login" class="hover:text-white transition">Login</a>
                <?php endif; ?>
            </div>

            <div class="hidden md:block">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="index.php?page=register"
                        class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-full text-sm font-semibold transition shadow-lg shadow-primary-500/30">Get
                        Started</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-white focus:outline-none">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu"
            class="hidden absolute top-20 left-4 right-4 glass rounded-2xl p-6 flex flex-col space-y-4 md:hidden animate-fade-in-up">
            <a href="index.php" class="text-gray-200 hover:text-white transition">Home</a>
            <a href="index.php#services" class="text-gray-200 hover:text-white transition">Services</a>
            <hr class="border-gray-600">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="text-teal-400">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="index.php?page=logout" class="text-red-400 transition">Logout</a>
            <?php else: ?>
                <a href="index.php?page=login" class="text-gray-200 hover:text-white transition">Login</a>
                <a href="index.php?page=register"
                    class="bg-primary-600 text-center py-2 rounded-full text-white font-semibold shadow-lg">Get Started</a>
            <?php endif; ?>
        </div>
    </nav>

    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>

    <main class="flex-grow">