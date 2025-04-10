@extends("Layout.main")

@section("title","Trang tìm phòng")

@section("content")
<style>.text-bg-success {
    background-color: #28a745 !important; /* Green background */
    color: #ffffff !important;          /* White text */
}

.text-bg-danger {
    background-color: #dc3545 !important; /* Red background */
    color: #ffffff !important;           /* White text */
}

.btn-close-white {
    filter: brightness(0) invert(1); /* Ensure close button remains white */
}
.filter-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    padding: 10px 0;
    border-bottom: 2px solid #e0e0e0;
    margin-bottom: 20px;
}

.section-title {
    font-size: 1.5rem;
    color: #333;
    margin: 0;
}

.filter-form {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.form-group {
    display: flex;
    flex-direction: column;
    font-size: 0.9rem;
}

.form-group label {
    margin-bottom: 4px;
    color: #444;
    font-weight: 500;
}

.form-group select {
    padding: 5px 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    min-width: 160px;
}

</style>
<!-- Toast dynamic notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
    <div id="dynamicToast" class="toast align-items-center border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div id="toast-body-content" class="toast-body">Thông báo sẽ xuất hiện ở đây</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
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
                        <td><button type="submit" class="btn primary-btn">Tìm kiếm</button></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</section>
<script>
    //------------------Ngày nhận và ngày trả--------------------//
        document.addEventListener("DOMContentLoaded", function() {
        const checkinInput = document.querySelector("[name='check_in']");
        const checkoutInput = document.querySelector("[name='check_out']");
        const bookingForm = document.querySelector("form");

        // Lấy ngày hiện tại và ngày mai
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1); // Ngày mai

        // Định dạng ngày thành YYYY-MM-DD
        const formatDate = (date) => date.toISOString().split("T")[0];

        // Thiết lập giá trị mặc định & min cho input ngày
        checkinInput.value = formatDate(today);
        checkinInput.setAttribute("min", formatDate(today));

        checkoutInput.value = formatDate(tomorrow);
        checkoutInput.setAttribute("min", formatDate(today));

        //  Kiểm tra trước khi gửi form
        bookingForm.addEventListener("submit", function(event) {
            const checkinDate = new Date(checkinInput.value);
            const checkoutDate = new Date(checkoutInput.value);

            if (checkinDate >= checkoutDate) {
                alert("❌ Ngày nhận phòng phải trước ngày trả phòng!");
                event.preventDefault(); // Ngăn form gửi đi
            }
        });
    });
</script>

@if(isset($data))
<section class="room-list">
    <div class="container">
    <div class="filter-container">
    <h2 class="section-title">Danh sách phòng trống</h2>
    <form id="sort-form" method="POST" action="{{ route('searchroom.search') }}" class="filter-form">
        @csrf
        <input type="hidden" name="check_in" value="{{ request('check_in') }}">
        <input type="hidden" name="check_out" value="{{ request('check_out') }}">
        <input type="hidden" name="adults" value="{{ request('adults') }}">
        <input type="hidden" name="children" value="{{ request('children') }}">

        <div class="form-group">
            <label for="sort_by">Sắp xếp theo giá:</label>
            <select name="sort_by" id="sort_by" onchange="this.form.submit()">
                <option value="asc" {{ request('sort_by') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                <option value="desc" {{ request('sort_by') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
            </select>
        </div>

        <div class="form-group">
            <label for="has_discount">Hiện phòng có ưu đãi:</label>
            <select name="has_discount" id="has_discount" onchange="this.form.submit()">
                <option value="">Tất cả</option>
                <option value="1" {{ request('has_discount') == '1' ? 'selected' : '' }}>Chỉ hiện phòng có ưu đãi</option>
            </select>
        </div>
    </form>
</div>


        @if($available_rooms->isEmpty())
            <p class="no-rooms">Không có phòng trống phù hợp!</p>
        @else
            <div class="room-grid">
                @foreach($available_rooms as $room)
                <div class="room-card" style="width:1000px;height: 330px">
                <img src="{{ asset('room_img/'.$room->file_anh) }}" alt="{{ $room->room_type }}" style="width:60%">
                    <div class="room-details" style="width:40%">
                        <h4>{{ $room->room_type }}</h4>
                        <p><i class="fas fa-bed"></i> Loại giường: <strong>{{ $room->bed_type ?? 'Không xác định' }}</strong></p>
                        <p><i class="fas fa-ruler-combined"></i> Diện tích: <strong>{{ $room->area ?? 'Không xác định' }} m²</strong></p>
                        <p><i class="fas fa-umbrella-beach"></i> Hướng phòng: <strong>{{ $room->view ?? 'Không xác định' }}</strong></p>
                        <p class="discount"><i class="fas fa-percent"></i> Giảm giá: 
                            @if($room['discount_percent'] > 0)
                                <span class="badge bg-success">{{ $room['discount_percent'] }}%</span>
                            @else
                                <span class="badge bg-secondary">Không giảm giá</span>
                            @endif
                        </p>

                        <p class="d-flex align-items-center gap-2" style="gap:10px">
                            <i class="fas fa-tag"></i>
                            <span>Giá phòng/đêm:</span>
                            <b>
                            @if($room['discount_percent'] > 0)
                                <span class="text-muted" style="text-decoration: line-through;">
                                    {{ number_format($room->price_per_night, 0, ',', '.') }} VNĐ
                                </span>
                                <span class="fw-bold text-danger">
                                    {{ number_format($room->price_per_night * (1 - $room['discount_percent'] / 100), 0, ',', '.') }} VNĐ
                                </span>
                            @else
                                <strong>{{ number_format($room->price_per_night, 0, ',', '.') }} VNĐ</strong>
                            @endif
                            </b>
                        </p>

                        <p><i class="fas fa-door-open"></i>
                            <label for="rooms_{{ $room->id }}">Số phòng:</label>
                            <input type="number" id="roomsInput_{{ $room->id }}" name="rooms_{{ $room->id }}" value="1" min="1" max="5"  style="width: 40px;">
                        </p>

                        <div class="room-actions" >
                            <form method="POST" action="{{ route('thongtin') }}" >
                                @csrf
                                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                                    <input type="hidden" name="check_in" value="{{ $data['check_in'] }}">
                                    <input type="hidden" name="check_out" value="{{ $data['check_out'] }}">
                                    <input type="hidden" name="rooms" class="roomsField" value="1">
                                    <button type="submit" class="btn primary-btn" style="margin-top: -10px;">Đặt ngay</button>
                            </form>
                            <form method="POST" action="{{ route('cart.add') }}" id="formAdd_{{ $room->id }}" class="add-to-cart" data-room-id="{{ $room->id }}">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <input type="hidden" name="check_in" value="{{ $data['check_in'] }}">
                                <input type="hidden" name="check_out" value="{{ $data['check_out'] }}">
                                <input type="hidden" name="rooms" id="hiddenRooms_{{ $room->id }}" value="1">
                                <button type="submit" class="btn primary-btn" style="margin-top: -10px;">Thêm vào giỏ hàng</button>
                            </form>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script >//chỉnh cái này nè
                          // Listen for form submission
                    $(document).on('submit', '#formAdd_{{ $room->id }}', function (e) {
                        e.preventDefault(); // Stop the normal form submission

                        var form = $(this);
                        var url = form.attr('action');
                        var data = form.serialize();

                        // Perform AJAX request
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: data,
                            success: function (response) {
                                // Show success toast
                                showToast(response.success, true);

                                // Update the cart count dynamically
                                $.ajax({
                                    url: "{{ route('cart.count') }}", // Ensure this route returns the cart count
                                    success: function (count) {
                                        $('#cart-number-product').text(count);
                                    }
                                });
                            },
                            error: function (response) {
                                let message = response.responseJSON && response.responseJSON.error
                                    ? response.responseJSON.error
                                    : 'Có lỗi xảy ra, vui lòng thử lại.';
                                // Show error toast
                                showToast(message, false);
                            }
                        });
                    });

                    // Toast display function
                    function showToast(message, success) {
                        const toastBody = document.getElementById("toast-body-content");
                        const toastContainer = document.getElementById("dynamicToast");

                        // Update the toast message
                        toastBody.textContent = message;

                        // Change toast background color based on success or error
                        if (success) {
                            toastContainer.classList.add("bg-success", "text-white");
                            toastContainer.classList.remove("bg-danger");
                        } else {
                            toastContainer.classList.add("bg-danger", "text-white");
                            toastContainer.classList.remove("bg-success");
                        }

                        // Show the toast
                        const toastEl = new bootstrap.Toast(toastContainer, {
                            delay: 5000 // 5-second display
                        });
                        toastEl.show();
                    }

                        </script>

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
        // --------------------đồng bộ giá trị số phòng----------------
        document.addEventListener("DOMContentLoaded", function() {
            // Lắng nghe sự thay đổi trong từng trường nhập liệu số lượng phòng
            document.querySelectorAll('.room-card').forEach(function(roomCard) {
                const roomsInput = roomCard.querySelector('input[name^="rooms_"]');  // Lấy input theo tên riêng biệt của từng phòng

                roomsInput.addEventListener('change', function() {
                    const roomId = roomCard.querySelector('input[name^="rooms_"]').name.split('_')[1];  // Lấy id của phòng
                    const roomsValue = this.value;
                    
                    // Cập nhật trường ẩn tương ứng với mỗi phòng
                    roomCard.querySelectorAll('.roomsField').forEach(function(input) {
                        input.value = roomsValue;
                    });
                });
            });
        //----------------Số lượng nút thêm giá--------------------
            // Khi form Đặt ngay hoặc Thêm vào giỏ hàng được submit
            document.querySelectorAll("form").forEach(function(form) {
                form.addEventListener("submit", function() {
                    // Cập nhật trường ẩn với số lượng phòng tương ứng
                    form.querySelectorAll('input[name^="rooms_"]').forEach(function(input) {
                        const roomId = input.name.split('_')[1]; // Lấy id của phòng
                        const roomsValue = input.value;
                        
                        form.querySelectorAll(`.roomsField_${roomId}`).forEach(function(field) {
                            field.value = roomsValue;
                        });
                    });
                });
            });
        });

        // -------------------Ngày nhận và ngày trả--------------------
        document.addEventListener("DOMContentLoaded", function() {
            const checkinInput = document.querySelector("[name='check_in']");
            const checkoutInput = document.querySelector("[name='check_out']");
            const bookingForm = document.querySelector("form");

            // Định dạng ngày thành YYYY-MM-DD
            const formatDate = (date) => date.toISOString().split("T")[0];

            // Thiết lập giá trị mặc định & min cho input ngày
            checkinInput.value = formatDate(today);
            checkinInput.setAttribute("min", formatDate(today));

            checkoutInput.value = formatDate(tomorrow);
            checkoutInput.setAttribute("min", formatDate(today));

            //  Kiểm tra trước khi gửi form
            bookingForm.addEventListener("submit", function(event) {
                const checkinDate = new Date(checkinInput.value);
                const checkoutDate = new Date(checkoutInput.value);

                if (checkinDate >= checkoutDate) {
                    alert("❌ Ngày nhận phòng phải trước ngày trả phòng!");
                    event.preventDefault(); // Ngăn form gửi đi
                }
            });
        });
    </script>


@endsection