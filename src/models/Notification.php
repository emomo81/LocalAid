<?php
class Notification
{
    private $conn;
    private $table_name = "notifications";

    public $id;
    public $user_id;
    public $type;
    public $message;
    public $link;
    public $is_read;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create Notification
    public function create($user_id, $message, $type = 'system', $link = '#')
    {
        $query = "INSERT INTO " . $this->table_name . " 
                (user_id, message, type, link) VALUES (:user_id, :message, :type, :link)";

        $stmt = $this->conn->prepare($query);

        $message = htmlspecialchars(strip_tags($message));
        $type = htmlspecialchars(strip_tags($type));
        $link = htmlspecialchars(strip_tags($link));

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':link', $link);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get Unread Count for User
    public function getUnreadCount($user_id)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE user_id = ? AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }

    // Get All Notifications for User
    public function getUserNotifications($user_id, $limit = 10)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Mark as Read
    public function markAsRead($id, $user_id)
    {
        $query = "UPDATE " . $this->table_name . " SET is_read = 1 WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Mark All as Read
    public function markAllRead($user_id)
    {
        $query = "UPDATE " . $this->table_name . " SET is_read = 1 WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
}
?>