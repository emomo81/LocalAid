<?php
// Ensure user is logged in and is a provider
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: index.php?page=login");
    exit;
}

require_once '../src/models/Service.php';
require_once '../src/models/Category.php';
require_once '../src/models/Booking.php';

$service = new Service($db);
$category = new Category($db);
$booking = new Booking($db);
$msg = "";

// Handle Form Submit
if ($_POST) {
    // Create Service
    if (isset($_POST['create_service'])) {
        $service->provider_id = $_SESSION['user_id'];
        $service->category_id = $_POST['category_id'];
        $service->title = $_POST['title'];
        $service->description = $_POST['description'];
        $service->price = $_POST['price'];
        $service->location = $_POST['location'];

        // Handle Image Upload
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../public/uploads/services/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            // Simple unique naming
            $filename = time() . "_" . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $filename;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $service->image_url = "uploads/services/" . $filename;
            } else {
                $service->image_url = ""; // Or handle error
            }
        } else {
            $service->image_url = "";
        }

        if ($service->create()) {
            $msg = "<div class='bg-green-500/20 text-green-100 p-3 rounded mb-4'>Service posted successfully!</div>";
        } else {
            $msg = "<div class='bg-red-500/20 text-red-100 p-3 rounded mb-4'>Unable to post service.</div>";
        }
    }

    // Update Booking Status
    if (isset($_POST['update_status'])) {
        $booking_id = $_POST['booking_id'];
        $status = $_POST['status'];
        if ($booking->updateStatus($booking_id, $status)) {
            $msg = "<div class='bg-blue-500/20 text-blue-100 p-3 rounded mb-4'>Booking updated to " . ucfirst($status) . "!</div>";
        }
    }
}

// Fetch my services
$my_services = $service->readByProvider($_SESSION['user_id']);

// Fetch incoming bookings
$incoming_requests = $booking->getByProvider($_SESSION['user_id']);

// Fetch categories for dropdown

// Fetch categories for dropdown
$categories_stmt = $category->readAll();
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Sidebar / Stats (Placeholder) -->
        <div class="lg:col-span-1">
            <div class="glass rounded-xl p-6">
                <div class="flex items-center space-x-4 mb-6">
                    <div
                        class="h-16 w-16 bg-gradient-to-br from-primary-500 to-secondary rounded-full flex items-center justify-center text-2xl font-bold">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white"><?php echo htmlspecialchars($_SESSION['username']); ?>
                        </h2>
                        <span class="px-2 py-1 bg-teal-500/20 text-teal-300 text-xs rounded-full">Pro Provider</span>
                    </div>
                </div>
                <hr class="border-gray-600 mb-4">
                <nav class="space-y-2">
                    <a href="#" class="block px-4 py-2 bg-white/10 text-white rounded-lg font-medium">My Services</a>
                    <a href="#"
                        class="block px-4 py-2 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition">Orders
                        / Requests</a>
                    <a href="#"
                        class="block px-4 py-2 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition">Settings</a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">

            <?php echo $msg; ?>

            <!-- Incoming Requests -->
            <div class="glass rounded-xl p-8 mb-8 animate-fade-in">
                <h3 class="text-2xl font-bold mb-6 text-white flex items-center">
                    <i class="fa-solid fa-inbox text-teal-400 mr-2"></i> Incoming Bookings
                </h3>
                <div class="space-y-4">
                    <?php if ($incoming_requests->rowCount() > 0): ?>
                        <?php while ($req = $incoming_requests->fetch(PDO::FETCH_ASSOC)): ?>
                            <div
                                class="bg-white/5 p-4 rounded-lg border border-white/10 flex flex-col md:flex-row justify-between items-start md:items-center">
                                <div class="mb-4 md:mb-0">
                                    <h4 class="text-lg font-bold text-white">
                                        <?php echo htmlspecialchars($req['service_title']); ?>
                                    </h4>
                                    <p class="text-sm text-gray-400">Customer: <span
                                            class="text-teal-300"><?php echo htmlspecialchars($req['customer_name']); ?></span>
                                    </p>
                                    <p class="text-sm text-gray-400"><i class="fa-regular fa-clock"></i>
                                        <?php echo date('M d, Y h:i A', strtotime($req['booking_date'])); ?></p>
                                    <?php if ($req['notes']): ?>
                                        <p class="text-xs text-gray-500 mt-1 italic">
                                            "<?php echo htmlspecialchars($req['notes']); ?>"</p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <?php if ($req['status'] == 'pending'): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="update_status" value="1">
                                            <input type="hidden" name="booking_id" value="<?php echo $req['id']; ?>">
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition">Accept</button>
                                        </form>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="update_status" value="1">
                                            <input type="hidden" name="booking_id" value="<?php echo $req['id']; ?>">
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition">Reject</button>
                                        </form>
                                    <?php else: ?>
                                        <?php
                                        $statusColor = 'bg-yellow-500/20 text-yellow-300';
                                        if ($req['status'] == 'confirmed')
                                            $statusColor = 'bg-green-500/20 text-green-300';
                                        if ($req['status'] == 'cancelled')
                                            $statusColor = 'bg-red-500/20 text-red-300';
                                        ?>
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold <?php echo $statusColor; ?> uppercase"><?php echo $req['status']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-500">No new booking requests.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Add Service Form -->
            <div class="glass rounded-xl p-8 animate-fade-in-up">
                <h3 class="text-2xl font-bold mb-6 text-white flex items-center">
                    <i class="fa-solid fa-plus-circle text-teal-400 mr-2"></i> Post a New Service
                </h3>

                <form action="index.php?page=dashboard" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="create_service" value="1">

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Service Image</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-500 file:text-white hover:file:bg-teal-600">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Service Category</label>
                        <select name="category_id" required
                            class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500">
                            <?php while ($cat = $categories_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $cat['id']; ?>" class="bg-gray-800"><?php echo $cat['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Service Title</label>
                        <input type="text" name="title" required placeholder="e.g. Professional Home Cleaning"
                            class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="3" required placeholder="Describe what you offer..."
                            class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Price (USD)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-400">$</span>
                                <input type="number" name="price" required step="0.01"
                                    class="w-full bg-white/5 border border-gray-600 rounded-lg pl-8 pr-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Location</label>
                            <select name="location" required
                                class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500">
                                <option value="" class="bg-gray-800" disabled selected>Select City</option>
                                <option value="Monrovia" class="bg-gray-800">Monrovia</option>
                                <option value="Paynesville" class="bg-gray-800">Paynesville</option>
                                <option value="Sinkor" class="bg-gray-800">Sinkor</option>
                                <option value="Bushrod Island" class="bg-gray-800">Bushrod Island</option>
                                <option value="Congo Town" class="bg-gray-800">Congo Town</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-primary-600 to-secondary text-white font-bold py-3 rounded-lg shadow-lg hover:scale-[1.02] transition transform">
                        Post Service
                    </button>
                </form>
            </div>

            <!-- Active Services List -->
            <h3 class="text-xl font-bold text-gray-200">Your Active Services</h3>
            <div class="space-y-4">
                <?php if ($my_services->rowCount() > 0): ?>
                    <?php while ($row = $my_services->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="glass p-4 rounded-lg flex justify-between items-center group hover:bg-white/5 transition">
                            <div>
                                <h4 class="text-lg font-semibold text-white"><?php echo htmlspecialchars($row['title']); ?></h4>
                                <p class="text-sm text-gray-400"><?php echo htmlspecialchars($row['category_name']); ?> •
                                    $<?php echo htmlspecialchars($row['price']); ?></p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-gray-400 hover:text-white p-2"><i class="fa-solid fa-pen"></i></button>
                                <button class="text-red-400 hover:text-red-300 p-2"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center text-gray-500 py-8">You haven't posted any services yet.</div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>