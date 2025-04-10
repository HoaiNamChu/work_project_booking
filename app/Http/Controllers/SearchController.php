<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Session;
use App\Models\Slideshow;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    // Hiển thị form tìm phòng
    public function showForm()
    {
        $slides = Slideshow::all(); // Lấy dữ liệu slide từ database
        return view("Pages.searchroom", compact('slides')); // Truyền vào view
    }

    // Xử lý tìm phòng
    public function searchRoom(Request $request)
{
    $data = $request->validate([
        'check_in'     => 'required|date|after_or_equal:today',
        'check_out'    => 'required|date|after:check_in',
        'adults'       => 'required|integer|min:1|max:10',
        'children'     => 'required|integer|min:0|max:10',
        'sort_by'      => 'nullable|in:asc,desc',
        'has_discount' => 'nullable|in:1', // chỉ chấp nhận 1 nếu có
    ]);
    

    // Lưu vào session
    session([
        'check_in'     => $data['check_in'],
        'check_out'    => $data['check_out'],
        'adults'       => $data['adults'],
        'children'     => $data['children'],
        'sort_by'      => $data['sort_by'] ?? 'asc',
        'has_discount' => $data['has_discount'] ?? null,
    ]);
    

    $total_guests = $data['adults'] + $data['children'];
    $sort_by = $data['sort_by'] ?? 'asc';
    $has_discount = $data['has_discount'] ?? null;
    
    $available_rooms = Room::with('capacity')
        ->leftJoin('discount', function ($join) use ($data) {
            $join->on('room_detail.id', '=', 'discount.room_id')
                 ->where('discount.start_date', '<=', $data['check_in'])
                 ->where('discount.end_date', '>=', $data['check_out']);
        })
        ->selectRaw('room_detail.*, COALESCE(discount.discount_percent, 0) as discount_percent')
        ->whereHas('capacity', function ($query) use ($total_guests) {
            $query->where('max_capacity', '>=', $total_guests);
        })
        ->where('remaining_rooms', '>', 0)
        ->when($has_discount == '1', function ($query) {
            // chỉ chọn những phòng có discount_percent > 0
            $query->whereRaw('COALESCE(discount.discount_percent, 0) > 0');
        })
        ->orderBy('price_per_night', $sort_by)
        ->distinct()
        ->get();
    

    return view("Pages.searchroom", compact('data', 'available_rooms'));
}

public function hienThiThongTin(Request $request)
{
    $data = $request->validate([
        'room_id' => 'required|exists:room_detail,id',
        'check_in' => 'nullable|date|after_or_equal:today',
        'check_out' => 'nullable|date|after:check_in',
        'rooms' => 'nullable|integer|min:1|max:10'
    ]);

    // Thiết lập mặc định nếu thiếu
    $data['check_in'] = $data['check_in'] ?? now()->toDateString();
    $data['check_out'] = $data['check_out'] ?? now()->addDays(1)->toDateString();
    $data['adults'] = $data['adults'] ?? 1;
    $data['children'] = $data['children'] ?? 0;
    $data['rooms'] = $data['rooms'] ?? 1;

    // Lấy thông tin phòng
    $room = \App\Models\Room::find($data['room_id']);

    // Tính số đêm
    $stayDays = \Carbon\Carbon::parse($data['check_out'])->diffInDays(\Carbon\Carbon::parse($data['check_in']));

    // Tính giảm giá nếu có
    $discount = DB::table('discount')
        ->where('room_id', $room->id)
        ->where('start_date', '<=', $data['check_in'])
        ->where('end_date', '>=', $data['check_out'])
        ->first();

    $discountPercent = $discount->discount_percent ?? 0;
    $discountedPrice = $room->price_per_night * (1 - ($discountPercent / 100));
    $roomTotal = $discountedPrice * $stayDays * $data['rooms'];


    // Tạo dữ liệu hiển thị
    $bookedRooms = [[
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
        'room_total' => $roomTotal
    ]];

    $totalAmount = $roomTotal;

    return view('pages.thongtin', compact('bookedRooms', 'totalAmount'));
}

}