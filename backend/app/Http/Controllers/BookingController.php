<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Create a booking
    public function store(Request $request)
    {
        $fields = $request->validate([
            'service_id' => 'required|exists:services,id',
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string'
        ]);

        $service = Service::findOrFail($fields['service_id']);

        // Prevent booking your own service
        if ($service->user_id === $request->user()->id) {
            return response()->json(['message' => 'You cannot book your own service.'], 403);
        }

        $booking = Booking::create([
            'service_id' => $fields['service_id'],
            'client_id' => $request->user()->id,
            'provider_id' => $service->user_id,
            'scheduled_at' => $fields['scheduled_at'],
            'status' => 'pending',
            'notes' => $fields['notes'] ?? null
        ]);

        return response()->json($booking, 201);
    }

    // List my bookings (as a client)
    public function index(Request $request)
    {
        return $request->user()->bookings()->with(['service', 'provider'])->latest()->get();
    }
}
