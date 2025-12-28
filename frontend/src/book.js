import { createBooking, getServices } from './api.js';

export async function renderBook() {
    const token = localStorage.getItem('token');
    if (!token) {
        alert('Please login to book a service.');
        window.location.href = '/login';
        return; // Stop rendering
    }

    document.querySelector('#app').innerHTML = `
    <div class="min-h-screen flex flex-col">
       <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          <a href="/" class="text-2xl font-bold text-blue-600" data-link>LocalAid</a>
          <div class="hidden md:flex space-x-6">
            <a href="/" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Home</a>
            <a href="/services" class="text-gray-600 hover:text-blue-600 font-medium" data-link>Services</a>
            <button id="logout-btn" class="text-red-600 hover:text-red-700 font-medium">Logout</button>
          </div>
        </nav>
      </header>

      <main class="flex-grow bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900">
                Book a Service
              </h3>
              <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>Select a service and choose a time that works for you.</p>
              </div>
              
              <form id="booking-form" class="mt-5 space-y-6">
                 <div>
                    <label for="service" class="block text-sm font-medium text-gray-700">Service</label>
                    <select id="service" name="service_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Loading services...</option>
                    </select>
                </div>

                <div>
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" required class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                 <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Any specific instructions..."></textarea>
                </div>

                <div>
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Confirm Booking
                    </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  `;

    // Populate Services
    try {
        const data = await getServices();
        const services = data.data;
        const select = document.getElementById('service');
        select.innerHTML = '<option value="">Select a service</option>' +
            services.map(s => `<option value="${s.id}">${s.title} ($${s.price})</option>`).join('');
    } catch (err) {
        alert('Failed to load services.');
    }

    // Handle Submit
    document.getElementById('booking-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const service_id = e.target.service_id.value;
        const scheduled_at = e.target.scheduled_at.value; // Format: 2025-06-12T19:30
        const notes = e.target.notes.value;

        // Basic format fix for Laravel validation (needs Y-m-d H:i:s usually, but local datetime often works or needs T removed)
        // Let's ensure format is acceptable. 'Y-m-d H:i:s' is standard.
        const formattedDate = scheduled_at.replace('T', ' ') + ':00';

        try {
            await createBooking({
                service_id,
                scheduled_at: formattedDate,
                notes
            });
            alert('Booking Confirmed! ✅');
            window.location.href = '/';
        } catch (err) {
            alert('Booking Failed: ' + err.message);
        }
    });

    // Logout handler (since we overwrote nav)
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        });
    }
}
