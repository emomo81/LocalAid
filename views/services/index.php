<?php
require_once '../src/models/Service.php';
require_once '../src/models/Category.php';

$service = new Service($db);
$category = new Category($db);

// Get Filters
$keywords = isset($_GET['q']) ? $_GET['q'] : null;
$location_filter = isset($_GET['location']) ? $_GET['location'] : null;
$lat = isset($_GET['lat']) ? $_GET['lat'] : null;
$lng = isset($_GET['lng']) ? $_GET['lng'] : null;

// Fetch filtered services
$services_stmt = $service->readAll($keywords, $location_filter, $lat, $lng);

// Fetch categories for filter (future enhancement)
$categories_stmt = $category->readAll();
?>

<div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto min-h-screen">

    <!-- Header / Filter Section -->
    <div class="text-center mb-12 animate-fade-in">
        <h1 class="text-4xl font-extrabold text-white mb-4">Find Local Professionals</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Browse trusted service providers in your area ready to help you.</p>

        <!-- Functional Filter Bar -->
        <form action="index.php" method="GET" id="searchForm"
            class="mt-8 max-w-4xl mx-auto glass p-2 rounded-lg flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4">
            <input type="hidden" name="page" value="services">
            <input type="hidden" name="lat" id="latInput" value="<?php echo htmlspecialchars($lat ?? ''); ?>">
            <input type="hidden" name="lng" id="lngInput" value="<?php echo htmlspecialchars($lng ?? ''); ?>">

            <div
                class="flex-grow w-full md:w-auto px-4 py-2 border-b md:border-b-0 md:border-r border-gray-600 flex items-center">
                <i class="fa-solid fa-search text-gray-400 mr-2"></i>
                <input type="text" name="q" value="<?php echo htmlspecialchars($keywords ?? ''); ?>"
                    placeholder="Search for 'Cleaning'..."
                    class="bg-transparent border-none text-white focus:ring-0 w-full placeholder-gray-400">
            </div>

            <div
                class="flex-grow w-full md:w-auto px-4 py-2 border-b md:border-b-0 md:border-r border-gray-600 flex items-center">
                <i class="fa-solid fa-location-dot text-gray-400 mr-2"></i>
                <select name="location"
                    class="bg-transparent border-none text-white focus:ring-0 w-full cursor-pointer">
                    <option value="" class="bg-gray-800 text-gray-300">All Locations</option>
                    <?php
                    $counties = [
                        'Bomi',
                        'Bong',
                        'Gbarpolu',
                        'Grand Bassa',
                        'Grand Cape Mount',
                        'Grand Gedeh',
                        'Grand Kru',
                        'Lofa',
                        'Margibi',
                        'Maryland',
                        'Montserrado',
                        'Nimba',
                        'River Cess',
                        'River Gee',
                        'Sinoe'
                    ];
                    foreach ($counties as $c) {
                        $selected = ($location_filter == $c) ? 'selected' : '';
                        echo "<option value='$c' class='bg-gray-800' $selected>$c</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Locator Button -->
            <button type="button" id="geoBtn" onclick="getLocation()"
                class="bg-teal-500/20 hover:bg-teal-500/30 text-teal-300 p-2 rounded-lg transition"
                title="Use My Location">
                <i class="fa-solid fa-crosshairs"></i>
            </button>

            <button type="submit" class="btn btn-primary w-full md:w-auto">
                Search
            </button>
        </form>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in-up">
        <?php if ($services_stmt->rowCount() > 0): ?>
            <?php while ($row = $services_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <!-- Service Card -->
                <div class="glass rounded-xl overflow-hidden hover:scale-[1.02] transition duration-300 group flex flex-col">
                    <!-- Category Badge / Image Placeholder -->
                    <div class="h-48 bg-gray-800 relative flex items-center justify-center overflow-hidden">
                        <?php if (!empty($row['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($row['title']); ?>"
                                class="w-full h-full object-cover transition duration-500 transform group-hover:scale-110" />
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent opacity-60"></div>
                        <?php else: ?>
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-800 to-gray-900 opacity-80"></div>
                            <i
                                class="<?php echo $row['category_icon']; ?> text-6xl text-gray-700 group-hover:text-gray-600 transition duration-500 transform group-hover:scale-110"></i>
                        <?php endif; ?>

                        <div class="absolute top-4 left-4 z-10">
                            <span
                                class="<?php echo $row['category_bg']; ?> <?php echo $row['category_color']; ?> px-3 py-1 rounded-full text-xs font-bold border border-white/10 backdrop-blur-md">
                                <?php echo htmlspecialchars($row['category_name']); ?>
                            </span>
                        </div>

                        <!-- Distance Badge if Lat/Lng present -->
                        <?php if (isset($row['distance'])): ?>
                            <div class="absolute top-4 right-4 z-10">
                                <span
                                    class="bg-gray-900/80 text-teal-400 px-3 py-1 rounded-full text-xs font-bold border border-teal-500/30 backdrop-blur-md flex items-center">
                                    <i class="fa-solid fa-location-arrow mr-1"></i>
                                    <?php echo number_format($row['distance'], 1); ?> km
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-6 flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-white leading-tight mb-1">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h3>
                            <span
                                class="text-teal-400 font-bold whitespace-nowrap">$<?php echo htmlspecialchars($row['price']); ?></span>
                        </div>

                        <p class="text-gray-400 text-sm mb-4 line-clamp-2"><?php echo htmlspecialchars($row['description']); ?>
                        </p>

                        <div class="mt-auto pt-4 border-t border-gray-700 flex justify-between items-center text-sm">
                            <div class="flex items-center text-gray-300">
                                <div
                                    class="h-8 w-8 rounded-full bg-gradient-to-r from-primary-600 to-secondary flex items-center justify-center mr-2 text-xs font-bold text-white">
                                    <?php echo strtoupper(substr($row['provider_name'], 0, 1)); ?>
                                </div>
                                <span class="mr-1"><?php echo htmlspecialchars($row['provider_name']); ?></span>
                                <?php if (!empty($row['is_verified'])): ?>
                                    <i class="fa-solid fa-circle-check text-blue-400 text-xs" title="Verified Provider"></i>
                                <?php endif; ?>
                            </div>
                            <div class="text-gray-400 flex items-center">
                                <i class="fa-solid fa-map-marker-alt mr-1"></i>
                                <?php echo htmlspecialchars($row['location']); ?>
                            </div>
                        </div>

                        <div class="flex space-x-2 mt-4">
                            <a href="index.php?page=service&service_id=<?php echo $row['id']; ?>"
                                class="flex-1 text-center bg-white/5 hover:bg-white/10 text-white font-semibold py-2 rounded-lg transition border border-white/10">
                                View Details
                            </a>
                            <a href="index.php?page=book&service_id=<?php echo $row['id']; ?>"
                                class="flex-1 text-center bg-teal-500/20 hover:bg-teal-500/30 text-teal-300 font-semibold py-2 rounded-lg transition border border-teal-500/30">
                                Book
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-full text-center py-20">
                <i class="fa-solid fa-box-open text-6xl text-gray-600 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-400">No services found yet.</h3>
                <p class="text-gray-500">Check back later or become a pro to post one!</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
    function getLocation() {
        if (navigator.geolocation) {
            // Show loading state on button
            const btn = document.getElementById('geoBtn');
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        document.getElementById("latInput").value = position.coords.latitude;
        document.getElementById("lngInput").value = position.coords.longitude;
        // Auto submit to sort
        document.getElementById("searchForm").submit();
    }

    function showError(error) {
        const btn = document.getElementById('geoBtn');
        btn.innerHTML = '<i class="fa-solid fa-crosshairs"></i>'; // Reset icon

        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                alert("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
        }
    }
</script>