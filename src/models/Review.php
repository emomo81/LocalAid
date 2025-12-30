<?php
class Review
{
    private $conn;
    private $table_name = "reviews";

    public $id;
    public $booking_id;
    public $reviewer_id;
    public $rating;
    public $comment;
    public $created_at;

    // Joined Fields
    public $reviewer_name;
    public $reviewer_avatar;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                (booking_id, reviewer_id, rating, comment) 
                VALUES (:booking_id, :reviewer_id, :rating, :comment)";

        $stmt = $this->conn->prepare($query);

        $this->comment = htmlspecialchars(strip_tags($this->comment));

        $stmt->bindParam(":booking_id", $this->booking_id);
        $stmt->bindParam(":reviewer_id", $this->reviewer_id);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":comment", $this->comment);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get Reviews for a particular service 
    // Technically reviews are linked to bookings, which are linked to services.
    public function getByService($service_id)
    {
        $query = "SELECT 
                    r.rating, r.comment, r.created_at,
                    u.username as reviewer_name, u.avatar_url as reviewer_avatar
                  FROM " . $this->table_name . " r
                  JOIN bookings b ON r.booking_id = b.id
                  JOIN users u ON r.reviewer_id = u.id
                  WHERE b.service_id = ?
                  ORDER BY r.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $service_id);
        $stmt->execute();
        return $stmt;
    }

    // Get average rating for a service
    public function getAverageRating($service_id)
    {
        $query = "SELECT AVG(r.rating) as avg_rating, COUNT(r.id) as count 
                  FROM " . $this->table_name . " r
                  JOIN bookings b ON r.booking_id = b.id
                  WHERE b.service_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $service_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>