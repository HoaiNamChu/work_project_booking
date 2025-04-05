<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Room;
class CartController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = session()->getId();

        // Lấy dữ liệu giỏ hàng theo session_id
        $cartItems = Cart::where('session_id', $sessionId)->get();

        // Kiểm tra nếu giỏ hàng trống
        if ($cartItems->isEmpty()) {
            return view('user.cart', ['shoppingCart' => []]);
        }

        $shoppingCart = $cartItems->map(function ($cart) {
            $room = Room::find($cart->room_id);
            return [
                'room_id' => $cart->room_id,
                'room_type' => $room->room_type ?? 'Không xác định',
                'image_url' => $room->image_url ?? '',
                'price_per_night' => $room->price_per_night ?? 0,
                'check_in' => $cart->check_in,
                'check_out' => $cart->check_out
            ];
        });

        return view('user.cart', compact('shoppingCart'));
    }

    public function remove($roomId)
    {
        $cart = session('shoppingCart', []);
        foreach ($cart as $key => $item) {
            if ($item['room_id'] == $roomId) {
                unset($cart[$key]);
                break;
            }
        }
        session(['shoppingCart' => array_values($cart)]);
        return redirect()->route('cart')->with('noti', 'Đã xóa phòng khỏi giỏ hàng!');
    }

    public function checkout($roomId)
    {

        $room = Room::query()->find($roomId);


        return view('Pages.thanhtoan', compact('room'));
    }
    public function addToCart(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:room_detail,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Lấy session_id (dùng để phân biệt khách hàng chưa đăng nhập)
        $session_id = session()->getId();

        // Thêm vào giỏ hàng
        Cart::create([
            'session_id' => $session_id,
            'room_id' => $request->room_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'adults' => $request->adults ?? 1,
            'children' => $request->children ?? 0,
            'quantity' => 1, // Giả định mặc định số lượng là 1
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Phòng đã được thêm vào giỏ hàng!',
        ]);
    }
}
