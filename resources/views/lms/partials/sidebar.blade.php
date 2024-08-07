<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}">ABANGKOMANDAN</a>
    </div>

    <div class="px-3 pb-2 hide-sidebar-mini btn-group btn-block btn-icon-split">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary" aria-current="page"> <i
                class="fa fa-file-text-o" aria-hidden="true"></i>
            Laporan</a>
        <a href="{{ route('lms.index') }}" class="btn btn-primary"><i class="fa-solid fa-book-open"></i> LMS</a>
    </div>

    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/img/favicon.ico') }}" style="width: 24px" alt="">
        </a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Beranda</li>
        <li class="{{ request()->routeIs('lms.index') ? 'active' : '' }}"><a class="nav-link menu"
                href="{{ route('lms.index') }}"><i class="fas fa-fire"></i>
                <span>Dashboard</span></a>
        </li>
        <li class="menu-header">Menu Utama</li>

        <li class="{{ request()->routeIs('lms.course.mycourse') ? 'active' : '' }}"><a class="menu nav-link"
                href="{{ route('lms.course.mycourse') }}"><i class="fas fa-chalkboard-teacher"></i>
                <span>Kursus di Ikuti</span></a>
        </li>
        <li
            class="{{ request()->routeIs('lms.course.*') && !request()->routeIs('lms.course.mycourse') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('lms.course.index') }}"><i class="fas fa-list"></i>
                <span>Daftar Kursus</span></a>
        </li>
        @can('lms-list')
        {{-- ADMINISTRATOR --}}
        <li class="menu-header">Administrator</li>
        <li class="{{ request()->routeIs('lms.admin.category.*') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('lms.admin.category.index') }}"><i class="fas fa-layer-group"></i>
                <span>Manajemen Kategori</span></a>
        </li>
        <li class="{{ request()->routeIs('lms.admin.course.*') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('lms.admin.course.index') }}"><i class="fas fa-book"></i>
                <span>Manajemen Kursus</span></a>
        </li>
        <li class="{{ request()->routeIs('lms.admin.module.*') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('lms.admin.module.index') }}"><i class="fas fa-list-check"></i>
                <span>Manajemen Modul</span></a>
        </li>
        <li class="{{ request()->routeIs('lms.admin.lesson.*') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('lms.admin.lesson.index') }}"><i
                    class="fas fa-book-open-reader"></i>
                <span>Manajemen Pelajaran</span></a>
        </li>
        <li
            class="{{ request()->routeIs('lms.admin.quiz.*') || request()->routeIs('lms.admin.question.*') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('lms.admin.quiz.index') }}"><i class="fas fa-chalkboard-user"></i>
                <span>Manajemen Quiz</span></a>
        </li>
        <li class="{{ request()->routeIs('lms.admin.enrollment.*') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('lms.admin.enrollment.index') }}"><i class="fas fa-key"></i>
                <span>Manajemen Enrollments</span></a>
        <li class="{{ request()->routeIs('lms.admin.request-access.*') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('lms.admin.request-access.index') }}"><i class="fas fa-key"></i>
                <span>Manajemen Request</span></a>
        </li>
        <li class="{{ request()->routeIs('lms.admin.token.*') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('lms.admin.token.index') }}"><i class="fas fa-key"></i>
                <span>Manajemen Token</span></a>
        </li>
        @endcan

        @can('role-list')
        <li class="menu-header">Menu Pengguna</li>
        <li
            class="nav-item dropdown {{ request()->routeIs('permission.index') || request()->routeIs('permission.edit') || request()->routeIs('permission.delete') || request()->routeIs('role.index') || request()->routeIs('role.edit') || request()->routeIs('role.delete') || request()->routeIs('role.show') || request()->routeIs('user.index') || request()->routeIs('user.edit') || request()->routeIs('user.delete') || request()->routeIs('user.show') || request()->routeIs('user.profile') ? 'active' : '' }}">
            <a href="#" class="menu nav-link has-dropdown" data-toggle="dropdown"><i class="fa-solid fa-user-tie"></i>
                <span>User</span></a>
            <ul class="dropdown-menu">
                <li
                    class="{{ request()->routeIs('user.index') || request()->routeIs('user.edit') || request()->routeIs('user.show') || request()->routeIs('user.delete') ? 'active' : '' }}">
                    <a class="menu nav-link" href="{{ route('user.index') }}">Daftar User</a>
                </li>
                <li
                    class="{{ request()->routeIs('role.index') || request()->routeIs('role.edit') || request()->routeIs('role.show') || request()->routeIs('role.delete') ? 'active' : '' }}">
                    <a class="menu nav-link" href="{{ route('role.index') }}">Role Group</a>
                </li>
                <li
                    class="{{ request()->routeIs('permission.index') || request()->routeIs('permission.edit') || request()->routeIs('permission.show') || request()->routeIs('permission.delete') ? 'active' : '' }}">
                    <a class="menu nav-link" href="{{ route('permission.index') }}">Permission</a>
                </li>
                <li class="{{ request()->routeIs('user.profile') ? 'active' : '' }}"><a class="
                                                nav-link" href=" {{ route('user.profile') }}">Detail</a></li>
                <li class="{{ request()->routeIs('user.profile') ? 'active' : '' }}"><a class="menu nav-link"
                        href="{{ route('user.profile') }}">Ubah Password</a></li>
            </ul>
        </li>
        @endcan
    </ul>
    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
        <form action="{{ route('logout') }}" id="logout-submit1" method="POST">
            @csrf
            <button type="submit" id="logout_sidebar" class="btn btn-primary btn-lg btn-block btn-icon-split logout">
                <i class="fas fa-sign-out-alt pl-1 pr-2"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
    @include('partials.logout_confirm')
</aside>