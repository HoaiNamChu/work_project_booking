<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;
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

    public function saveCustomerInfo(Request $request)
    {
        $validatedData = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sdt' => 'nullable|regex:/^[0-9]{10,15}$/',
            'nationality' => 'required|string|max:100',
            'payment_method' => 'required|string',
        ]);

        // Lấy thông tin phòng đã đặt từ session
        $bookedRooms = session()->get('bookedRooms', []);
        foreach ($bookedRooms as $index => $room) {
            $room = Room::query()->find($room['room_id']);
            if ($room->remaining_rooms <= 0){
                return redirect()->route('showBooking')->with('Error', 'Room is out of stock');
            }
        }
        $totalAmount = array_sum(array_column($bookedRooms, 'room_total'));

        // Lưu thông tin khách hàng vào session
        session()->put('customer_info', [
            'full_name' => $validatedData['ho_ten'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['sdt'] ?? '',
            'nationality' => $validatedData['nationality'],
            'payment_method' => $validatedData['payment_method'],
            'booked_rooms' => $bookedRooms,
            'total_amount' => $totalAmount,
        ]);

        return redirect()->route('paymentPage');
    }

    public function showPaymentPage()
    {
        $customerInfo = session()->get('customer_info', []);

        if (empty($customerInfo)) {
            return redirect()->route('showBooking')->with('error', 'Vui lòng nhập thông tin khách hàng trước khi thanh toán.');
        }

        return view('Pages.thanhtoan', compact('customerInfo'));
    }


    public function payment(Request $request)
    {

        $customerInfo = session()->get('customer_info', []);
        foreach ($customerInfo['booked_rooms'] as $index => $room) {
            $room = Room::query()->find($room['room_id']);
            if ($room->remaining_rooms <= 0){
                return redirect()->route('showBooking')->with('Error', 'Room is out of stock');
            }
        }

        $customer = Customer::create([
            'full_name' => $customerInfo['full_name'],
            'email' => $customerInfo['email'],
            'phone' => $customerInfo['phone'],
            'nationality' => $customerInfo['nationality'],
        ]);

        $booking = Booking::create([
            'check_in' => Carbon::now(),
            'check_out' => Carbon::now(),
            'booking_date' => Carbon::now(),
            'status' => 'đang xử lý',
            'customer_id' => $customer->id,
        ]);
        $discountedPrice = 0;
        $totalAmount = 0;
        foreach ($customerInfo['booked_rooms'] as $value) {
            $booking->update([
                'check_in' => $value['check_in'],
                'check_out' => $value['check_out'],
            ]);
            DB::table('room_booking_detail')->insert([
                'booking_id' => $booking->id,
                'room_id' => $value['room_id'],
            ]);
            $discountedPrice += $value['discounted_price'];
            $totalAmount += $value['discounted_price'];
        }

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $discountedPrice,
            'tax' => 0,
            'total_amount' => $totalAmount,
            'payment_date' => Carbon::now(),
            'payment_method' => $customerInfo['payment_method'],
        ]);

        if ($customerInfo['payment_method'] === 'VNPAY') {
            return $this->processOnlinePayment($payment);
        }

        foreach ($customerInfo['booked_rooms'] as $value) {
           $room =  Room::query()->where('id', $value['room_id'])->first();
            if ($room->remaining_rooms <= 0) {
                return 0;
            }
           $room->update([
               'remaining_rooms' => (int)$room->remaining_rooms - 1,
           ]);
        }

        session()->forget('bookedRooms');

        return redirect()->route('home')->with('success', 'Đặt phòng thành công !');
    }

    private function processOnlinePayment($payment)
    {
        // Cấu hình cổng thanh toán (ví dụ với VNPAY)
        $vnp_TmnCode = "VE6K2G0A";
        $vnp_HashSecret = "2YZEFBP627O8ZXMP8H5XH0YWF19QXCV1";
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('payment.callback');

        $vnp_TxnRef = $payment->id . '_' . time();
        $vnp_OrderInfo = "Thanh toan don hang #$payment->id";
        $vnp_Amount = $payment->total_amount * 100;
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
        $orderId = explode('_', $request->vnp_TxnRef)[0];

        $order = Payment::findOrFail($orderId);


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
            return redirect()->route('home', $order->id);
        }

        return redirect()->route('home')->with('failed', 'Thanh toán không thành công');
    }


}
