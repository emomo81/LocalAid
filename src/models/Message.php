<?php
class Message
{
    private $conn;
    private $table_name = "messages";

    public $id;
    public $sender_id;
    public $receiver_id;
    public $message;
    public $is_read;
    public $created_at;

    public $sender_name;
    public $sender_avatar;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function send()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                (sender_id, receiver_id, message) 
                VALUES (:sender_id, :receiver_id, :message)";

        $stmt = $this->conn->prepare($query);

        $this->message = htmlspecialchars(strip_tags($this->message));

        $stmt->bindParam(":sender_id", $this->sender_id);
        $stmt->bindParam(":receiver_id", $this->receiver_id);
        $stmt->bindParam(":message", $this->message);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get conversation between two users
    public function getConversation($user1, $user2)
    {
        $query = "SELECT 
                    m.*, 
                    u.username as sender_name, u.avatar_url as sender_avatar
                  FROM " . $this->table_name . " m
                  JOIN users u ON m.sender_id = u.id
                  WHERE (m.sender_id = :u1 AND m.receiver_id = :u2) 
                     OR (m.sender_id = :u2 AND m.receiver_id = :u1)
                  ORDER BY m.created_at ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":u1", $user1);
        $stmt->bindParam(":u2", $user2);
        $stmt->execute();
        return $stmt;
    }

    // Get list of people user has chatted with (Inbox logic)
    // This is a bit complex in raw SQL, simplifying to just get distinct senders.
    public function getInbox($user_id)
    {
        $query = "SELECT DISTINCT 
                    CASE 
                        WHEN sender_id = :uid THEN receiver_id 
                        ELSE sender_id 
                    END as chat_partner_id,
                    u.username as partner_name, u.avatar_url as partner_avatar
                  FROM " . $this->table_name . " m
                  JOIN users u ON (CASE WHEN sender_id = :uid THEN receiver_id ELSE sender_id END) = u.id
                  WHERE sender_id = :uid OR receiver_id = :uid";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        return $stmt;
    }
}
?>