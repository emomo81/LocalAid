<?php
// Ensure user is logged in and is a provider
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'provider') {
    header("Location: index.php?page=login");
    exit;
}

require_once '../src/models/Service.php';
require_once '../src/models/Category.php';

$service = new Service($db);
$category = new Category($db);
$msg = "";

// Handle Form Submit
if ($_POST) {
    if (isset($_POST['create_service'])) {
        $service->provider_id = $_SESSION['user_id'];
        $service->category_id = $_POST['category_id'];
        $service->title = $_POST['title'];
        $service->description = $_POST['description'];
        $service->price = $_POST['price'];
        $service->location = $_POST['location'];

        if ($service->create()) {
            $msg = "<div class='bg-green-500/20 text-green-100 p-3 rounded mb-4'>Service posted successfully!</div>";
        } else {
            $msg = "<div class='bg-red-500/20 text-red-100 p-3 rounded mb-4'>Unable to post service.</div>";
        }
    }
}

// Fetch my services
$my_services = $service->readByProvider($_SESSION['user_id']);

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

            <!-- Add Service Form -->
            <div class="glass rounded-xl p-8 animate-fade-in-up">
                <h3 class="text-2xl font-bold mb-6 text-white flex items-center">
                    <i class="fa-solid fa-plus-circle text-teal-400 mr-2"></i> Post a New Service
                </h3>

                <form action="index.php?page=dashboard" method="POST" class="space-y-6">
                    <input type="hidden" name="create_service" value="1">

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