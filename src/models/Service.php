<?php
class Service
{
    private $conn;
    private $table_name = "services";

    public $id;
    public $provider_id;
    public $category_id;
    public $title;
    public $description;
    public $price;
    public $location;
    public $created_at;

    // Joined fields
    public $category_name;
    public $provider_name;
    public $category_icon;
    public $category_color;
    public $category_bg;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create new service
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                (provider_id, category_id, title, description, price, location) 
                VALUES (:provider_id, :category_id, :title, :description, :price, :location)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->location = htmlspecialchars(strip_tags($this->location));

        // Bind
        $stmt->bindParam(":provider_id", $this->provider_id);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":location", $this->location);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all services with category and provider details
    public function readAll()
    {
        $query = "SELECT 
                    s.id, s.title, s.description, s.price, s.location, s.created_at,
                    c.name as category_name, c.icon_class as category_icon, c.color_class as category_color, c.bg_color_class as category_bg,
                    u.username as provider_name
                  FROM " . $this->table_name . " s
                  LEFT JOIN categories c ON s.category_id = c.id
                  LEFT JOIN users u ON s.provider_id = u.id
                  ORDER BY s.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read services by provider
    public function readByProvider($provider_id)
    {
        $query = "SELECT 
                    s.id, s.title, s.description, s.price, s.location, s.created_at,
                    c.name as category_name
                  FROM " . $this->table_name . " s
                  LEFT JOIN categories c ON s.category_id = c.id
                  WHERE s.provider_id = ?
                  ORDER BY s.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $provider_id);
        $stmt->execute();
        return $stmt;
    }
}
?>