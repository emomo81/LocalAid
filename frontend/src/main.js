import './style.css'

document.querySelector('#app').innerHTML = `
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="text-2xl font-bold text-blue-600">LocalAid</div>
      <div class="hidden md:flex space-x-6">
        <a href="/" class="text-gray-600 hover:text-blue-600 font-medium">Home</a>
        <a href="/services" class="text-gray-600 hover:text-blue-600 font-medium">Services</a>
        <a href="/login" class="text-gray-600 hover:text-blue-600 font-medium">Login</a>
        <a href="/book" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Book Now</a>
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
`
