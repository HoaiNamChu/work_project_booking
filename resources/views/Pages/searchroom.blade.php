@extends("Layout.main")

@section("title","Trang tìm phòng")

@section("content")

<section class="book" style="margin-top: 10vh">
    <div class="container flex_space">
        <div class="text">
            <h1><span>Book</span> Your rooms</h1>
        </div>
        <div class="form">
            <form action="{{ route('searchroom.search') }}" class="grid" method="POST">
            @csrf  
                <table class="table-book">
                    <tr>
                        <td style="color: white;">Ngày nhận phòng</td>
                        <td style="color: white;">Ngày trả phòng</td>
                        <td style="color: white;">Người lớn</td>
                        <td style="color: white;">Trẻ em</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><input type="date" name="check_in" value="{{ request('check_in', old('check_in')) }}" required></td>
                        <td><input type="date" name="check_out" value="{{ request('check_out', old('check_out')) }}" required></td>
                        <td><input type="number" name="adults" min="1" max="10" value="{{ request('adults', old('adults', 1)) }}" required></td>
                        <td><input type="number" name="children" min="0" max="10" value="{{ request('children', old('children', 0)) }}" required></td>
                        <td><button type="submit" class="primary-btn">Tìm kiếm</button></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkinInput = document.querySelector("[name='check_in']");
        const checkoutInput = document.querySelector("[name='check_out']");
        const bookingForm = document.querySelector("form");

        bookingForm.addEventListener("submit", function(event) {
            const checkinDate = new Date(checkinInput.value);
            const checkoutDate = new Date(checkoutInput.value);

            if (checkinDate >= checkoutDate) {
                alert("❌ Ngày nhận phòng phải trước ngày trả phòng!");
                event.preventDefault(); // Ngăn form gửi đi
                return;
            }
        });
    });
</script>

@if(isset($data))
<section class="room-list">
    <div class="container">
        <h2 class="section-title">Danh sách phòng trống</h2>
        
        @if($available_rooms->isEmpty())
            <p class="no-rooms">Không có phòng trống phù hợp!</p>
        @else
            <div class="room-grid">
                @foreach($available_rooms as $room)
                <div class="room-card" style="width:600px">
                <img src="{{ asset('room_img/'.$room->file_anh) }}" alt="{{ $room->room_type }}" style="width: 300px">
                <div class="room-details">
                        <h3>{{ $room->room_type }}</h3>
                        <p><i class="fas fa-bed"></i> Loại giường: <strong>{{ $room->bed_type ?? 'Không xác định' }}</strong></p>
                        <p><i class="fas fa-ruler-combined"></i> Diện tích: <strong>{{ $room->area ?? 'Không xác định' }} m²</strong></p>
                        <p><i class="fas fa-umbrella-beach"></i> Hướng phòng: <strong>{{ $room->view ?? 'Không xác định' }}</strong></p>
                        <p><i class="fas fa-tag"></i> Giá phòng/đêm: <strong>{{ number_format($room->price_per_night, 0, ',', '.') }} VNĐ</strong></p>
                        <p class="discount"><i class="fas fa-percent"></i> Giảm giá:<strong>{{ $room->discount_percent }}%</strong></p>
                        <p><i class="fas fa-door-open"></i> Số phòng còn lại: <strong>{{ $room->remaining_rooms ?? 'Không xác định' }}</strong></p>
                        <p><i class="fas fa-user-friends"></i> Sức chứa: <strong>{{ optional($room->capacity)->max_capacity ?? 'Không xác định' }} người</strong></p>
                        
                        <div class="room-actions">
                            <form method="GET" action="{{ route('thongtin') }}">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <input type="hidden" name="check_in" value="{{ $data['check_in'] }}">
                                <input type="hidden" name="check_out" value="{{ $data['check_out'] }}">
                                <input type="hidden" name="adults" value="{{ $data['adults'] }}">
                                <input type="hidden" name="children" value="{{ $data['children'] }}">
                                <button type="submit" class="primary-btn">Đặt ngay</button>
                            </form>

                            <form id="addToCartForm">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <input type="hidden" name="check_in" value="{{ $data['check_in'] }}">
                                <input type="hidden" name="check_out" value="{{ $data['check_out'] }}">
                                <button type="submit" class="primary-btn">Thêm vào giỏ hàng</button>
                            </form>
                            <div id="cart-message" style="color: green; display: none;">✅ Đã thêm vào giỏ hàng!</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("form#addToCartForm").forEach(function (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Ngăn trang tải lại

            let formData = new FormData(this);

            fetch("{{ route('cart.add') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("input[name=_token]").value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("✅ " + data.message); // Chỉ hiển thị alert khi thành công
                }
            })
            .catch(error => console.error("Lỗi:", error));
        });
    });
});
</script>



@endsection