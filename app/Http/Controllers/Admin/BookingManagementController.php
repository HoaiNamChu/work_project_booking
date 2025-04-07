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
    public function index()
    {
        $roomBookings = Booking::query()->with('customer')->orderBy('booking_date', 'DESC')->paginate(10);
        return view('admin.bookings.index', compact('roomBookings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $booking = Booking::query()
            ->with('customer', 'payment', 'rooms')
            ->findOrFail($id);
        return view('admin.bookings.edit', compact('booking'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'full_name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'nationality' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'status' => 'required',
            'roomBookingId' => 'required',
        ]);

        $dataBooking = [
            'check_in' => Carbon::parse($request->check_in)->format('Y-m-d'),
            'check_out' => Carbon::parse($request->check_out)->format('Y-m-d'),
            'status' => $request->status
        ];


        if ($dataBooking['check_in'] != null && $dataBooking['check_out'] != null) {
            if (Carbon::parse($dataBooking['check_in']) > Carbon::parse($dataBooking['check_out'])){
                return redirect()->back()->withErrors(['message' => 'Check-in must be after check out']);
            }
        }

//        foreach ($request->roomBookingId as $key => $value) {
//            $roomBooking = Room::query()->with('bookings')->where('id', $value)->first();
//            foreach ($roomBooking->bookings as $booking) {
//                if ($booking->id !== $id) {
//                    if (Carbon::parse($booking->check_out) < Carbon::parse($dataBooking['check_in'])){
//                        return redirect()->back();
//                    }
//                }
//            }
//        }

        $booking = Booking::query()->with('rooms')->findOrFail($id);

        $booking->rooms()->sync(request('roomBookingId'));

        $booking->update([
            'check_in' => $dataBooking['check_in'],
            'check_out' => $dataBooking['check_out'],
            'status' => $dataBooking['status'],
        ]);


        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

        return redirect()->route('admin.bookings.index');
    }
}
