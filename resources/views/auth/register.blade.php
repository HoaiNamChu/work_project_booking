@extends('auth.layout')


@section('content')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{route('home')}}"><b style="color: #B88A44;font-size: 70px;">Golden Tree</b></a>
        </div>
        <!-- /.register-logo -->
        <div class="card">
            <div class="card-body register-card-body">
                <p class="register-box-msg">Register a new membership</p>
                <form action="{{route('register')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="full_name" placeholder="Full Name"/>
                        <div class="input-group-text"><span class="bi bi-person"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email"/>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password"/>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-8">
                            <p class="mb-0">
                                <a href="{{route('login')}}" class="text-center"> I already have a membership </a>
                            </p>
{{--                            <div class="form-check">--}}
{{--                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"/>--}}
{{--                                <label class="form-check-label" for="flexCheckDefault">--}}
{{--                                    I agree to the <a href="#">terms</a>--}}
{{--                                </label>--}}
{{--                            </div>--}}
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary"
                                        style="background-color: #B88A44; border-color: #B88A44;">Register
                                </button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>
            </div>
            <!-- /.register-card-body -->
        </div>
    </div>
@endsection
