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

// Handle Verification Actions
if ($_POST) {
    require_once '../src/models/User.php';
    $adminUser = new User($db);

    if (isset($_POST['approve_user'])) {
        if ($adminUser->approveVerification($_POST['user_id'])) {
            $msg = "User approved successfully.";
        }
    }
    if (isset($_POST['reject_user'])) {
        if ($adminUser->rejectVerification($_POST['user_id'])) {
            $msg = "User verification rejected.";
        }
    }
}

// Fetch Pending Verifications
// We assume User model is already required or we require it
require_once '../src/models/User.php';
$verifUser = new User($db);
$pending_verifs = $verifUser->getPendingVerifications();
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

    <!-- Management Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Users -->
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

        <!-- Pending Verifications -->
        <div class="glass rounded-xl p-6 border border-amber-500/20 shadow-lg shadow-amber-900/10">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Pending Verifications</h3>
                <span
                    class="bg-amber-500 text-black text-xs font-bold px-2 py-1 rounded-full"><?php echo $pending_verifs->rowCount(); ?>
                    Pending</span>
            </div>

            <?php if (isset($msg)): ?>
                <div class="bg-green-500/20 text-green-100 text-sm p-2 rounded mb-4"><?php echo $msg; ?></div>
            <?php endif; ?>

            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                <?php if ($pending_verifs->rowCount() > 0): ?>
                    <?php while ($pv = $pending_verifs->fetch(PDO::FETCH_ASSOC)): ?>
                        <div
                            class="card bg-white/5 p-4 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h4 class="font-bold text-white"><?php echo htmlspecialchars($pv['username']); ?></h4>
                                <p class="text-xs text-gray-400"><?php echo htmlspecialchars($pv['email']); ?></p>
                                <a href="<?php echo htmlspecialchars($pv['verification_doc']); ?>" target="_blank"
                                    class="text-xs text-teal-400 underline hover:text-teal-300 mt-1 inline-block"><i
                                        class="fa-solid fa-paperclip"></i> View Document</a>
                            </div>
                            <form action="" method="POST" class="flex space-x-2">
                                <input type="hidden" name="user_id" value="<?php echo $pv['id']; ?>">
                                <button type="submit" name="reject_user"
                                    class="p-2 rounded bg-red-500/20 text-red-300 hover:bg-red-500 hover:text-white transition"
                                    title="Reject">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                                <button type="submit" name="approve_user"
                                    class="p-2 rounded bg-green-500/20 text-green-300 hover:bg-green-500 hover:text-white transition"
                                    title="Approve">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-500 text-sm italic">No pending verifications.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>