<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand" style="background-color: #B88A44; ">
        <!--begin::Brand Link-->
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <!--begin::Brand Text-->
            <h1 class="brand-text fw-light ">Golden Tree</h1>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper" style="background-color: #B88A44; ">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-light">
                        <i class="nav-icon bi bi-palette"></i>
                        <p> Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.bookings.index')}}" class="nav-link text-light">
                        <i class="nav-icon bi bi-palette"></i>
                        <p>Bookings</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.rooms.index')}}" class="nav-link text-light">
                        <i class="nav-icon bi bi-palette"></i>
                        <p>Rooms</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.customers.index')}}" class="nav-link text-light">
                        <i class="nav-icon bi bi-palette"></i>
                        <p>Customer</p>
                    </a>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
