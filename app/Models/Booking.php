<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'room_booking'; // Đúng với tên bảng trong CSDL

    protected $fillable = [
        'check_in', 'check_out', 'booking_date', 'customer_id', 'status'
    ];

    // Liên kết với khách hàng
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Liên kết với chi tiết đặt phòng
    public function bookingDetails()
    {
        return $this->hasMany(RoomBookingDetail::class, 'booking_id');
    }

    // Liên kết với thanh toán
    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id');
    }

    // Lấy danh sách các phòng trong đặt phòng
    public function rooms()
    {

    }

}
