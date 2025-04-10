@extends('layout.main')


@section('title', 'Thông Tin Khách Hàng')




@section('content')
    @include('layout.reuse.steps', ['step' => 1]) <!-- Hiển thị các bước -->
    @include('layout.reuse.count-time') <!-- Nhúng đồng hồ đếm ngược -->





    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="container">
        <div class="infor-container-right">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                    <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert"
                         aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                        </div>
                    </div>
                </div>


                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        var toastEl = document.getElementById("liveToast");
                        var toast = new bootstrap.Toast(toastEl, {
                            delay: 8000 // Hiển thị trong 8 giây
                        });
                        toast.show();
                    });
                </script>
            @endif
            <h2>Thông tin đặt phòng</h2>


            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th>Loại phòng</th>
                    <th>Ngày check-in</th>
                    <th>Ngày check-out</th>
                    <th>Số phòng</th> <!-- New column for number of rooms -->
                    <th>Giá phòng/đêm</th>
                    <th>Giảm giá</th>
                    <th>Giá phòng sau khi giảm</th>
                    <th>Thành tiền</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($bookedRooms as $index => $room)
                    <tr>
                        <td>{{ $room['room_type'] }}</td>
                        <td>{{ $room['check_in'] }}</td>
                        <td>{{ $room['check_out'] }}</td>
                        <td> {{ $room['rooms'] ?? 1 }}  </td>
                        <td>{{ number_format($room['price_per_night']) }} VND</td>
                        <td>
                            @if($room['discount_percent'] > 0)
                                <span class="badge bg-success">{{ $room['discount_percent'] }}% OFF</span>
                            @else
                                <span class="badge bg-secondary">Không giảm giá</span>
                            @endif
                        </td>
                        <td>
                            @if($room['discount_percent'] > 0)
                                <span class="text-muted text-decoration-line-through">
                                {{ number_format($room['price_per_night']) }} VND
                            </span>
                                <br>
                                <span class="fw-bold">{{ number_format($room['discounted_price']) }} VND/đêm</span>
                            @else
                                {{ number_format($room['price_per_night']) }} VND
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-primary">{{ number_format($room['room_total']) }} VND</span>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>


            <div class="mt-3">
                <h4>Tổng tiền: <span class="fw-bold text-danger">{{ number_format($totalAmount) }} VND</span></h4>
            </div>
        </div>
    </div>
    <form action="{{ route('saveBookingWithCustomerInfo') }}" method="POST">
        @csrf
        <!-- Thông tin khách hàng -->
        <div class="infor-container-left mx-auto" style="margin:10px;">


            <h2>Điền thông tin đặt phòng</h2>
            <div class="mb-3">
                <label for="fullName" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="ho_ten" required maxlength="255">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" required maxlength="255">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại</label>
                <input type="tel" class="form-control" name="sdt" pattern="[0-9]{10,15}" maxlength="15">
            </div>
            <div class="mb-3">
                <label for="nationality" class="form-label">Quốc tịch <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nationality" required maxlength="100">
            </div>
            <div class="mb-3">
                <label for="payment_method" class="form-label">Phương thức thanh toán
                    <span class="text-danger">*</span>
                </label>
                <select name="payment_method" class="form-control" required>
                    <option value="CASH">Thanh toán tại quầy</option>
                    <option value="VNPAY">Thanh toán qua VNPAY</option>
                </select>
            </div>


            <!-- Thông tin phòng (ẩn nếu không cần chỉnh sửa) -->
            @foreach ($bookedRooms as $index => $room)
                <input type="hidden" name="rooms[{{ $index }}][room_type]" value="{{ $room['room_type'] }}">
                <input type="hidden" name="rooms[{{ $index }}][check_in]" value="{{ $room['check_in'] }}">
                <input type="hidden" name="rooms[{{ $index }}][check_out]" value="{{ $room['check_out'] }}">
                <input type="hidden" name="rooms[{{ $index }}][rooms]" value="{{ $room['rooms'] }}">
                <input type="hidden" name="rooms[{{ $index }}][price_per_night]" value="{{ $room['price_per_night'] }}">
                <input type="hidden" name="rooms[{{ $index }}][discount_percent]"
                       value="{{ $room['discount_percent'] }}">
            @endforeach


            <button type="submit" class="btn btn-primary">Tiếp tục</button>
    </form>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
