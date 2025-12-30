<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    $sql = "ALTER TABLE services 
            ADD COLUMN latitude DECIMAL(10, 8) NULL, 
            ADD COLUMN longitude DECIMAL(11, 8) NULL";

    $db->exec($sql);
    echo "Successfully added latitude and longitude columns to services table.\n";
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
?>