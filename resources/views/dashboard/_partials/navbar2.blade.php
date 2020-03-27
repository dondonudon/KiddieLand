<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" id="sidebarToggle" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
{{--            @yield('page_menu')--}}
            @if(!in_array(request()->segment(2),[null,'profile']) && !in_array(request()->segment(3),['tiket-offline']))
                {{-- <li class="nav-item {{ (request()->segment(4) == null) ? 'active' : '' }}">
                    <a href="{{ url(request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3)) }}" class="nav-link">
                        <i class="fas fa-plus-circle mr-2" style="color: #ffffff; font-size: x-large; vertical-align: middle;"></i>
                        <div class="d-none d-lg-inline-block d-xl-inline-block">Tambah {{ ucfirst(request()->segment(3)) }}</div>
                    </a>
                </li>
                <li class="nav-item {{ (request()->segment(4) == 'list') ? 'active' : '' }}">
                    <a href="{{ url(request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3)) }}/list" class="nav-link">
                        <i class="fas fa-table mr-2" style="color: #ffffff; font-size: x-large; vertical-align: middle;"></i>
                        <span class="d-none d-lg-inline-block d-xl-inline-block">
                             Daftar {{ ucfirst(request()->segment(3)) }}
                        </span>
                    </a>
                </li> --}}
            @endif
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('assets/img/avatar/avatar-5.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">{{ request()->session()->get('name') }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                {{--                <div class="dropdown-title">Logged in 5 min ago</div>--}}
                <div class="dropdown-title">Account</div>
                <a href="{{ url('dashboard/profile') }}" class="dropdown-item has-icon">
                    <i class="fas fa-user"></i> Profile
                </a>
                {{--                <a href="features-settings.html" class="dropdown-item has-icon">--}}
                {{--                    <i class="fas fa-cog"></i> Settings--}}
                {{--                </a>--}}
                <div class="dropdown-divider"></div>
                <a href="#" id="btnLogout" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
