@extends('layout.main')


@section('title', 'Giỏ hàng')


@section('content')


   <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="style.css">
    <div class="infor-container-right">
    @if(session('error'))
    <div class="alert alert-danger"  style="padding: 30px; margin-top: 40px; margin-bottom: 20px;">
        {{ session('error') }}
    </div>
@endif
@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var toastEl = document.getElementById("liveToast");
        var toast = new bootstrap.Toast(toastEl, {
            delay: 8000 // Hiển thị trong 8 giây
        });
        toast.show();
    });
</script>
@endif
<h2 class="mt-4 mb-3" style="padding: 5%; padding-bottom:10px;text-align: center;">
        GIỎ HÀNG CỦA BẠN
    </h2>

    <div class="container">
    @foreach ($shoppingCart as $index => $room)
    <div class="row mb-3 p-3 border rounded bg-light" style="min-height: 180px; display: flex; align-items: center;">
        <div class="col-md-3 d-flex align-items-center justify-content-center">

            <img src="{{ asset('room_img/'.$room['file_anh']) }}" alt="Room Image" class="img-fluid rounded" style="object-fit: cover;">
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold">Phòng: {{ $room['room_type'] }}</h5>
                    <p class="mb-1">Ngày check-in: {{ $room['check_in'] }}</p>
                    <p class="mb-1">Ngày check-out: {{ $room['check_out'] }}</p>
                    <p class="mb-1">Giá phòng/đêm: {{ number_format($room['price_per_night']) }} VND</p>

                    <p>
                        @if($room['discount_percent'] > 0)
                            <span class="badge bg-success">{{ $room['discount_percent'] }}% OFF</span>
                            <span class="text-decoration-line-through">{{ number_format($room['price_per_night']) }} VND</span>
                            <br>
                            <span class="fw-bold">{{ number_format($room['discounted_price']) }} VND/đêm</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-3 text-center">
                    <label class="fw-bold">Số lượng</label>
                    <form action="{{ route('cart.update', $index) }}" method="POST" class="d-flex align-items-center justify-content-center">
                        @csrf
                        <input type="number" name="rooms" value="{{ old('rooms', $room['rooms'] ?? 1) }}" min="1" max="5" class="form-control w-25 text-center" style="max-width: 100px;" onchange="this.form.submit()">
                    </form>
                    <a href="{{ route('cart.remove', $index) }}" class="d-block text-decoration-underline text-dark mt-4" 
                    style="color: #B88A44 !important;" onclick="return confirm('Bạn có chắc muốn xóa phòng này khỏi giỏ hàng?');">Xóa</a>
                </div>
                <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                    <p class="fw-bold text-primary fs-5 mb-0">{{ number_format($room['room_total']) }} VND</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3 container p-0">
        <div class="row justify-content-end align-items-center mx-0">
            <div class="col-auto text-end">
                <p class="fw-bold text-dark fs-4 mb-0">
                    Tổng tiền: {{ number_format(array_sum(array_column(session('bookedRooms', []), 'room_total'))) }} VND
                </p>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        <div class="d-flex justify-content-between">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Quay lại</a>
            <a href="{{ route('showBooking') }}" class="btn btn-primary">Chuyển sang trang đặt phòng</a>
        </div>
    </div>

@endsection