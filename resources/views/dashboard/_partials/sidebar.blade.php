@php
    $sidebar = \App\Http\Controllers\c_Dashboard::sidebar();
    $segGroup = request()->segment(2);
    $segMenu = request()->segment(3);
@endphp
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}">{{ config('app.name') }}{{ config('app.name1') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/logo/without-name/logo-1000.png') }}" style="width: 100%" alt="logo">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="nav-item dropdown">
                <a href="{{ url('/') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            @foreach($sidebar as $g)
                @if($g['group']['segment_name'] == $segGroup)
                    @php($statGroup = 'active')
                @else
                    @php($statGroup = '')
                @endif
                <li class="nav-item dropdown {{ $statGroup }}">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="{{ $g['group']['icon'] }}"></i>
                        <span>{{ $g['group']['name'] }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($g['menu'] as $m)
                            @if($m['segment_name'] == $segMenu)
                                @php($statMenu = 'active')
                            @else
                                @php($statMenu = '')
                            @endif
                            <li class="{{ $statMenu }}">
                                <a class="nav-link" href="{{ url($m['url']) }}">{{ $m['name'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </aside>
</div>
