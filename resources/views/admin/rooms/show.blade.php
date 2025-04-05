@extends('admin.layout.main')


@section('content')
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Xem thông tin phòng</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Xem thông tin phòng</li>
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
            <!--begin::Row-->
            <div class="row g-4">
                <!--begin::Col-->
                <div class="col-md-12">
                    <!--begin::Quick Example-->
                    <div class="card card-primary card-outline mb-4">
                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title">Thêm phòng mới</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Room type</label>
                                    <input type="text" class="form-control @error('room_type') is-invalid @enderror"
                                           name="room_type" value="{{$room->room_type}}" id="exampleInputEmail1"
                                           aria-describedby="emailHelp" readonly disabled/>
                                    @error('room_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="input-group mb-3">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($room->file_anh) }}" alt="" width="100" height="100">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Bed type</label>
                                    <input type="text" name="bed_type"
                                           value="{{$room->bed_type}}"
                                           class="form-control @error('bed_type') is-invalid @enderror"
                                           id="exampleInputPassword1" readonly disabled/>
                                    @error('bed_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Area</label>
                                    <input type="text" name="area"
                                           value="{{$room->area}}"
                                           class="form-control @error('area') is-invalid @enderror"
                                           id="exampleInputPassword1" readonly disabled/>
                                    @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">View</label>
                                    <input type="text" name="view"
                                           value="{{$room->view}}"
                                           class="form-control @error('view') is-invalid @enderror"
                                           id="exampleInputPassword1" readonly disabled/>
                                    @error('view')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Price per night</label>
                                    <input type="text" name="price_per_night"
                                           value="{{$room->price_per_night}}"
                                           class="form-control @error('price_per_night') is-invalid @enderror"
                                           id="exampleInputPassword1" readonly disabled/>
                                    @error('price_per_night')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Max capacity</label>
                                    <input type="text" name="max_capacity"
                                           value="{{$room->capacity->max_capacity}}"
                                           class="form-control @error('max_capacity') is-invalid @enderror"
                                           id="exampleInputPassword1" readonly disabled/>
                                    @error('max_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Description</label>
                                    <textarea name="description" id=""
                                              class="form-control @error('description') is-invalid @enderror"
                                              rows="10" readonly disabled>{!! $room->description !!}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer">
                                <a href="{{route('admin.rooms.index')}}" class="btn btn-primary">Back</a>
                            </div>
                            <!--end::Footer-->

                    </div>
                    <!--end::Quick Example-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection
