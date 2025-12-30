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
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="username" class="sr-only">Username</label>
                        <input id="username" name="username" type="text" required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-600 bg-white/10 placeholder-gray-400 text-white rounded-t-lg focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm backdrop-blur-sm"
                            placeholder="Username">
                    </div>
                    <div>
                        <label for="email-address" class="sr-only">Email address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-600 bg-white/10 placeholder-gray-400 text-white focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm backdrop-blur-sm"
                            placeholder="Email address">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required
                            class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-600 bg-white/10 placeholder-gray-400 text-white rounded-b-lg focus:outline-none focus:ring-teal-500 focus:border-teal-500 focus:z-10 sm:text-sm backdrop-blur-sm"
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
                                class="rounded-lg border border-gray-600 bg-white/5 p-4 text-center hover:bg-white/10 peer-checked:border-teal-500 peer-checked:bg-teal-500/20 transition">
                                <i class="fa-solid fa-user text-xl mb-1 block"></i>
                                <span class="text-sm">Hire Info</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="provider" class="peer sr-only">
                            <div
                                class="rounded-lg border border-gray-600 bg-white/5 p-4 text-center hover:bg-white/10 peer-checked:border-secondary peer-checked:bg-pink-500/20 transition">
                                <i class="fa-solid fa-briefcase text-xl mb-1 block"></i>
                                <span class="text-sm">Work</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-primary-600 to-secondary hover:from-primary-700 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition shadow-lg">
                        Sign up
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>