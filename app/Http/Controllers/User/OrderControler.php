<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderControler extends Controller
{
    public function payment(Request $request)
    {
        $data = [
            'check_in' => Carbon::parse(request('check_in'))->toDateString(),
            'check_out' => Carbon::parse( request('check_out'))->toDateString(),
            'room_id' => request('room_id'),
            'payment_method' => request('payment_method'),
            'booking_date' => Carbon::now(),
            'status' => 'đang xử lý',
        ];

        $room = Room::find(request('room_id'));

        $check_in = Carbon::parse(request('check_in'));
        $check_out = Carbon::parse(request('check_out'));
        $days = $check_in->diffInDays($check_out);
        if ($check_out->greaterThan($check_in->copy()->addDays($days))) {
            $days += 1;
        }

        $amount = $days * $room->price_per_night;


        $data['amount'] = $amount;
        $data['payment_date'] = Carbon::now();
        $data['tax'] = request('tax') ?? 0;
        $data['total_amount'] = $data['amount'] + $data['tax'];

        $customer = '';

        if (auth()->check()) {
            $customer = auth()->user();
        }else{
            $customer = Customer::create([
                'full_name' => 'Khách vãng lai',
                'email' => request('email'),
                'phone' => request('phone'),
                'nationality' => 'Khách vãng lai',
            ]);
        }

        $booking = Booking::create([
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'booking_date' => $data['booking_date'],
            'status' => $data['status'],
            'customer_id' => $customer->id,
        ]);

        DB::table('room_booking_detail')->insert([
            'booking_id' => $booking->id,
            'room_id' => $data['room_id'],
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $data['amount'],
            'tax' => $data['tax'],
            'total_amount' => $data['amount'],
            'payment_date' => $data['payment_date'],
            'payment_method' => $data['payment_method'],
        ]);

        if ($request->payment_method === 'VNPAY') {
            return $this->processOnlinePayment($payment);
        }

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
