<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Public: List all services
    public function index(Request $request)
    {
        $query = Service::with('provider');

        // Simple Search/Filter
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    // Public: Show single service
    public function show($id)
    {
        return Service::with('provider')->findOrFail($id);
    }

    // Protected: Create a service
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string',
            'location' => 'required|string',
            'image_url' => 'nullable|url'
        ]);

        // Create service linked to current user
        $service = $request->user()->services()->create($fields);

        return response()->json($service, 201);
    }
}
