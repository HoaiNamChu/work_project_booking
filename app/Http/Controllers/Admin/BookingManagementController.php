<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


public function index(Request $request)
{
    $query = Booking::with('customer')->orderBy('booking_date', 'DESC');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if (request('check_in') !== null && request('check_out') == null){
        $query->where('check_in', '>=', Carbon::parse(request('check_in'))->format('Y-m-d'));
    }

    if (request('check_in') == null && request('check_out') !== null){
        $query->where('check_out', '<=', Carbon::parse(request('check_out'))->format('Y-m-d'));
    }

    if (request('check_in') !== null && request('check_out') !== null) {
        if (Carbon::parse(request('check_in'))->format('Y-m-d') > Carbon::parse(request('check_out'))->format('Y-m-d')) {
            return redirect()->back()->with('error', 'Check-in must be after check out');
        }

        $query->where('check_in', '>=', Carbon::parse(request('check_in'))->format('Y-m-d'))
        ->where('check_out', '<=', Carbon::parse(request('check_out'))->format('Y-m-d'));
    }

    $roomBookings = $query->paginate(10)->withQueryString();

    return view('admin.bookings.index', compact('roomBookings'));
}

public function edit($id)
{
    $booking = Booking::with('customer', 'rooms')->findOrFail($id);
    $rooms = Room::all();

    return view('admin.bookings.edit', compact('booking', 'rooms'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email',
        'nationality' => 'required|string|max:100',
        'check_in' => 'required|date',
        'check_out' => 'required|date|after_or_equal:check_in',
        'status' => 'required|string',
        'roomBookingId' => 'required|array',
    ]);

    $booking = Booking::with('customer', 'rooms')->findOrFail($id);

    // Cập nhật thông tin khách hàng
    $booking->customer->update([
        'full_name' => $request->input('full_name'),
        'phone' => $request->input('phone'),
        'email' => $request->input('email'),
        'nationality' => $request->input('nationality'),
    ]);

    // Cập nhật thông tin booking
    $booking->update([
        'check_in' => Carbon::parse($request->check_in)->format('Y-m-d'),
        'check_out' => Carbon::parse($request->check_out)->format('Y-m-d'),
        'status' => $request->status,
    ]);

    // Cập nhật danh sách phòng (many-to-many)
    $booking->rooms()->sync($request->roomBookingId);

    return redirect()->route('admin.bookings.index')->with('success', 'Cập nhật đặt phòng thành công!');
}

    public function destroy($id)
    {
        $booking = Booking::query()->with('rooms')->findOrFail($id);

        foreach ($booking->rooms as $room){
            $room = Room::query()->with('capacity')->findOrFail($room->id);
            if ((int)$room->remaining_rooms < (int)$room->capacity->max_capacity){
                $room->update([
                    'remaining_rooms' => $room->remaining_rooms + 1
                ]);
            }
        }

        $booking->payment()->delete();

        DB::table('room_booking_detail')->where('booking_id', $id)->delete();


        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Xóa đơn đặt phòng thành công!');
    }
}
