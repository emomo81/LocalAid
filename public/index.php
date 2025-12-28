<?php
// Start Session
session_start();

// Configuration & Database
require_once '../config/database.php';
require_once '../src/models/Category.php';
// Will add User model later
// require_once '../src/models/User.php';

// Connect to DB
// We use a try-catch block here to ensure we don't crash if DB is not setup
try {
    $database = new Database();
    $db = $database->getConnection();
} catch (Exception $e) {
    $db = null;
    $error_msg = "Database connection failed. Please run setup.";
}

// Router Logic
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Include Header
include '../views/layouts/header.php';

// Route to Views
switch ($page) {
    case 'home':
        include '../views/home.php';
        break;

    case 'login':
        include '../views/auth/login.php';
        break;

    case 'register':
        include '../views/auth/register.php';
        break;

    case 'dashboard':
        // Role check
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        if (isset($_SESSION['role']) && $_SESSION['role'] === 'provider') {
            include '../views/dashboard/provider.php';
        } else {
            // For now customer dashboard is basic or just profile
            // include '../views/dashboard/customer.php'; 
            echo "<div class='container mx-auto mt-20 text-center text-white'>Customer Dashboard Coming Soon! <br> <a href='index.php' class='underline'>Go Home</a></div>";
        }
        break;

    case 'logout':
        // Handle logout inline or separate controller
        session_destroy();
        header("Location: index.php");
        exit;
        break;

    default:
        echo "<div class='container mx-auto mt-20 text-center text-red-500 text-3xl'>404 Page Not Found</div>";
        break;
}

// Include Footer
include '../views/layouts/footer.php';
?>