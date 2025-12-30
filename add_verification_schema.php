<?php
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

try {
    // Add verification columns to users table
    $sql = "ALTER TABLE users 
            ADD COLUMN is_verified TINYINT(1) DEFAULT 0,
            ADD COLUMN verification_status ENUM('none', 'pending', 'approved', 'rejected') DEFAULT 'none',
            ADD COLUMN verification_doc VARCHAR(255) DEFAULT NULL";

    $db->exec($sql);
    echo "Successfully added verification columns to users table.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>