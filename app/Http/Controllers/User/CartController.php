<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index()
    {
        // Lấy giỏ hàng từ session
        $shoppingCart = session('bookedRooms', []);
        return view('user.cart', compact('shoppingCart'));
    }

    public function count() {
        return response()->json(count(session('bookedRooms') ?? []));
    }


    public function remove($index)
    {
        $bookedRooms = session('bookedRooms', []);

        if (isset($bookedRooms[$index])) {
            unset($bookedRooms[$index]);
            session(['bookedRooms' => array_values($bookedRooms)]); // Cập nhật lại session sau khi xóa
            return redirect()->route('cart')->with('success', 'Đã xóa phòng khỏi giỏ hàng!');
        }

        return redirect()->route('cart')->with('error', 'Không tìm thấy phòng để xóa!');
    }


    public function add(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:room_detail,id',
            'check_in' => 'nullable|date|after_or_equal:today',
            'check_out' => 'nullable|date|after:check_in',
            'adults' => 'nullable|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:10',
            'rooms' => 'nullable|integer|min:1|max:5',

        ]);

        $data['check_in'] = $data['check_in'] ?? now()->toDateString();
        $data['check_out'] = $data['check_out'] ?? now()->addDays(1)->toDateString();
        $data['adults'] = $data['adults'] ?? 1;
        $data['children'] = $data['children'] ?? 0;
        $data['rooms'] = $data['rooms'] ?? 1;

        $totalPeople = $data['adults'] + $data['children'];
        if ($data['rooms'] > $totalPeople) {
            $message = 'Số phòng không được lớn hơn số người. Vui lòng cập nhật lại!';
            return $request->ajax()
                ? response()->json(['error' => $message], 422)
                : redirect()->back()->with('error', $message);
        }

        $room = Room::find($data['room_id']);
        if (!$room) {
            $message = 'Phòng không tồn tại!';
            return $request->ajax()
                ? response()->json(['error' => $message], 404)
                : redirect()->back()->with('error', $message);
        }

        $bookedRooms = session()->get('bookedRooms', []);
        $roomTypesInCart = collect($bookedRooms)->pluck('room_type')->unique();

        if ($roomTypesInCart->count() >= 4 && !in_array($room->room_type, $roomTypesInCart->toArray())) {
            $message = 'Giỏ hàng đã đầy, vui lòng xóa để thêm phòng mới!';
            return $request->ajax()
                ? response()->json(['error' => $message], 422)
                : redirect()->route('home')->with('error', $message);
        }

        $stayDays = Carbon::parse($data['check_out'])->diffInDays(Carbon::parse($data['check_in']));
        $discount = DB::table('discount')
            ->where('room_id', $room->id)
            ->where('start_date', '<=', $data['check_in'])
            ->where('end_date', '>=', $data['check_out'])
            ->first();

        $discountPercent = $discount->discount_percent ?? 0;
        $discountedPrice = $room->price_per_night * (1 - ($discountPercent / 100));
        $roomTotal = $discountedPrice * $stayDays * $data['rooms'];

        $roomExists = false;
        foreach ($bookedRooms as &$bookedRoom) {
            if ($bookedRoom['room_id'] === $room->id &&
                $bookedRoom['check_in'] === $data['check_in'] &&
                $bookedRoom['check_out'] === $data['check_out']) {
                $bookedRoom['rooms'] += $data['rooms'];
                $bookedRoom['room_total'] += $roomTotal;
                $roomExists = true;
                break;
            }
        }

        if (!$roomExists) {
            $bookedRooms[] = [
                'room_id' => $room->id,
                'room_type' => $room->room_type,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'adults' => $data['adults'],
                'children' => $data['children'],
                'rooms' => $data['rooms'],
                'price_per_night' => $room->price_per_night,
                'discount_percent' => $discountPercent,
                'stay_days' => $stayDays,
                'discounted_price' => $discountedPrice,
                'room_total' => $roomTotal,
                'file_anh'  => $room->file_anh

            ];
        }

        session()->put('bookedRooms', $bookedRooms);

        $successMessage = 'Phòng đã được thêm vào giỏ hàng!';
        return $request->ajax()
            ? response()->json(['success' => $successMessage])
            : redirect()->route('home')->with('success', $successMessage);
    }


    // UPATE số lượng phòng
    public function update(Request $request, $index)
    {
        $bookedRooms = session('bookedRooms', []);

        if (!isset($bookedRooms[$index])) {
            return redirect()->route('cart')->with('error', 'Phòng không tồn tại trong giỏ hàng.');
        }

        $validated = $request->validate([
            'rooms' => 'required|integer|min:1|max:5',
        ]);

        $rooms = $validated['rooms'];

        // Cập nhật lại session
        $room = &$bookedRooms[$index];
        $room['rooms'] = $rooms;

        // Cập nhật lại thành tiền
        $room['room_total'] = $room['discounted_price'] * $room['stay_days'] * $rooms;

        session(['bookedRooms' => $bookedRooms]);

        return redirect()->route('cart')->with('success', 'Cập nhật giỏ hàng thành công!');
    }

}
