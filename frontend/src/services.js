export function renderServices() {
    document.querySelector('#app').innerHTML = `
    <div class="min-h-screen flex flex-col">
       <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          <a href="/" class="text-2xl font-bold text-blue-600" data-link>LocalAid</a>
          <div class="hidden md:flex space-x-6">
            <a href="/" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Home</a>
            <a href="/services" class="text-blue-600 font-medium" data-link>Services</a>
            <a href="/login" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Login</a>
            <a href="/book" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" data-link>Book Now</a>
          </div>
        </nav>
      </header>

      <main class="flex-grow bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Available Services</h1>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Service Card 1 -->
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
              <div class="h-48 bg-gray-200 w-full object-cover flex items-center justify-center text-gray-400">
                <span class="text-4xl">🧹</span>
              </div>
              <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900">Home Cleaning</h3>
                <p class="mt-2 text-gray-500">Professional home cleaning services. Deep cleaning, regular maintenance, and move-in/out.</p>
                <div class="mt-4 flex items-center justify-between">
                  <span class="text-blue-600 font-bold">$25/hr</span>
                  <a href="/book" class="text-blue-600 hover:text-blue-800 font-medium" data-link>Book Now &rarr;</a>
                </div>
              </div>
            </div>

            <!-- Service Card 2 -->
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
              <div class="h-48 bg-gray-200 w-full object-cover flex items-center justify-center text-gray-400">
                <span class="text-4xl">🔧</span>
              </div>
              <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900">Plumbing Repair</h3>
                <p class="mt-2 text-gray-500">Expert plumbing solutions for leaks, clogs, and installations. Fast and reliable.</p>
                <div class="mt-4 flex items-center justify-between">
                  <span class="text-blue-600 font-bold">$40/hr</span>
                   <a href="/book" class="text-blue-600 hover:text-blue-800 font-medium" data-link>Book Now &rarr;</a>
                </div>
              </div>
            </div>

            <!-- Service Card 3 -->
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
               <div class="h-48 bg-gray-200 w-full object-cover flex items-center justify-center text-gray-400">
                <span class="text-4xl">⚡</span>
              </div>
              <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900">Electrical Help</h3>
                <p class="mt-2 text-gray-500">Certified electricians for wiring, repairs, and safety inspections.</p>
                <div class="mt-4 flex items-center justify-between">
                  <span class="text-blue-600 font-bold">$50/hr</span>
                   <a href="/book" class="text-blue-600 hover:text-blue-800 font-medium" data-link>Book Now &rarr;</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      
       <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
          &copy; 2024 LocalAid. All rights reserved.
        </div>
      </footer>
    </div>
  `
}
