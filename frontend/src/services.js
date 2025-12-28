import { getServices } from './api.js';

export async function renderServices() {
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
          
          <div id="services-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6">
             <!-- Loader -->
             <div class="col-span-3 text-center py-12">
                <p class="text-gray-500">Loading services...</p>
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
  `;

  try {
    const data = await getServices();
    const services = data.data; // Laravel pagination returns { data: [...] }
    const container = document.getElementById('services-grid');

    if (services.length === 0) {
      container.innerHTML = `<div class="col-span-3 text-center py-12"><p class="text-gray-500">No services found.</p></div>`;
      return;
    }

    container.innerHTML = services.map(service => `
        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
          <div class="h-48 bg-gray-200 w-full object-cover flex items-center justify-center text-gray-400">
            ${service.image_url ? `<img src="${service.image_url}" class="h-full w-full object-cover" />` : '<span class="text-4xl">🛠️</span>'}
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900">${service.title}</h3>
            <p class="mt-2 text-gray-500 line-clamp-2">${service.description}</p>
            <div class="mt-4 flex items-center justify-between">
              <span class="text-blue-600 font-bold">$${service.price}</span>
              <a href="/book" class="text-blue-600 hover:text-blue-800 font-medium" data-link>Book Now &rarr;</a>
            </div>
          </div>
        </div>
      `).join('');
  } catch (error) {
    document.getElementById('services-grid').innerHTML = `
        <div class="col-span-3 text-center py-12 text-red-500">
            Failed to load services. Please try again later.
        </div>
      `;
  }
}
