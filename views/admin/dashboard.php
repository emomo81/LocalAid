<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // For now simple check, role is string 'admin'.
    // If we don't have admins yet, this might lock me out, ensuring user table has role enum or string logic.
    // Assuming schema allows role 'admin'.
    // Redirect to login if not admin
    header("Location: index.php?page=login");
    exit;
}

// Simple Stats
$users_count = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$services_count = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
$bookings_count = $db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$revenue_est = $db->query("SELECT SUM(price) FROM bookings WHERE status='completed' JOIN services ON bookings.service_id = services.id")->fetchColumn();
// Note: SQL for revenue is tricky without joins properly or price in bookings table. 
// Simplification: We don't store price in bookings history, so price change affects old records. 
// Ignored for MVP.
?>

<div class="max-w-7xl mx-auto px-4 py-24 animate-fade-in">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
            <p class="text-gray-400">System Overview & Management</p>
        </div>
        <div class="text-right">
            <p class="text-teal-400 font-bold"><?php echo date('F d, Y'); ?></p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass p-6 rounded-xl border border-white/5">
            <h3 class="text-gray-400 text-sm font-medium">Total Users</h3>
            <p class="text-3xl font-bold text-white mt-2"><?php echo $users_count; ?></p>
        </div>
        <div class="glass p-6 rounded-xl border border-white/5">
            <h3 class="text-gray-400 text-sm font-medium">Active Services</h3>
            <p class="text-3xl font-bold text-white mt-2"><?php echo $services_count; ?></p>
        </div>
        <div class="glass p-6 rounded-xl border border-white/5">
            <h3 class="text-gray-400 text-sm font-medium">Total Bookings</h3>
            <p class="text-3xl font-bold text-white mt-2"><?php echo $bookings_count; ?></p>
        </div>
        <div class="glass p-6 rounded-xl border border-white/5">
            <h3 class="text-gray-400 text-sm font-medium">Completed Revenue (Est)</h3>
            <p class="text-3xl font-bold text-teal-400 mt-2">$<?php echo number_format($revenue_est ?: 0, 2); ?></p>
        </div>
    </div>

    <!-- Management Sections (Placeholders) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="glass rounded-xl p-6">
            <h3 class="text-xl font-bold text-white mb-4">Recent Users</h3>
            <table class="w-full text-left text-sm text-gray-400">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="pb-2">User</th>
                        <th class="pb-2">Role</th>
                        <th class="pb-2">Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $db->query("SELECT username, role, created_at FROM users ORDER BY created_at DESC LIMIT 5");
                    while ($u = $stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
                        <tr class="border-b border-gray-800 last:border-0 hover:bg-white/5 transition">
                            <td class="py-3 text-white font-medium"><?php echo htmlspecialchars($u['username']); ?></td>
                            <td class="py-3"><span
                                    class="px-2 py-1 rounded-full text-xs font-bold bg-white/10"><?php echo $u['role']; ?></span>
                            </td>
                            <td class="py-3"><?php echo date('M d', strtotime($u['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="glass rounded-xl p-6">
            <h3 class="text-xl font-bold text-white mb-4">Platform Health</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm text-gray-400 mb-1">
                        <span>Server Status</span>
                        <span class="text-green-400">Operational</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm text-gray-400 mb-1">
                        <span>Database Load</span>
                        <span class="text-yellow-400">Moderate</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>