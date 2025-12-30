<?php
class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $role;

    // Profile Fields
    public $phone;
    public $bio;
    public $location;
    public $avatar_url;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Register new user
    public function register()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                (username, email, password_hash, role) 
                VALUES (:username, :email, :password_hash, :role)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role)); // Ensure role is valid enums in controller

        // Password Hash
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Bind
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password_hash", $password_hash);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Check if email exists
    public function emailExists()
    {
        $query = "SELECT id, username, password_hash, role, phone, bio, location, avatar_url FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password_hash']; // Store hash to verify later
            $this->role = $row['role'];
            $this->phone = $row['phone'];
            $this->bio = $row['bio'];
            $this->location = $row['location'];
            $this->avatar_url = $row['avatar_url'];
            return true;
        }
        return false;
    }

    // Login (Verify Password)
    // Note: Call emailExists first to populate $this->password (hash)
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    // Get Profile Data
    public function getProfile()
    {
        $query = "SELECT username, email, role, phone, bio, location, avatar_url FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update Profile
    public function updateProfile()
    {
        $query = "UPDATE " . $this->table_name . " 
                SET phone = :phone, bio = :bio, location = :location, avatar_url = :avatar_url 
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->bio = htmlspecialchars(strip_tags($this->bio));
        $this->location = htmlspecialchars(strip_tags($this->location));
        $this->avatar_url = htmlspecialchars(strip_tags($this->avatar_url));

        // Bind
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":bio", $this->bio);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":avatar_url", $this->avatar_url);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>