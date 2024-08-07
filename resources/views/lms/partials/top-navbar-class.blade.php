<nav class="navbar navbar-expand-lg main-navbar">
    <a href="{{ route('lms.course.mycourse') }}" class="btn btn-outline-primary navbar-brand sidebar-gone-hide"><i
            class="fas fa-arrow-left"></i>
    </a>
    <a href="index.html" class="navbar-brand sidebar-gone-hide">{{ $course->course_name }} ({{
        $course->detail_course->total_hours }} JP)</a>
    {{-- <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a> --}}
    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg sidebar-gone-show"><i class="fas fa-bars"
            aria-hidden="true"></i></a>
    <a href="{{ route('lms.course.mycourse') }}" class="nav-link nav-link-lg sidebar-gone-show"><i
            class="fas fa-arrow-left"></i> Kembali</a>
    <form class="form-inline ml-auto">
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <div class="d-sm-none d-lg-inline-block">Hai, {{ Auth::user()->user_name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">Anda Aktif (Logged In)</div>
                <a href="{{ route(  'user.profile') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                <a href="{{ route('user.activities') }}" class="dropdown-item has-icon">
                    <i class="fas fa-bolt"></i> Activities
                </a>
                <a href="{{ route('role.index') }}" class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST" id="logout_submit_top">
                    @csrf
                    <button type="submit" id="logout_top"
                        class="dropdown-item d-flex align-items-center text-danger logout">
                        <i class="fas fa-sign-out-alt pl-1 pr-2"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>