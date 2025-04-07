@extends('admin.layout.main')


@section('content')
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Sửa thông tin khách hàng</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sửa thông tin khách hàng</li>
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
                <div class="col-md-5">
                    <!--begin::Quick Example-->
                    <div class="card card-primary card-outline mb-4">
                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title">Sửa thông tin khách hàng</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Họ tên</label>
                                <input type="text" class="form-control"
                                       name="full_name" id="full_name"
                                       value="{{$customer->ten}}"
                                disabled
                                />
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Email</label>
                                <input type="email" name="email"
                                       value="{{$customer->email}}"
                                       class="form-control"
                                       id="exampleInputPassword1" disabled/>
                            </div>
                        </div>
                        <!--end::Body-->
                        <!--begin::Footer-->
                        <div class="card-footer">
                            <a href="{{route('admin.customers.index')}}" class="btn btn-secondary">Quay lại</a>
                            <a href="{{route('admin.customers.edit', $customer->id_taikhoan)}}" class="btn btn-warning">Cập nhật</a>
                        </div>
                        <!--end::Footer-->
                        <!--end::Form-->
                    </div>
                    <!--end::Quick Example-->
                </div>
                <!--end::Col-->

{{--                <div class="col-md-7">--}}
{{--                    <div class="card mb-4">--}}
{{--                        <div class="card-header">--}}
{{--                            <h3 class="card-title">Lịch sử đặt phòng</h3>--}}
{{--                        </div>--}}
{{--                        <!-- /.card-header -->--}}
{{--                        <div class="card-body">--}}
{{--                            <table class="table table-bordered">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th style="width: 10px">#</th>--}}
{{--                                    <th>Check in</th>--}}
{{--                                    <th>Check out</th>--}}
{{--                                    <th>Ngày đặt</th>--}}
{{--                                    <th>Trạng thái</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}

{{--                                   @foreach($customer->bookings as $key => $item)--}}
{{--                                       <tr class="align-middle">--}}
{{--                                           <td>{{ $key + 1 }}</td>--}}
{{--                                           <td>{{ $item-> check_in}}</td>--}}
{{--                                           <td>--}}
{{--                                               {{ $item->check_out }}--}}
{{--                                           </td>--}}
{{--                                           <td>--}}
{{--                                               {{ $item->booking_date }}--}}
{{--                                           </td>--}}
{{--                                           <td>{{ $item->status }}</td>--}}
{{--                                       </tr>--}}
{{--                                   @endforeach--}}

{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                        <!-- /.card-body -->--}}
{{--                        <div class="card-footer clearfix">--}}
{{--                            <ul class="pagination pagination-sm m-0 float-end">--}}
{{--                                {{$customers->links()}}--}}
{{--                                --}}{{--                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>--}}
{{--                                --}}{{--                                <li class="page-item"><a class="page-link" href="#">1</a></li>--}}
{{--                                --}}{{--                                <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                                --}}{{--                                <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                                --}}{{--                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection

