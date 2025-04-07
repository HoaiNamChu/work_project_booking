@extends('admin.layout.main')


@section('content')
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Sửa thông tin phòng</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sửa thông tin phòng</li>
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
                        <form action="{{route('admin.rooms.update', $room->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Room type</label>
                                    <input type="text" class="form-control @error('room_type') is-invalid @enderror"
                                           name="room_type" value="{{$room->room_type}}" id="exampleInputEmail1"
                                           aria-describedby="emailHelp"/>
                                    @error('room_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" name="image" id="inputGroupFile02"/>
                                    <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Bed type</label>
                                    <input type="text" name="bed_type"
                                           value="{{$room->bed_type}}"
                                           class="form-control @error('bed_type') is-invalid @enderror"
                                           id="exampleInputPassword1"/>
                                    @error('bed_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Area</label>
                                    <input type="text" name="area"
                                           value="{{$room->area}}"
                                           class="form-control @error('area') is-invalid @enderror"
                                           id="exampleInputPassword1"/>
                                    @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">View</label>
                                    <input type="text" name="view"
                                           value="{{$room->view}}"
                                           class="form-control @error('view') is-invalid @enderror"
                                           id="exampleInputPassword1"/>
                                    @error('view')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Price per night</label>
                                    <input type="text" name="price_per_night"
                                           value="{{$room->price_per_night}}"
                                           class="form-control @error('price_per_night') is-invalid @enderror"
                                           id="exampleInputPassword1"/>
                                    @error('price_per_night')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Max capacity</label>
                                    <input type="text" name="max_capacity"
                                           value="{{$room->capacity->max_capacity}}"
                                           class="form-control @error('max_capacity') is-invalid @enderror"
                                           id="exampleInputPassword1"/>
                                    @error('max_capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Description</label>
                                    <textarea name="description" id=""
                                              class="form-control @error('description') is-invalid @enderror"
                                              rows="10">{!! $room->description !!}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" style="background-color: #B88A44; border-color: #B88A44;">Update</button>
                            </div>
                            <!--end::Footer-->
                        </form>
                        <!--end::Form-->
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
