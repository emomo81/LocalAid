<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // specific user
        $provider = User::firstOrCreate(
            ['email' => 'provider@localaid.com'],
            [
                'name' => 'John Doe Provider',
                'password' => Hash::make('password'),
                'role' => 'provider'
            ]
        );

        Service::create([
            'user_id' => $provider->id,
            'title' => 'Professional Home Cleaning',
            'description' => 'Top-notch cleaning service for your entire home. Living room, kitchen, bathroom, and bedrooms.',
            'price' => 25.00,
            'category' => 'Cleaning',
            'location' => 'New York, NY',
            'image_url' => 'https://images.unsplash.com/photo-1581578731117-104f2a417954?auto=format&fit=crop&q=80&w=800'
        ]);

        Service::create([
            'user_id' => $provider->id,
            'title' => 'Expert Plumbing Repair',
            'description' => 'Fixing leaks, unclogging drains, and installing new fixtures. Fast and reliable service.',
            'price' => 45.00,
            'category' => 'Plumbing',
            'location' => 'Brooklyn, NY',
            'image_url' => 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?auto=format&fit=crop&q=80&w=800'
        ]);

        Service::create([
            'user_id' => $provider->id,
            'title' => 'Electrical Maintenance',
            'description' => 'Certified electrician for all your wiring and safety inspection needs.',
            'price' => 60.00,
            'category' => 'Electrical',
            'location' => 'Queens, NY',
            'image_url' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&q=80&w=800'
        ]);
    }
}
