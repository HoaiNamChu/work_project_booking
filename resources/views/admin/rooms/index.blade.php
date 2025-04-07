@extends('admin.layout.main')

@section('content')
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Danh sách phòng</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách phòng</li>
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
            <div class="row mb-3">
                <div class="col-md-12">
                    <a href="{{route('admin.rooms.create')}}" class="btn btn-primary"
                       style="background-color: #B88A44; border-color: #B88A44;"><i class="fa-solid fa-plus"></i>Thêm
                        phòng mới</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Danh sách phòng</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Image</th>
                                    <th>Room type</th>
                                    <th>Bed type</th>
                                    <th>Area</th>
                                    <th>View</th>
                                    <th>Max capacity</th>
                                    <th>Price</th>
                                    <th>Remaining room</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rooms as $room)
                                    <tr class="align-middle">
                                        <td>{{$room->id}}.</td>
                                        <td><img src="{{\Illuminate\Support\Facades\Storage::url($room->file_anh)}}"
                                                 alt="" srcset="" width="100px">
                                        </td>
                                        <td>{{$room->room_type}}</td>
                                        <td>{{$room->bed_type}}</td>
                                        <td>{{$room->area}}</td>
                                        <td>{{$room->view}}</td>
                                        <td>{{$room->capacity->max_capacity}}</td>
                                        <td>{{number_format($room->price_per_night)}} VND</td>
                                        <td>{{$room->remaining_rooms}}</td>
                                        <td>
                                            <a href="{{route('admin.rooms.show', $room->id)}}"
                                               class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>
                                            <a href="{{route('admin.rooms.edit', $room->id)}}"
                                               class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <form action="{{route('admin.rooms.destroy', $room->id)}}" class="d-inline"
                                                  method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit"
                                                        onclick="return confirm('Bạn có muốn xóa phòng này không?')"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-end">
                                {{$rooms->links()}}
                                {{--                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>--}}
                                {{--                                <li class="page-item"><a class="page-link" href="#">1</a></li>--}}
                                {{--                                <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
                                {{--                                <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
                                {{--                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>--}}
                            </ul>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection
