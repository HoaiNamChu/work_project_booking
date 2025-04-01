<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'room_detail'; // Đúng với tên bảng trong CSDL

    protected $fillable = [
        'room_type',
        'bed_type',
        'area',
        'view',
        'price_per_night',
        'remaining_room',
        'description',
        'file_anh'
    ];

    // Liên kết với bảng sức chứa
    public function capacity()
    {
        return $this->hasOne(Capacity::class, 'room_id');
    }


    // Liên kết với chi tiết đặt phòng (trung gian giữa room_booking và room_detail)
    public function bookingDetails()
    {
        return $this->hasMany(RoomBookingDetail::class, 'room_id');
    }

    // Liên kết với giảm giá
    public function discounts()
    {
        return $this->hasMany(RoomDiscount::class, 'room_id');
    }
}
