<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Booking;

class Service extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'category',
        'location',
        'image_url',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
