<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once '../src/models/Service.php';
require_once '../src/models/Booking.php';

$service_id = isset($_GET['service_id']) ? $_GET['service_id'] : die('ERROR: Missing Service ID.');

// Get Service Details
$service = new Service($db);
// For simplicity, we really need a readOne() here, but I'll write a query inline or add logic.
// Let's add readOne to Service model quickly or query raw. To keep it clean, assume we have basic data or fetch it.
// I will just re-use readAll but filter in loop? No inefficient.
// Let's just do a direct query here for simplicity or assume passed data.
// Better: Add readOne() to Service.php later. For now, let's query raw for this view.
$query = "SELECT s.*, u.username as provider_name FROM services s JOIN users u ON s.provider_id = u.id WHERE s.id = ? LIMIT 0,1";
$stmt = $db->prepare($query);
$stmt->bindParam(1, $service_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Service not found.");
}

// Handle Post
if ($_POST) {
    $booking = new Booking($db);
    $booking->customer_id = $_SESSION['user_id'];
    $booking->provider_id = $row['provider_id'];
    $booking->service_id = $service_id;
    $booking->booking_date = $_POST['date'] . ' ' . $_POST['time'];
    $booking->notes = $_POST['notes'];

    if ($booking->create()) {
        /* echo "<script>alert('Booking Requested!'); window.location.href='index.php?page=dashboard';</script>"; */
        header("Location: index.php?page=dashboard&msg=booked");
        exit;
    } else {
        $error = "Unable to book service.";
    }
}
?>

<div class="max-w-3xl mx-auto px-4 py-24">
    <div class="glass p-8 rounded-xl shadow-2xl animate-fade-in-up">
        <h2 class="text-3xl font-bold text-white mb-6">Confirm Booking</h2>

        <div class="bg-white/5 p-6 rounded-lg mb-8 border border-white/10">
            <h3 class="text-xl font-semibold text-teal-400"><?php echo htmlspecialchars($row['title']); ?></h3>
            <p class="text-gray-300 mt-2">Provider: <span
                    class="text-white font-medium"><?php echo htmlspecialchars($row['provider_name']); ?></span></p>
            <p class="text-gray-300">Price: <span
                    class="text-white font-medium">$<?php echo htmlspecialchars($row['price']); ?></span></p>
        </div>

        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Date</label>
                    <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>"
                        class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Time</label>
                    <input type="time" name="time" required
                        class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Special Instructions / Location
                    details</label>
                <textarea name="notes" rows="4" placeholder="Please come to the back gate. The address is..."
                    class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-4">
                <a href="index.php?page=services" class="text-gray-400 hover:text-white transition">Cancel</a>
                <button type="submit"
                    class="bg-gradient-to-r from-primary-600 to-secondary text-white font-bold py-2 px-8 rounded-lg shadow-lg hover:scale-105 transition transform">
                    Confirm Booking
                </button>
            </div>
        </form>
    </div>
</div>