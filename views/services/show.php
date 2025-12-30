<?php
require_once '../src/models/Service.php';
require_once '../src/models/Review.php';

$service_id = isset($_GET['service_id']) ? $_GET['service_id'] : die('Missing Service ID');

$service_model = new Service($db);
// For simplicity using raw query as readOne not implemented fully
$query = "SELECT s.*, u.username as provider_name, u.avatar_url as provider_avatar, c.name as category_name 
          FROM services s 
          JOIN users u ON s.provider_id = u.id 
          LEFT JOIN categories c ON s.category_id = c.id
          WHERE s.id = ? LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(1, $service_id);
$stmt->execute();
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service)
    die('Service not found');

// Fetch Reviews
$review_model = new Review($db);
$reviews_stmt = $review_model->getByService($service_id);
$avg_rating = $review_model->getAverageRating($service_id);

?>

<div class="max-w-7xl mx-auto px-4 py-24 animate-fade-in">
    <!-- Service Header -->
    <div class="glass rounded-xl overflow-hidden mb-8">
        <div class="h-64 bg-gray-800 relative z-0">
            <?php if (!empty($service['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($service['image_url']); ?>"
                    class="w-full h-full object-cover opacity-70">
            <?php else: ?>
                <div class="w-full h-full bg-gradient-to-r from-primary-900 to-gray-900"></div>
            <?php endif; ?>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
            <div class="absolute bottom-6 left-6 z-10">
                <span
                    class="px-3 py-1 bg-teal-500 text-white rounded-full text-xs font-bold uppercase tracking-wider mb-2 inline-block"><?php echo htmlspecialchars($service['category_name']); ?></span>
                <h1 class="text-4xl font-extrabold text-white"><?php echo htmlspecialchars($service['title']); ?></h1>
                <div class="flex items-center text-gray-300 mt-2">
                    <i class="fa-solid fa-map-marker-alt mr-2 text-red-400"></i>
                    <?php echo htmlspecialchars($service['location']); ?>
                    <span class="mx-3">•</span>
                    <i class="fa-solid fa-star text-yellow-400 mr-1"></i>
                    <?php echo number_format($avg_rating['avg_rating'], 1); ?> (<?php echo $avg_rating['count']; ?>
                    reviews)
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-8">
                <div>
                    <h3 class="text-xl font-bold text-white mb-2">About this Service</h3>
                    <p class="text-gray-300 leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
                </div>

                <!-- Reviews -->
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Reviews</h3>
                    <?php if ($reviews_stmt->rowCount() > 0): ?>
                        <div class="space-y-4">
                            <?php while ($row = $reviews_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <div class="bg-white/5 p-4 rounded-lg flex space-x-4">
                                    <div class="shrink-0">
                                        <?php if ($row['reviewer_avatar']): ?>
                                            <img src="<?php echo htmlspecialchars($row['reviewer_avatar']); ?>"
                                                class="w-10 h-10 rounded-full object-cover">
                                        <?php else: ?>
                                            <div
                                                class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center font-bold text-white">
                                                <?php echo substr($row['reviewer_name'], 0, 1); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-white font-bold text-sm">
                                                <?php echo htmlspecialchars($row['reviewer_name']); ?></h4>
                                            <span
                                                class="text-xs text-gray-500"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                                        </div>
                                        <div class="flex text-yellow-400 text-xs mb-1">
                                            <?php for ($i = 1; $i <= 5; $i++)
                                                echo ($i <= $row['rating']) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>'; ?>
                                        </div>
                                        <p class="text-gray-300 text-sm"><?php echo htmlspecialchars($row['comment']); ?></p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 italic">No reviews yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Booking Sidebar -->
            <div class="bg-gray-800/50 p-6 rounded-xl h-fit sticky top-24 border border-white/10">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="h-16 w-16 rounded-full bg-white/10 overflow-hidden">
                        <?php if (!empty($service['provider_avatar'])): ?>
                            <img src="<?php echo $service['provider_avatar']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div
                                class="w-full h-full flex items-center justify-center text-2xl font-bold text-white border-2 border-white/20 rounded-full">
                                <?php echo strtoupper(substr($service['provider_name'], 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Service by</p>
                        <h4 class="text-lg font-bold text-white">
                            <?php echo htmlspecialchars($service['provider_name']); ?></h4>
                    </div>
                </div>

                <div class="text-3xl font-bold text-teal-400 mb-6">$<?php echo htmlspecialchars($service['price']); ?>
                    <span class="text-sm text-gray-400 font-normal">/ service</span></div>

                <a href="index.php?page=book&service_id=<?php echo $service['id']; ?>"
                    class="block w-full text-center bg-gradient-to-r from-primary-600 to-secondary hover:scale-105 transition transform text-white font-bold py-3 rounded-lg shadow-lg">
                    Book Now
                </a>
            </div>
        </div>
    </div>
</div>