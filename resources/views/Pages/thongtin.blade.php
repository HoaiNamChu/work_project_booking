@extends("Layout.main")

@section('title', 'Thông Tin Khách Hàng')

@section('content')
    @include('layout.reuse.steps', ['step' => 1]) <!-- Hiển thị các bước -->
    @include('layout.reuse.count-time') <!-- Nhúng đồng hồ đếm ngược -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="container">
        <div class="infor-container-right">
            <h2>Thông tin đặt phòng</h2>
            <table class="table table-bordered">
                <thead class="table-light">
                <tr>
                    <th>Loại phòng</th>
                    <th>Ngày check-in</th>
                    <th>Ngày check-out</th>
                    <th>Số người</th>
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
                        <td>{{ ($room['adults'] ?? 0) + ($room['children'] ?? 0) }}</td>
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
                        <td>
                            <a href="{{ route('xoaPhong', $index) }}" class="btn btn-danger btn-sm">Xóa</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!-- Hiển thị tổng tiền -->
            <div class="mt-3">
                <h4>Tổng tiền: <span class="fw-bold text-danger">{{ number_format($totalAmount) }} VND</span></h4>
            </div>

            <!-- Nút quay lại để thêm phòng -->
            <div class="mt-3">
                <a href="{{ route('home') }}" class="btn btn-primary">Thêm phòng</a>
            </div>
        </div>
        <!-- Thông tin khách hàng -->
        <div class="infor-container-left" style="margin:10px;">
            <h2>Điền thông tin đặt phòng</h2>
            <form action="{{ route('saveCustomerInfo') }}" method="POST">
                @csrf
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
                    <label for="payment_method" class="form-label">Phương thức thanh toán <span
                            class="text-danger">*</span></label>
                    <select name="payment_method" class="form-control" required>
                        <option value="CASH">Thanh toán tại quầy</option>
                        <option value="VNPAY">Thanh toán qua VNPAY</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('showBooking') }}" class="btn btn-outline-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-primary">Tiếp tục thanh toán</button>
                </div>
            </form>

        </div>


        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
