<?php
// Handle Login Logic
$error = "";
if ($_POST) {
    require_once '../src/models/User.php';
    $user = new User($db);

    $user->email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->emailExists() && $user->verifyPassword($password)) {
        // Success
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;

        // Redirect
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<div class="min-h-screen flex items-center justify-center pt-20 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full glass p-8 rounded-2xl shadow-2xl space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-300">
                Or
                <a href="index.php?page=register" class="font-medium text-teal-400 hover:text-teal-300">
                    create a new account
                </a>
            </p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-100 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="index.php?page=login" method="POST">
            <input type="hidden" name="remember" value="true">
            <div class="space-y-4">
                <div>
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required
                        class="input-field" placeholder="Email address">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="input-field" placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox"
                        class="h-4 w-4 text-teal-500 focus:ring-teal-500 border-gray-600 rounded bg-white/10">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-300">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-teal-400 hover:text-teal-300">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary w-full group relative">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fa-solid fa-lock text-white/50 group-hover:text-white transition"></i>
                    </span>
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>