<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

require_once '../src/models/User.php';
$user = new User($db);
$user->id = $_SESSION['user_id'];

// Handle Update
$msg = "";
if ($_POST) {
    if (isset($_POST['update_profile'])) {
        $user->phone = $_POST['phone'];
        $user->bio = $_POST['bio'];
        $user->location = $_POST['location'];
        
        // Handle Avatar Upload
        if (!empty($_FILES['avatar']['name'])) {
            $target_dir = "../public/uploads/avatars/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            // Simple unique naming
            $filename = time() . "_" . $_SESSION['user_id'] . "_" . basename($_FILES["avatar"]["name"]);
            $target_file = $target_dir . $filename;
            
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                $user->avatar_url = "uploads/avatars/" . $filename;
            } else {
                $user->avatar_url = $profile['avatar_url'] ?? ""; // Keep old or empty
            }
        } else {
             $user->avatar_url = $profile['avatar_url'] ?? ""; // Keep old if not provided
        }

        if ($user->updateProfile()) {
            $msg = "<div class='bg-green-500/20 text-green-100 p-3 rounded mb-4'>Profile updated successfully!</div>";
        } else {
            $msg = "<div class='bg-red-500/20 text-red-100 p-3 rounded mb-4'>Unable to update profile.</div>";
        }
    }
}

// Re-Fetch Data to show updates immediately (especially avatar)
$profile = $user->getProfile();

// Fetch Current Data
$profile = $user->getProfile();
?>

<div class="max-w-4xl mx-auto px-4 py-24 animate-fade-in-up">
    <div class="glass p-8 rounded-xl">
        <h2 class="text-3xl font-bold text-white mb-6">Edit Profile</h2>

        <?php echo $msg; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="update_profile" value="1">

            <div class="flex items-center space-x-6">
                <div class="shrink-0">
                    <?php if (!empty($profile['avatar_url'])): ?>
                        <img class="h-24 w-24 object-cover rounded-full border-4 border-gray-700"
                            src="<?php echo htmlspecialchars($profile['avatar_url']); ?>" alt="Current profile photo" />
                    <?php else: ?>
                        <div
                            class="h-24 w-24 rounded-full bg-gradient-to-br from-primary-500 to-secondary flex items-center justify-center text-3xl font-bold text-white border-4 border-gray-700">
                            <?php echo strtoupper(substr($profile['username'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <label class="block">
                    <span class="sr-only">Choose profile photo</span>
                    <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-gray-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-teal-500 file:text-white
                        hover:file:bg-teal-600
                    " />
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Read Only Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Username</label>
                    <input type="text" value="<?php echo htmlspecialchars($profile['username']); ?>" readonly
                        class="w-full bg-black/20 border border-gray-700 rounded-lg px-4 py-2 text-gray-400 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                    <input type="text" value="<?php echo htmlspecialchars($profile['email']); ?>" readonly
                        class="w-full bg-black/20 border border-gray-700 rounded-lg px-4 py-2 text-gray-400 cursor-not-allowed">
                </div>
            </div>

            <!-- Editable Fields -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Role</label>
                <div
                    class="px-4 py-2 bg-teal-500/10 text-teal-400 border border-teal-500/30 rounded-lg inline-block text-sm uppercase font-bold tracking-wider">
                    <?php echo htmlspecialchars($profile['role']); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>"
                        placeholder="+231..."
                        class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Location / Address</label>
                    <input type="text" name="location"
                        value="<?php echo htmlspecialchars($profile['location'] ?? ''); ?>"
                        placeholder="e.g. Sinkor, 12th Street"
                        class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Bio / About Me</label>
                <textarea name="bio" rows="4" placeholder="Tell us about yourself..."
                    class="w-full bg-white/5 border border-gray-600 rounded-lg px-4 py-2 text-white focus:ring-teal-500 focus:border-teal-500"><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-700">
                <a href="index.php?page=dashboard" class="text-gray-400 hover:text-white transition">Back to
                    Dashboard</a>
                <button type="submit"
                    class="bg-gradient-to-r from-primary-600 to-secondary text-white font-bold py-2 px-8 rounded-lg shadow-lg hover:scale-105 transition transform">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>