@extends('admin.layout.main')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Cập nhật đơn hàng</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cập nhật đơn hàng</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <div class="card-title">Cập nhật thông tin khách hàng</div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Họ tên</label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                           name="full_name" id="full_name"
                                           value="{{ $booking->customer->full_name }}">
                                    @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email"
                                           value="{{ $booking->customer->email }}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" name="phone"
                                           value="{{ $booking->customer->phone }}"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <input type="text" name="nationality"
                                           value="{{ $booking->customer->nationality }}"
                                           class="form-control @error('nationality') is-invalid @enderror"
                                           id="nationality">
                                    @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái đơn hàng</label>
                                    <select name="status" id="status"
                                            class="form-select @error('status') is-invalid @enderror">
                                        <option value="đang xử lý" {{ $booking->status == 'đang xử lý' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="đã xác nhận" {{ $booking->status == 'đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="hủy" {{ $booking->status == 'hủy' ? 'selected' : '' }}>Hủy</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @foreach($booking->rooms as $room)
                                <input type="hidden" name="roomBookingId[]" value="{{ $room->id }}">
                            @endforeach
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"
                                        style="background-color: #B88A44; border-color: #B88A44;">Cập nhật
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-primary card-outline mb-4">
                            <div class="card-header">
                                <div class="card-title">Cập nhật đơn hàng</div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="check_in" class="form-label">Check in</label>
                                    <input type="datetime-local"
                                           class="form-control @error('check_in') is-invalid @enderror"
                                           name="check_in" id="check_in"
                                           value="{{ \Carbon\Carbon::parse($booking->check_in . ' 00:00')->format('Y-m-d\TH:i') }}">
                                    @error('check_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="check_out" class="form-label">Check out</label>
                                    <input type="datetime-local" name="check_out"
                                           value="{{ \Carbon\Carbon::parse($booking->check_out . ' 00:00')->format('Y-m-d\TH:i') }}"
                                           class="form-control @error('check_out') is-invalid @enderror"
                                           id="check_out">
                                    @error('check_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Room type</th>
                                        <th>Bed type</th>
                                        <th>Area</th>
                                        <th>View</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($booking->rooms as $room)
                                        <tr class="align-middle" id="tr_room_{{ $room->id }}">
                                            <input type="hidden" name="roomBookingId[]" value="{{ $room->id }}">
                                            <td>{{ $room->id }}</td>
                                            <td><img src="{{ \Illuminate\Support\Facades\Storage::url($room->file_anh) }}"
                                                     width="100px" alt=""></td>
                                            <td>{{ $room->room_type }}</td>
                                            <td>{{ $room->bed_type }}</td>
                                            <td>{{ $room->area }}</td>
                                            <td>{{ $room->view }}</td>
                                            <td>{{ number_format($room->price_per_night) }} VND</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#roomSelectModal">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <!-- Modal chọn phòng -->
                                <div class="modal fade" id="roomSelectModal" tabindex="-1" aria-labelledby="roomSelectModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Chọn phòng mới</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>Chọn</th>
                                                        <th>Loại phòng</th>
                                                        <th>Giường</th>
                                                        <th>Giá</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($rooms as $room)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="roomIds[]" value="{{ $room->id }}"
                                                                    {{ $booking->rooms->contains($room->id) ? 'checked' : '' }}>
                                                            </td>
                                                            <td>{{ $room->room_type }}</td>
                                                            <td>{{ $room->bed_type }}</td>
                                                            <td>{{ number_format($room->price_per_night) }} VND</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                <button type="button" class="btn btn-primary" id="saveRoomSelection">Lưu phòng</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Thông báo chọn phòng -->
            <div id="roomSelectionAlert" class="alert alert-success mt-3 d-none" role="alert">
                Danh sách phòng đã được cập nhật thành công!
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const roomData = @json($rooms);
        document.getElementById('saveRoomSelection').addEventListener('click', function () {
            const selectedRoomIds = [];
            const roomData = @json($rooms); // Truyền dữ liệu phòng từ server sang JS

            // Lấy danh sách checkbox được chọn
            document.querySelectorAll('#roomSelectModal input[name="roomIds[]"]:checked').forEach(checkbox => {
                selectedRoomIds.push(parseInt(checkbox.value));
            });

            // Xóa các input ẩn cũ
            document.querySelectorAll('input[name="roomBookingId[]"]').forEach(input => input.remove());

            const form = document.querySelector('form');

            // Xóa nội dung bảng hiện tại
            const tableBody = document.querySelector('tbody');
            tableBody.innerHTML = '';

            // Tạo lại bảng hiển thị và input ẩn tương ứng
            selectedRoomIds.forEach(id => {
                const room = roomData.find(r => r.id === id);
                if (room) {
                    // Tạo input ẩn
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'roomBookingId[]';
                    input.value = id;
                    form.appendChild(input);

                    // Tạo hàng bảng
                    const row = document.createElement('tr');
                    row.classList.add('align-middle');
                    row.id = `tr_room_${room.id}`;
                    row.innerHTML = `
        <td>${room.id}</td>
        <td><img src="/storage/${room.file_anh}" width="100px" alt=""></td>
        <td>${room.room_type}</td>
        <td>${room.bed_type}</td>
        <td>${room.area}</td>
        <td>${room.view}</td>
        <td>${parseInt(room.price_per_night).toLocaleString('vi-VN')} VND</td>
        <td>
          <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#roomSelectModal">
            <i class="fa-solid fa-pen-to-square"></i>
          </button>
        </td>
      `;
                    tableBody.appendChild(row);
                }
            });

            // Hiển thị thông báo
            const alertBox = document.getElementById('roomSelectionAlert');
            alertBox.classList.remove('d-none');
            setTimeout(() => alertBox.classList.add('d-none'), 3000);

            // Đóng modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('roomSelectModal'));
            modal.hide();
        });

    </script>
@endsection
