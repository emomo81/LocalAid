<?php
class Booking
{
    private $conn;
    private $table_name = "bookings";

    public $id;
    public $customer_id;
    public $provider_id;
    public $service_id;
    public $booking_date;
    public $status; // pending, confirmed, completed, cancelled
    public $notes;
    public $created_at;

    // Joined Fields
    public $service_title;
    public $service_price;
    public $provider_name;
    public $customer_name;
    public $customer_email;
    public $customer_phone;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                (customer_id, provider_id, service_id, booking_date, notes) 
                VALUES (:customer_id, :provider_id, :service_id, :booking_date, :notes)";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->notes = htmlspecialchars(strip_tags($this->notes));
        $this->booking_date = htmlspecialchars(strip_tags($this->booking_date));

        // Bind
        $stmt->bindParam(":customer_id", $this->customer_id);
        $stmt->bindParam(":provider_id", $this->provider_id);
        $stmt->bindParam(":service_id", $this->service_id);
        $stmt->bindParam(":booking_date", $this->booking_date);
        $stmt->bindParam(":notes", $this->notes);

        if ($stmt->execute()) {
            // Trigger Notification for Provider
            require_once 'Notification.php';
            $notification = new Notification($this->conn);
            $msg = "You have a new booking request!";
            $link = "index.php?page=dashboard";
            $notification->create($this->provider_id, $msg, 'booking_request', $link);

            return true;
        }
        return false;
    }

    // Get Bookings for a Customer
    public function getByCustomer($user_id)
    {
        $query = "SELECT 
                    b.id, b.booking_date, b.status, b.notes, b.created_at,
                    s.title as service_title, s.price as service_price,
                    u.username as provider_name
                  FROM " . $this->table_name . " b
                  JOIN services s ON b.service_id = s.id
                  JOIN users u ON b.provider_id = u.id
                  WHERE b.customer_id = ?
                  ORDER BY b.booking_date ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    // Get Bookings for a Provider
    public function getByProvider($user_id)
    {
        $query = "SELECT 
                    b.id, b.booking_date, b.status, b.notes, b.created_at,
                    s.title as service_title, s.price as service_price,
                    u.username as customer_name, u.email as customer_email, u.phone as customer_phone
                  FROM " . $this->table_name . " b
                  JOIN services s ON b.service_id = s.id
                  JOIN users u ON b.customer_id = u.id
                  WHERE b.provider_id = ?
                  ORDER BY b.booking_date ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }

    // Update Status
    public function updateStatus($id, $status)
    {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $status = htmlspecialchars(strip_tags($status));
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            // Get booking details to notify customer
            $checkQuery = "SELECT customer_id, service_id, s.title FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.id = ?";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(1, $id);
            $checkStmt->execute();

            if ($row = $checkStmt->fetch(PDO::FETCH_ASSOC)) {
                require_once 'Notification.php';
                $notification = new Notification($this->conn);
                $msg = "Your booking for '{$row['title']}' has been " . $status . ".";
                $link = "index.php?page=dashboard"; // Or maybe specific booking view
                $notification->create($row['customer_id'], $msg, 'booking_update', $link);
            }

            return true;
        }
        return false;
    }
}
?>