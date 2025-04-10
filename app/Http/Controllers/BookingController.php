<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    
        public function xoaPhong($index)
    {
        $bookedRooms = session()->get('bookedRooms', []);
        
        if (isset($bookedRooms[$index])) {
            unset($bookedRooms[$index]);
            session()->put('bookedRooms', array_values($bookedRooms));
        }

        return redirect()->route('showBooking');
    }

    public function showBooking()
    {
        $bookedRooms = session()->get('bookedRooms', []);
        $totalAmount = array_sum(array_column($bookedRooms, 'room_total'));
        return view('Pages.thongtin', compact('bookedRooms', 'totalAmount'));
    }

    public function saveBookingWithCustomerInfo(Request $request)
    {
        $validated = $request->validate([
            'ho_ten' => 'required|max:255',
            'email' => 'required|email|max:255',
            'sdt' => 'nullable|regex:/^[0-9]{10,15}$/',
            'nationality' => 'required|max:100',
            'payment_method' => 'required|in:CASH,VNPAY',
            'rooms' => 'required|array',
            'rooms.*.room_type' => 'required|string',
            'rooms.*.check_in' => 'required|date',
            'rooms.*.check_out' => 'required|date|after:rooms.*.check_in',
            'rooms.*.rooms' => 'required|integer|min:1',
            'rooms.*.price_per_night' => 'required|numeric|min:0',
            'rooms.*.discount_percent' => 'required|numeric|min:0|max:100',
        ]);
    
        $bookedRooms = [];
        $totalAmount = 0;
    
        foreach ($validated['rooms'] as $room) {
            $stayDays = \Carbon\Carbon::parse($room['check_out'])->diffInDays(\Carbon\Carbon::parse($room['check_in']));
            $discountedPrice = $room['price_per_night'] * (1 - ($room['discount_percent'] / 100));
            $roomTotal = $discountedPrice * $stayDays * $room['rooms'];
    
            $room['stay_days'] = $stayDays;
            $room['discounted_price'] = $discountedPrice;
            $room['room_total'] = $roomTotal;
    
            $bookedRooms[] = $room;
            $totalAmount += $roomTotal;
        }
    
        session([
            'finalBooking' => [
                'rooms' => $bookedRooms,
                'customer' => [
                    'ho_ten' => $validated['ho_ten'],
                    'email' => $validated['email'],
                    'sdt' => $validated['sdt'] ?? '',
                    'nationality' => $validated['nationality'],
                    'payment_method' => $validated['payment_method'],
                ],
                'total_amount' => $totalAmount,
            ]
        ]);
    
        return redirect()->route('confirmBooking')->with('success', 'Thông tin đặt phòng đã được lưu!');
    }
    

    public function showConfirmPage()
{
    $booking = session('finalBooking');
    $bookedRooms = $booking['rooms']; // nếu lưu nhiều phòng

    return view('pages.thanhtoan', compact('booking', 'bookedRooms'));
}



}