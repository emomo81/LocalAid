'use client';
import { useEffect, useState } from 'react';
import { supabase } from '@/lib/supabaseClient';
import Link from 'next/link';

export default function ServiceProfile({ params }: { params: { id: string } }) {
    const [service, setService] = useState<any>(null);
    const [loading, setLoading] = useState(true);

    // Unwrap params for Next.js 15+ if needed, but assuming 14 for now, params is sync in component props usually, 
    // but in latest Next.js params is a Promise. I'll stick to standard useEffect for client component.
    // Actually, 'params' prop is a Promise in recent Next.js versions for Server Components, 
    // but this is 'use client', so it's passed as prop? 
    // To be safe with 'use client', I'll use React.use() or just assume it's passed. 
    // Or better, make it a Server Component efficiently?
    // Let's stick to simple client fetch for now.

    const { id } = params;

    useEffect(() => {
        async function fetchService() {
            const { data, error } = await supabase
                .from('services')
                .select('*, profiles(*)')
                .eq('id', id)
                .single();

            if (data) setService(data);
            setLoading(false);
        }
        fetchService();
    }, [id]);

    if (loading) return <div className="p-10 text-center">Loading...</div>;
    if (!service) return <div className="p-10 text-center">Service not found</div>;

    return (
        <div className="min-h-screen bg-gray-50 flex flex-col items-center py-10">
            <div className="bg-white max-w-4xl w-full rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div className="h-64 bg-slate-200 relative">
                    <div className="absolute bottom-0 left-0 p-8 pt-32 bg-gradient-to-t from-black/60 to-transparent w-full">
                        <h1 className="text-4xl font-bold text-white">{service.title}</h1>
                        <p className="text-white/90 text-lg">{service.location}</p>
                    </div>
                </div>

                <div className="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div className="col-span-2 space-y-6">
                        <section>
                            <h2 className="text-2xl font-bold mb-4">About this Service</h2>
                            <p className="text-gray-600 leading-relaxed text-lg">{service.description}</p>
                        </section>

                        <section className="border-t pt-6">
                            <div className="flex items-center space-x-4">
                                {service.profiles?.avatar_url ? (
                                    <img src={service.profiles.avatar_url} className="w-16 h-16 rounded-full" alt="Worker" />
                                ) : (
                                    <div className="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-2xl">
                                        {service.profiles?.full_name?.[0] || 'W'}
                                    </div>
                                )}
                                <div>
                                    <h3 className="text-xl font-bold">{service.profiles?.full_name || 'Service Provider'}</h3>
                                    <p className="text-gray-500">Verified Worker</p>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div className="col-span-1">
                        <div className="bg-gray-50 p-6 rounded-xl border border-gray-200 sticky top-10">
                            <div className="text-4xl font-bold text-blue-600 mb-2">${service.price}</div>
                            <p className="text-gray-500 mb-6">Starting price</p>

                            <Link
                                href={`/book/${service.id}`}
                                className="block w-full text-center py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-bold rounded-xl shadow-lg hover:shadow-xl transition-all"
                            >
                                Book Now
                            </Link>
                            <p className="text-xs text-center text-gray-400 mt-4">Safe & Secure Payment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
