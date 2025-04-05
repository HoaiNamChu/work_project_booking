@extends('layout.main')
@section('title', 'Chi Tiết Thanh Toán')

@section('content')
    <style>
        .tab-thongtinkhachhang, .tab-chitietthanhtoan {
            padding: 1rem;
            background-color: white;
            border-radius: 10px;
        }

        .phandau {
            background: linear-gradient(135deg, #b88a44 0%, #8b6b2f 100%);
            padding: 20px 0;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 10vh;
        }

        .phandau h1 {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 30px;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .phandaua {
            display: flex;
            justify-content: center;
            gap: 100px;
            margin-top: 40px;
            position: relative;
        }

        .phandaua::before {
            content: "";
            position: absolute;
            top: 17px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 2px;
            background-color: rgba(255, 255, 255, 0.3);
            z-index: 0;
        }

        .phandaua1 {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 1;
        }

        .phandaua1 span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #b88a44; /* Màu nền mặc định cho số 1 và 3 */
            color: white; /* Màu chữ mặc định cho số 1 và 3 */
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .phandaua1:hover span {
            transform: scale(1.1);
        }

        .phandaua1.active span {
            background-color: #b88a44;
            color: white;
        }

        .active span {
            background-color: white !important;
            color: #b88a44 !important;
        }

        /* Chỉnh màu số 2 thành #b88a44 */
        .phandaua1:nth-child(1) span {
            background-color: white;
            color: #b88a44;
        }

        .phandaua1 p {
            font-size: 1.2rem;
            font-weight: 500;
            color: white;
            text-align: center;
        }

        .phanthanmot {
            text-align: center;
            padding: 20px;
            background-color: white;
            margin: 30px auto;
            max-width: 900px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .phanthanmot p {
            text-align: center;
            font-size: 1.3rem;
            color: #e74c3c;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .complete-payment {
            text-align: center;
            margin-top: 20px;
        }

        .btn-complete {
            background-color: #4CAF50; /* Màu xanh */
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-complete:hover {
            background-color: #45a049; /* Màu xanh đậm khi hover */
        }
    </style>
    <div class="phandau">
        <div class="container">
            <div class="phandaua">
                <!-- Bước 1 -->
                <div class="phandaua1 active">
                    <span>1</span>
                    <p>Thông tin khách hàng</p>
                </div>
                <!-- Bước 2 -->
                <div class="phandaua1 active">
                    <span>2</span>
                    <p>Chi tiết thanh toán</p>
                </div>
                <!-- Bước 3 -->
                <div class="phandaua1">
                    <span>3</span>
                    <p>Xác nhận đặt phòng</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Đồng hồ đếm ngược -->
        <div class="phanthanmot">
            <p>Thời gian còn lại: <span id="countdown-timer"></span></p>
        </div>
    </div>

    <script>
        // Đồng hồ đếm ngược
        let timer = 5 * 60; // Đặt thời gian là 5 phút (5 * 60 giây)
        const countdownElement = document.getElementById("countdown-timer");

        function updateCountdown() {
            const minutes = Math.floor(timer / 60); // Chia số phút
            const seconds = timer % 60; // Lấy phần dư cho số giây
            countdownElement.textContent = `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`; // Hiển thị dưới dạng mm:ss

            if (timer > 0) {
                timer--; // Giảm dần thời gian
            } else {
                clearInterval(countdownInterval); // Dừng bộ đếm
                alert("Thời gian đã hết, vui lòng thử lại!"); // Thông báo khi hết thời gian
            }
        }

        // Cập nhật đồng hồ mỗi giây
        const countdownInterval = setInterval(updateCountdown, 1000);
    </script>

    <div class="thongtinthanhtoan container">
        <form action="{{route('payment')}}" method="post">
            @csrf
            <div class="row mb-3">

                <div class="col-md-8">
                    <div class="tab-thongtinkhachhang ">
                        <div class="mb-3">
                            <label for="" class="form-label">Họ tên:</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Số điện thoại:</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Check in:</label>
                            <input type="datetime-local" id="checkIn" name="check_in" onchange="calculAmount()" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Check out:</label>
                            <input type="datetime-local" id="checkOut" onchange="calculAmount()" name="check_out" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tab-chitietthanhtoan">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Ảnh</th>
                                <th>Kiểu phòng</th>
                                <th>Loại giường</th>
                                <th>Diện tích</th>
                                <th>View</th>
                                <th>Gía</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><img src="{{ $room->image_url }}" alt="" width="50" height="50"></td>
                                <td>{{$room->room_type}}</td>
                                <td>{{$room->bed_type}}</td>
                                <td>{{$room->area}} m2</td>
                                <td>{{$room->view}}</td>
                                <td>{{number_format($room->price_per_night)}} VNĐ</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="mb-3">
                            <label for="" class="form-label">Phương thức thanh toán:</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="CASH">Thanh toán bằng tiền mặt</option>
                                <option value="VNPAY">Thanh toán qua VNPAY</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <p>Tổng tiền: <span id="total_price">0</span> VNĐ</p>
                            <input type="hidden" name="total_price" value="">
                            <input type="hidden" name="room_id" value="{{$room->id}}">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn-complete">Đặt phòng</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <script !src="">

        function calculAmount(){
            let checkIn = new Date(document.getElementById('checkIn').value);
            let checkOut = new Date(document.getElementById('checkOut').value);
            let roomPrice = {{$room->price_per_night}};
            let timeStay =  (((checkOut-checkIn)/1000)/60)/60;

            if (timeStay < 24 && timeStay >= 1){
                document.getElementsByName('total_price').value = roomPrice;
                console.log(document.getElementsByName('total_price'))

               document.getElementById('total_price').innerHTML = new Intl.NumberFormat('vi-VN', {
                   style: 'currency',
                   currency: 'VND',
               }).format(roomPrice);
            }else {
                document.getElementsByName('total_price').value = roomPrice * (Math.ceil(timeStay/24));

                document.getElementById('total_price').innerHTML = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND',
                }).format(roomPrice * (Math.ceil(timeStay/24)));
            }


        }

    </script>
@endsection
