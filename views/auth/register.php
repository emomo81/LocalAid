<?php
// Handle Register Logic
$error = "";
$success = "";

if ($_POST) {
    require_once '../src/models/User.php';
    $user = new User($db);

    $user->username = $_POST['username'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $user->role = $_POST['role']; // 'customer' or 'provider'

    if ($user->emailExists()) {
        $error = "Email already registered.";
    } else {
        if ($user->register()) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<div class="min-h-screen flex items-center justify-center pt-24 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full glass p-8 rounded-2xl shadow-2xl space-y-8">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-white">
                Create Account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-300">
                Or
                <a href="index.php?page=login" class="font-medium text-teal-400 hover:text-teal-300">
                    sign in to existing account
                </a>
            </p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-100 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-500/20 border border-green-500 text-green-100 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo $success; ?></span>
                <p class="mt-2 text-sm"><a href="index.php?page=login" class="underline">Click here to login</a></p>
            </div>
        <?php else: ?>

            <form class="mt-8 space-y-6" action="index.php?page=register" method="POST">
                <div class="space-y-4">
                    <div>
                        <label for="username" class="sr-only">Username</label>
                        <input id="username" name="username" type="text" required class="input-field"
                            placeholder="Username">
                    </div>
                    <div>
                        <label for="email-address" class="sr-only">Email address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                            class="input-field" placeholder="Email address">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required class="input-field"
                            placeholder="Password">
                    </div>
                </div>

                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">I want to:</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="customer" class="peer sr-only" checked>
                            <div
                                class="rounded-xl border border-gray-600 bg-white/5 p-4 text-center hover:bg-white/10 peer-checked:border-teal-500 peer-checked:bg-teal-500/20 transition">
                                <i class="fa-solid fa-user text-xl mb-1 block text-teal-400"></i>
                                <span class="text-sm font-medium">Hire Info</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="provider" class="peer sr-only">
                            <div
                                class="rounded-xl border border-gray-600 bg-white/5 p-4 text-center hover:bg-white/10 peer-checked:border-pink-500 peer-checked:bg-pink-500/20 transition">
                                <i class="fa-solid fa-briefcase text-xl mb-1 block text-pink-400"></i>
                                <span class="text-sm font-medium">Work</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary w-full group relative">
                        Sign up
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>