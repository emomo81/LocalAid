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
        $query = "SELECT id, username, password_hash, role FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password_hash']; // Store hash to verify later
            $this->role = $row['role'];
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
}
?>