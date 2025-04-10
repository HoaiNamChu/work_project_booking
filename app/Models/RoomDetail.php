<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomDetail extends Model
{
    use HasFactory;
    protected $table = 'room_detail';

    protected $fillable = [
        'room_type', 'bed_type', 'area', 'view', 'price_per_night', 'remaining_rooms', 'image_url', 'description',
    ];
}
