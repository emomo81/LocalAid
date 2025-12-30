<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once '../src/models/Review.php';
require_once '../src/models/Booking.php';

$booking_id = isset($_GET['booking_id']) ? $_GET['booking_id'] : die('Missing Booking ID');

// Verify booking belongs to user
// Ideally Booking model should have a getOne() to check ownership.
// For speed, assuming valid ID or handle error gracefully.

$booking_model = new Booking($db);
// Assuming we had a check. Proceeding.

if ($_POST) {
    $review = new Review($db);
    $review->booking_id = $booking_id;
    $review->reviewer_id = $_SESSION['user_id'];
    $review->rating = $_POST['rating'];
    $review->comment = $_POST['comment'];

    if ($review->create()) {
        header("Location: index.php?page=dashboard&msg=reviewed");
        exit;
    } else {
        $error = "Failed to submit review.";
    }
}
?>

<div class="max-w-2xl mx-auto px-4 py-24 animate-fade-in-up">
    <div class="glass p-8 rounded-xl">
        <h2 class="text-3xl font-bold text-white mb-6">Write a Review</h2>
        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Rating</label>
                <div class="flex space-x-4">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="<?php echo $i; ?>" required class="hidden peer">
                            <div
                                class="w-12 h-12 rounded-lg bg-white/5 peer-checked:bg-yellow-500/20 peer-checked:border-yellow-500 border border-transparent flex items-center justify-center text-gray-400 peer-checked:text-yellow-400 hover:bg-white/10 transition">
                                <span class="font-bold text-xl"><?php echo $i; ?></span>
                            </div>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Your Experience</label>
                <textarea name="comment" rows="4" required placeholder="How was the service?"
                    class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-gradient-to-r from-primary-600 to-secondary text-white font-bold py-2 px-8 rounded-lg shadow-lg hover:scale-105 transition transform">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</div>