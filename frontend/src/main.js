import './style.css'
import { renderLogin } from './login.js'
import { renderRegister } from './register.js'
import { renderServices } from './services.js'
import { renderBook } from './book.js'

// Simple Router
const routes = {
  '/': renderHome,
  '/login': renderLogin,
  '/register': renderRegister,
  '/services': renderServices,
  '/book': renderBook,
};

function renderHome() {
  document.querySelector('#app').innerHTML = `
  <div class="min-h-screen flex flex-col">
    <header class="bg-white shadow-sm sticky top-0 z-50">
      <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="/" class="text-2xl font-bold text-blue-600" data-link>LocalAid</a>
        <div class="hidden md:flex space-x-6">
          <a href="/" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Home</a>
          <a href="/services" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Services</a>
          <a href="/login" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Login</a>
          <a href="/book" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" data-link>Book Now</a>
        </div>
      </nav>
    </header>
    
    <main class="flex-grow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
          Find the help you need, <span class="text-blue-600">instantly.</span>
        </h1>
        <p class="mt-5 max-w-xl mx-auto text-xl text-gray-500">
          Cleaning, repairs, laundry, and more. Trusted professionals at your doorstep.
        </p>
      </div>
    </main>

    <footer class="bg-gray-800 text-white py-8">
      <div class="max-w-7xl mx-auto px-4 text-center">
        &copy; 2024 LocalAid. All rights reserved.
      </div>
    </footer>
  </div>
  `
  attachLinks();
}

function router() {
  const path = window.location.pathname;
  const view = routes[path] || renderHome;
  view();
  attachLinks();
}

function navigateTo(url) {
  history.pushState(null, null, url);
  router();
}

function attachLinks() {
  document.querySelectorAll('[data-link]').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      navigateTo(link.getAttribute('href'));
    });
  });
}

// Handle browser back/forward
window.addEventListener('popstate', router);

// Initial load
document.addEventListener('DOMContentLoaded', () => {
  router();
  checkAuthState();
});

function checkAuthState() {
  const token = localStorage.getItem('token');
  const user = JSON.parse(localStorage.getItem('user'));

  // Find nav container (simplified for now, assuming standard structure)
  // In a real app, this would be reactive or part of the render function
  const navRight = document.querySelector('nav .hidden.md\\:flex');
  if (!navRight) return;

  if (token && user) {
    // User is logged in
    navRight.innerHTML = `
            <a href="/" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Home</a>
            <a href="/services" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Services</a>
            <span class="text-gray-600 font-medium">Hi, ${user.name}</span>
            <button id="logout-btn" class="text-red-600 hover:text-red-700 font-medium">Logout</button>
            <a href="/book" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" data-link>Book Now</a>
        `;

    document.getElementById('logout-btn').addEventListener('click', async () => {
      // Optional: Call API to invalidate token
      // await fetch(`${API_URL}/logout`, { method: 'POST', headers: { Authorization: `Bearer ${token}` } });
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
    });
  } else {
    // User is guest (Default)
    navRight.innerHTML = `
            <a href="/" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Home</a>
            <a href="/services" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Services</a>
            <a href="/login" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Login</a>
            <a href="/book" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" data-link>Book Now</a>
        `;
  }

  attachLinks(); // Re-attach links for new elements
}

// Hook into router to update auth state on navigation
const originalRouter = router;
router = function () {
  const path = window.location.pathname;
  const view = routes[path] || renderHome;
  view();
  attachLinks();
  checkAuthState();
};
