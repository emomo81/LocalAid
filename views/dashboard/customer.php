<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once '../src/models/Booking.php';
$booking = new Booking($db);
$my_bookings = $booking->getByCustomer($_SESSION['user_id']);
?>

<div class="max-w-7xl mx-auto px-4 py-24">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="glass rounded-xl p-6">
                <div class="flex items-center space-x-4 mb-6">
                    <div
                        class="h-16 w-16 bg-gradient-to-br from-teal-400 to-blue-500 rounded-full flex items-center justify-center text-2xl font-bold text-white">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white"><?php echo htmlspecialchars($_SESSION['username']); ?>
                        </h2>
                        <span class="text-gray-400 text-sm">Customer</span>
                    </div>
                </div>
                <nav class="space-y-2">
                    <a href="#" class="block px-4 py-2 bg-white/10 text-white rounded-lg font-medium">My Bookings</a>
                    <a href="index.php?page=profile"
                        class="block px-4 py-2 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition">Edit
                        Profile</a>
                </nav>
            </div>
        </div>

        <!-- content -->
        <div class="lg:col-span-3">
            <h2 class="text-2xl font-bold text-white mb-6">My Bookings</h2>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'booked'): ?>
                <div class="bg-green-500/20 text-green-100 p-4 rounded-lg mb-6 border border-green-500/30">
                    <i class="fa-solid fa-check-circle mr-2"></i> Booking request sent successfully!
                </div>
            <?php endif; ?>

            <div class="space-y-4">
                <?php if ($my_bookings->rowCount() > 0): ?>
                    <?php while ($row = $my_bookings->fetch(PDO::FETCH_ASSOC)): ?>
                        <div
                            class="glass p-6 rounded-xl flex flex-col md:flex-row justify-between items-start md:items-center group hover:bg-white/5 transition">
                            <div class="mb-4 md:mb-0">
                                <h3 class="text-lg font-bold text-white"><?php echo htmlspecialchars($row['service_title']); ?>
                                </h3>
                                <p class="text-gray-400 text-sm mb-1">Provider: <span
                                        class="text-teal-400"><?php echo htmlspecialchars($row['provider_name']); ?></span></p>
                                <p class="text-gray-400 text-sm"><i class="fa-regular fa-clock mr-1"></i>
                                    <?php echo date('M d, Y h:i A', strtotime($row['booking_date'])); ?></p>
                            </div>

                            <div class="flex items-center space-x-4">
                                <?php
                                $statusColor = 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30';
                                if ($row['status'] == 'confirmed')
                                    $statusColor = 'bg-green-500/20 text-green-300 border-green-500/30';
                                if ($row['status'] == 'cancelled')
                                    $statusColor = 'bg-red-500/20 text-red-300 border-red-500/30';
                                if ($row['status'] == 'completed')
                                    $statusColor = 'bg-blue-500/20 text-blue-300 border-blue-500/30';
                                ?>
                                <div class="flex flex-col items-end space-y-2">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold border <?php echo $statusColor; ?> uppercase tracking-wider">
                                        <?php echo $row['status']; ?>
                                    </span>

                                    <?php if ($row['status'] == 'confirmed' || $row['status'] == 'completed'): ?>
                                        <a href="index.php?page=review&booking_id=<?php echo $row['id']; ?>"
                                            class="text-xs bg-white/10 hover:bg-white/20 text-white px-3 py-1 rounded transition">
                                            <i class="fa-regular fa-star mr-1"></i> Review
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-12 glass rounded-xl">
                        <i class="fa-regular fa-calendar-xmark text-4xl text-gray-500 mb-3"></i>
                        <p class="text-gray-400">No active bookings.</p>
                        <a href="index.php?page=services" class="text-teal-400 hover:text-teal-300 text-sm mt-2 block">Find
                            a service</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>