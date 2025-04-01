@extends('admin.layout.main')

@section('content')
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Simple Tables</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Simple Tables</li>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Bordered Table</h3>
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
                                    <th>Price</th>
                                    <th>Remaining room</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($rooms as $room)
                                    <tr class="align-middle">
                                        <td>{{$room->id}}.</td>
                                        <td><img src="/room_img/{{$room->file_anh}}" alt="" srcset="" width="100px"></td>
                                        <td>{{$room->room_type}}</td>
                                        <td>{{$room->bed_type}}</td>
                                        <td>{{$room->area}}</td>
                                        <td>{{$room->view}}</td>
                                        <td>{{number_format($room->price_per_night)}} VND</td>
                                        <td>{{$room->remaining_rooms}}</td>
                                        <td>
                                            <button class="btn btn-info">Show</button>
                                            <button class="btn btn-warning">Update</button>
                                            <button class="btn btn-danger">Delete</button>
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
