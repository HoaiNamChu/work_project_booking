<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;

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

    public function payment(Request $request)
    {
        $bookingInfo = session('finalBooking');
        foreach ($bookingInfo['rooms'] as $item) {
            $room = Room::query()->where('id', $item['room_id'])->first();
            if ($room->remaining_rooms < $item['rooms']) {
                return redirect()->back();
            }
        }

        $customerInfo = session('finalBooking')['customer'];


        $customer = Customer::query()->create([
            'full_name' => $customerInfo['ho_ten'],
            'email' => $customerInfo['email'],
            'phone' => $customerInfo['sdt'],
            'nationality' => $customerInfo['nationality'],
        ]);

        $booking = Booking::query()->create([
            'check_in' => Carbon::now(),
            'check_out' => Carbon::now(),
            'booking_date' => Carbon::now(),
            'customer_id' => $customer->id,
        ]);

        foreach ($bookingInfo['rooms'] as $item) {
            $booking->update([
                'check_in' => $item['check_in'],
                'check_out' => $item['check_out'],
            ]);

            DB::table('room_booking_detail')->insert([
                'room_id' => $item['room_id'],
                'booking_id' => $booking->id,
            ]);
        }

        $payment = Payment::query()->create([
            'booking_id' => $booking->id,
            'amount' => $bookingInfo['total_amount'],
            'tax' => 0,
            'total_amount' => $bookingInfo['total_amount'],
            'payment_date' => Carbon::now(),
            'payment_method' => $bookingInfo['customer']['payment_method'],
        ]);

        if ($bookingInfo['customer']['payment_method'] == 'VNPAY') {
            return $this->processOnlinePayment($payment);
        }

        foreach ($bookingInfo['rooms'] as $item) {
            $room = Room::query()->where('id', $item['room_id'])->first();

            if ($room->remaining_rooms >= $item['rooms'] && $room->remaining_rooms != 0) {
                $room->update([
                    'remaining_rooms' => $room->remaining_rooms - $item['rooms'],
                ]);
            }
        }

        session()->forget('finalBooking');

        return redirect()->route('thankYou')->with('success', 'Đặt phòng thành công!');


    }


    private function processOnlinePayment($order)
    {
        // Cấu hình cổng thanh toán (ví dụ với VNPAY)
        $vnp_TmnCode = "VE6K2G0A";
        $vnp_HashSecret = "2YZEFBP627O8ZXMP8H5XH0YWF19QXCV1";
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('paymentCallback');

        $vnp_TxnRef = $order->id . '_' . time();
        $vnp_OrderInfo = "Thanh toan don hang #$order->id";
        $vnp_Amount = $order->total_amount * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        return redirect($vnp_Url);
    }

    public function paymentCallback(Request $request)
    {

        // Xác minh tính hợp lệ của response từ VNPAY
        $vnp_HashSecret = "2YZEFBP627O8ZXMP8H5XH0YWF19QXCV1";
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash === $vnp_SecureHash && $request->vnp_ResponseCode == '00') {


            $bookingInfo = session('finalBooking');

            foreach ($bookingInfo['rooms'] as $item) {
                $room = Room::query()->where('id', $item['room_id'])->first();

                if ($room->remaining_rooms >= $item['rooms'] && $room->remaining_rooms != 0) {
                    $room->update([
                        'remaining_rooms' => $room->remaining_rooms - $item['rooms'],
                    ]);
                }
            }

            session()->forget('finalBooking');

            return redirect()->route('thankYou')->with('success', 'Đặt phòng thành công !');
        }

        return redirect()->route('thankYou')->with('error', 'Đặt phòng không thành công!');
    }

    public function thankYou(Request $request)
    {
        return view('pages.thankyou');
    }


}
