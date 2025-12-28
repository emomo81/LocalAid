<?php
class Category
{
    private $conn;
    private $table_name = "categories";

    public $id;
    public $name;
    public $slug;
    public $icon_class;
    public $color_class;
    public $bg_color_class;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>