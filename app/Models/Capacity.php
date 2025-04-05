<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacity extends Model
{
    use HasFactory;

    protected $table = 'capacity'; // Tên bảng trong database

    protected $fillable = ['room_id', 'max_capacity'];

    public $timestamps = false;
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
