@extends('admin.layout.main')


@section('content')
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
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
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <form action="{{route('admin.bookings.update', $booking->id)}}" method="POST">
                @csrf
                @method('PUT')
                <!--begin::Row-->
                <div class="row g-4">

                    <!--begin::Col-->
                    <div class="col-md-6">
                        <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4">
                            <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Cập nhật thông tin khách hàng</div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Form-->

                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Họ tên</label>
{{--                                    <input type="hidden" name="customer_id">--}}
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                           name="full_name" id="full_name"
                                           value="{{$booking->customer->full_name}}"/>
                                    @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email"
                                           value="{{$booking->customer->email}}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"/>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" name="phone"
                                           value="{{$booking->customer->phone}}"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"/>
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <input type="text" name="nationality"
                                           value="{{$booking->customer->nationality}}"
                                           class="form-control @error('nationality') is-invalid @enderror"
                                           id="nationality"/>
                                    @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái đơn hàng</label>
                                    <select name="status"
                                           class="form-select @error('status') is-invalid @enderror"
                                            id="status">
                                        <option value="đang xử lý" {{ $booking->status == 'đang xử lý' ? 'selected' : '' }} >Đang xử lý</option>
                                        <option value="đã xác nhận" {{ $booking->status == 'đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="hủy" {{ $booking->status == 'hủy' ? 'selected' : '' }}>Hủy</option>
                                    </select>

                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"
                                        style="background-color: #B88A44; border-color: #B88A44;">Cập nhật
                                </button>
                            </div>
                            <!--end::Footer-->

                            <!--end::Form-->
                        </div>
                        <!--end::Quick Example-->
                    </div>

                    <div class="col-md-6">
                        <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4">
                            <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Cập nhật đơn hàng</div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Form-->

                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="check_in" class="form-label">Check in</label>
                                        <input type="datetime-local"
                                               class="form-control @error('check_in') is-invalid @enderror"
                                               name="check_in" id="check_in"
                                               value="{{ \Carbon\Carbon::parse($booking->check_in.  ' 00:00')->format('Y-m-d\TH:i') }}"/>
                                        @error('check_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="check_out" class="form-label">Check out</label>
                                        <input type="datetime-local" name="check_out"
                                               value="{{ \Carbon\Carbon::parse($booking->check_out.  ' 00:00')->format('Y-m-d\TH:i') }}"
                                               class="form-control @error('check_out') is-invalid @enderror"
                                               id="exampleInputPassword1"/>
                                        @error('check_out')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
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
                                                <tr class="align-middle" id="tr_room_{{$room->id}}">
                                                    <input type="hidden" name="roomBookingId[]" value="{{$room->id}}">
                                                    <td>{{$room->id}}.</td>
                                                    <td><img
                                                            src="{{\Illuminate\Support\Facades\Storage::url($room->file_anh)}}"
                                                            alt="" srcset="" width="100px">
                                                    </td>
                                                    <td>{{$room->room_type}}</td>
                                                    <td>{{$room->bed_type}}</td>
                                                    <td>{{$room->area}}</td>
                                                    <td>{{$room->view}}</td>
                                                    <td>{{number_format($room->price_per_night)}} VND</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger" type="button" data-id="{{$room->id}}" id="deleteRoomBooking"
                                                                onclick="return confirm('Bạn có muốn xóa phòng này không?')">
                                                            <i
                                                                class="fa-solid fa-trash"></i></button>

                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->

                            <!--end::Form-->
                        </div>
                        <!--end::Quick Example-->
                    </div>

                    <!--end::Col-->
                </div>
            </form>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection

@section('script')
    <script !src="">
        $('#deleteRoomBooking').click(function () {
            let trRoomId = $(this).data('id');
            $(`#tr_room_${trRoomId}`).remove();
        })
    </script>
@endsection

