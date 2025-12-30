<?php
require_once __DIR__ . '/../config/database.php';

try {
    // 1. Connect to MySQL Server to create the DB
    $host = "localhost";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$host", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS localaid_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "Database 'localaid_db' checked/created successfully.<br>";
    } catch (PDOException $e) {
        die("DB Creation Error: " . $e->getMessage());
    }

    $database = new Database();
    $db = $database->getConnection();

    // Read Schema
    $sql = file_get_contents(__DIR__ . '/../src/sql/schema.sql');

    // Execute Schema (Split by semicolon to execute one by one if needed, but PDO might handle it)
    $db->exec($sql);

    // Manual Migrations for Profile
    try {
        $db->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(20)");
    } catch (Exception $e) {
    }
    try {
        $db->exec("ALTER TABLE users ADD COLUMN bio TEXT");
    } catch (Exception $e) {
    }
    try {
        $db->exec("ALTER TABLE users ADD COLUMN location VARCHAR(100)");
    } catch (Exception $e) {
    }
    try {
        $db->exec("ALTER TABLE users ADD COLUMN avatar_url VARCHAR(255)");
    } catch (Exception $e) {
    }
    try {
        $db->exec("ALTER TABLE services ADD COLUMN image_url VARCHAR(255)");
    } catch (Exception $e) {
    }

    echo "Tables created/updated successfully.<br>";

    // Seed Categories
    $categories = [
        [
            'name' => 'Home Cleaning',
            'slug' => 'home-cleaning',
            'icon_class' => 'fa-solid fa-broom',
            'color_class' => 'text-teal-400',
            'bg_color_class' => 'bg-teal-500/20 group-hover:bg-teal-500',
            'description' => 'Spotless cleaning for every room.'
        ],
        [
            'name' => 'Cooking',
            'slug' => 'cooking',
            'icon_class' => 'fa-solid fa-utensils',
            'color_class' => 'text-orange-400',
            'bg_color_class' => 'bg-orange-500/20 group-hover:bg-orange-500',
            'description' => 'Delicious home-cooked meals.'
        ],
        [
            'name' => 'Laundry',
            'slug' => 'laundry',
            'icon_class' => 'fa-solid fa-shirt',
            'color_class' => 'text-blue-400',
            'bg_color_class' => 'bg-blue-500/20 group-hover:bg-blue-500',
            'description' => 'Washing, folding, and ironing.'
        ],
        [
            'name' => 'Plumbing',
            'slug' => 'plumbing',
            'icon_class' => 'fa-solid fa-faucet-drip',
            'color_class' => 'text-cyan-400',
            'bg_color_class' => 'bg-cyan-500/20 group-hover:bg-cyan-500',
            'description' => 'Fix leaks and pipe issues.'
        ]
    ];

    $stmt = $db->prepare("INSERT IGNORE INTO categories (name, slug, icon_class, color_class, bg_color_class, description) VALUES (:name, :slug, :icon_class, :color_class, :bg_color_class, :description)");

    foreach ($categories as $cat) {
        $stmt->execute($cat);
    }
    echo "Categories seeded successfully.<br>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>