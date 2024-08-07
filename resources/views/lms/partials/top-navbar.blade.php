<div class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a>
        </li>
    </ul>
    <div class="search-element">
        <form action="{{ route('lms.course.index') }}" method="GET">
            <input class="form-control" type="search" name="search_course" placeholder="Pencarian Kursus.."
                data-width="250" autocomplete="off" @cannot('kompetensi-asn-list') disabled @endcannot
                value="{{ request('search_course') }}">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>
<ul class="navbar-nav navbar-right">
    {{-- @php
    $laporan_count = getCountDataLaporan();
    @endphp
    <li class="dropdown dropdown-list-toggle">
        <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle"><i
                class="far fa-envelope beep"></i>
            @if ($laporan_count > 0)
            &nbsp;&nbsp;<sup class="text-white bg-danger"><span class="p-1">{{
                    number_format($laporan_count['laporanDitinjau'], 0) }}</span></sup>
            @endif
        </a>
        <div class="dropdown-menu dropdown-list dropdown-menu-right">
            <div class="dropdown-header">Pesan
            </div>
            <div class="dropdown-list-content dropdown-list-icons">
                @if ($laporan_count['laporanDiperbaiki'] > 0)
                <a href="{{ route('laporan.index') }}" class="dropdown-item dropdown-item-unread">
                    <div class="dropdown-item-icon bg-info text-white">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        <span>Laporan yang memerlukan perbaikan sejumlah :
                            <b class="text-warning">{{ number_format($laporan_count['laporanDiperbaiki'], 0) }}</b>
                            Laporan </span>
                        <div class="time">{{ Carbon\Carbon::now()->diffForHumans() }}</div>
                    </div>
                </a>
                @endif
                @if ($laporan_count['laporanDitinjau'] > 0)
                <a href="{{ route('laporan.index') }}" class="dropdown-item dropdown-item-unread">
                    <div class="dropdown-item-icon bg-warning text-white">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        <span>Laporan yang memerlukan verifikasi sejumlah :
                            <b class="text-warning">{{ number_format($laporan_count['laporanDitinjau'], 0) }}</b>
                            Laporan </span>
                        <div class="time">{{ Carbon\Carbon::now()->diffForHumans() }}</div>
                    </div>
                </a>
                @endif
                @if ($laporan_count['laporanDisetujui'] > 0)
                <a href="{{ route('rekapitulasi.pd') }}" class="dropdown-item dropdown-item-unread">
                    <div class="dropdown-item-icon bg-success text-white">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        <span>Laporan yang disetujui sejumlah :
                            <b class="text-success">{{ number_format($laporan_count['laporanDisetujui'], 0) }}</b>
                            Laporan </span>
                        <div class="time">{{ Carbon\Carbon::now()->diffForHumans() }}</div>
                    </div>
                </a>
                @endif
                @if ($laporan_count['laporanDitolak'] > 0)
                <a href="{{ route('rekapitulasi.pd') }}" class="dropdown-item dropdown-item-unread">
                    <div class="dropdown-item-icon bg-danger text-white">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        <span>Laporan yang ditolak sejumlah :
                            <b class="text-danger">{{ number_format($laporan_count['laporanDitolak'], 0) }}</b>
                            Laporan </span>
                        <div class="time">{{ Carbon\Carbon::now()->diffForHumans() }}</div>
                    </div>
                </a>
                @endif
            </div>
            <div class="dropdown-footer text-center">
                <a href="{{ route('laporan.index') }}">Lihat semua <i class="fas fa-chevron-right"></i></a>
            </div>
        </div> --}}
    </li>

    <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
            class="nav-link notification-toggle nav-link-lg"><i class="far fa-bell"></i></a>
        <div class="dropdown-menu dropdown-list dropdown-menu-right">
            <div class="dropdown-header">Notifikasi
            </div>
            <div class="dropdown-list-content dropdown-list-icons">
                <a href="" class="dropdown-item dropdown-item-unread">
                    <div class="dropdown-item-icon bg-primary text-white">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        Selamat datang di <b>ABANGKOMANDAN</b>
                        <div class="time text-primary">{{ Carbon\Carbon::now()->diffForHumans() }}</div>
                    </div>
                </a>
                <a href="" class="dropdown-item">
                    <div class="dropdown-item-icon bg-success text-white">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="dropdown-item-desc">
                        <b>Anda</b> berhasil <b>Login</b> dengan <b>Sukses</b>
                        <div class="time">{{ Carbon\Carbon::now()->diffForHumans() }}</div>
                    </div>
                </a>
            </div>
            <div class="dropdown-footer text-center">
                <a href="{{ route('user.profile') }}">Lihat semua <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
    </li>
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <div class="d-sm-none d-lg-inline-block">Hai, {{ Auth::user()->user_name }}</div>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-title">Anda Aktif (Logged In)</div>
            <a href="{{ route('user.profile') }}" class="dropdown-item has-icon">
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