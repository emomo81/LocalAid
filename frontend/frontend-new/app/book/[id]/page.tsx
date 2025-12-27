'use client';
import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { supabase } from '@/lib/supabaseClient';

export default function BookService({ params }: { params: { id: string } }) {
    const router = useRouter();
    const { id } = params;
    const [date, setDate] = useState('');
    const [loading, setLoading] = useState(false);

    const handleBooking = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);

        const { data: { user } } = await supabase.auth.getUser();

        if (!user) {
            alert('Please login to book a service');
            router.push('/login');
            return;
        }

        // Insert booking
        const { error } = await supabase
            .from('bookings')
            .insert([
                {
                    service_id: id,
                    customer_id: user.id,
                    booking_date: new Date(date).toISOString(),
                    status: 'pending'
                }
            ]);

        setLoading(false);

        if (error) {
            alert('Booking failed: ' + error.message);
        } else {
            alert('Booking successful!');
            router.push('/');
        }
    };

    return (
        <div className="min-h-screen bg-gray-50 flex items-center justify-center p-4">
            <div className="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
                <h1 className="text-2xl font-bold mb-6 text-center">Book Service</h1>
                <form onSubmit={handleBooking} className="space-y-6">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">Select Date & Time</label>
                        <input
                            type="datetime-local"
                            required
                            className="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            value={date}
                            onChange={(e) => setDate(e.target.value)}
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={loading}
                        className="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition-all disabled:opacity-50"
                    >
                        {loading ? 'Processing...' : 'Confirm Booking'}
                    </button>

                    <button
                        type="button"
                        onClick={() => router.back()}
                        className="w-full py-2 text-gray-500 hover:text-gray-700 font-medium"
                    >
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    );
}
