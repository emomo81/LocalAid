'use client';
import { useState, useEffect } from 'react';
import Link from 'next/link';
import { supabase } from '../lib/supabaseClient';

export default function Home() {
  const [searchTerm, setSearchTerm] = useState('');
  const [location, setLocation] = useState('');
  const [services, setServices] = useState<any[]>([]);

  useEffect(() => {
    fetchServices();
  }, []);

  const fetchServices = async () => {
    // Basic fetch, in real app filter by search/location
    const { data, error } = await supabase
      .from('services')
      .select('*, profiles(full_name, avatar_url)')
      .limit(6);

    if (data) setServices(data);
  };

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    // Implement search logic or redirect to search page
    console.log('Searching for:', searchTerm, location);
  };

  return (
    <div className="min-h-screen bg-gray-50 font-sans text-gray-900">
      {/* Navigation */}
      <nav className="fixed w-full z-50 top-0 start-0 border-b border-gray-200 bg-white/80 backdrop-blur-md">
        <div className="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
          <a href="/" className="flex items-center space-x-3 rtl:space-x-reverse">
            <span className="self-center text-2xl font-bold whitespace-nowrap text-blue-600">LocalAid</span>
          </a>
          <div className="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <Link href="/login" className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center transition-all">
              Get Started
            </Link>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="relative py-32 bg-gradient-to-br from-blue-600 to-indigo-700 text-white overflow-hidden">
        <div className="absolute inset-0 bg-[url('/hero-pattern.svg')] opacity-10"></div>
        <div className="relative max-w-screen-xl mx-auto px-4 z-10 text-center">
          <h1 className="mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl lg:text-6xl">
            Find Trusted Local Services in Liberia
          </h1>
          <p className="mb-8 text-lg font-normal text-blue-100 lg:text-xl sm:px-16 lg:px-48">
            From house cleaning to skilled handymen. Connect with verified professionals near you.
          </p>

          {/* Search Bar */}
          <form onSubmit={handleSearch} className="max-w-2xl mx-auto bg-white rounded-xl shadow-2xl p-2 flex flex-col md:flex-row gap-2">
            <input
              type="text"
              placeholder="What do you need help with?"
              className="flex-1 p-3 text-gray-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
            <input
              type="text"
              placeholder="Location (e.g. Monrovia)"
              className="flex-1 p-3 text-gray-900 rounded-lg border-l md:border-l border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
              value={location}
              onChange={(e) => setLocation(e.target.value)}
            />
            <button type="submit" className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
              Search
            </button>
          </form>
        </div>
      </section>

      {/* Featured Services */}
      <section className="py-16 px-4 max-w-screen-xl mx-auto">
        <h2 className="text-3xl font-bold mb-8 text-center">Popular Services</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {ServicesList(services)}
        </div>

        {services.length === 0 && (
          <div className="text-center text-gray-500 mt-8">
            <p>No services found yet. Be the first to list one!</p>
          </div>
        )}
      </section>
    </div>
  );
}

function ServicesList(services: any[]) {
  // Placeholder if no data
  if (!services || services.length === 0) {
    return [1, 2, 3].map((i) => (
      <div key={i} className="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow border border-gray-100">
        <div className="h-48 bg-gray-200 animate-pulse"></div>
        <div className="p-6">
          <div className="h-6 bg-gray-200 rounded w-3/4 mb-4 animate-pulse"></div>
          <div className="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
          <div className="mt-4 flex justify-between items-center">
            <div className="h-8 bg-gray-200 rounded w-20 animate-pulse"></div>
          </div>
        </div>
      </div>
    ));
  }

  return services.map((service) => (
    <div key={service.id} className="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow border border-gray-100">
      <div className="h-48 bg-gray-200 relative">
        {/* <img src={service.image_url} alt={service.title} className="w-full h-full object-cover" /> */}
        <div className="absolute top-2 right-2 bg-white px-2 py-1 rounded-full text-xs font-bold shadow">
          {service.category}
        </div>
      </div>
      <div className="p-6">
        <h3 className="text-xl font-bold mb-2 text-gray-800">{service.title}</h3>
        <p className="text-gray-600 text-sm mb-4 line-clamp-2">{service.description}</p>
        <div className="flex items-center justify-between">
          <span className="text-blue-600 font-bold text-lg">${service.price}</span>
          <span className="text-gray-500 text-sm">{service.location}</span>
        </div>
        <Link href={`/services/${service.id}`} className="block mt-4 text-center w-full py-2 bg-gray-50 hover:bg-gray-100 text-blue-600 font-semibold rounded-lg transition-colors">
          View Details
        </Link>
      </div>
    </div>
  ));
}
