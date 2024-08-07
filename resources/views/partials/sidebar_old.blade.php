<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}">ABANGKOMANDAN</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/img/favicon.ico') }}" style="width: 24px" alt="">
        </a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Beranda</li>
        <li class="{{ request()->is('/') ? 'active' : '' }}"><a class="nav-link menu" href="{{ route('dashboard') }}"><i
                    class="fas fa-fire"></i>
                <span>Dashboard</span></a>
        </li>
        <li class="menu-header">Menu Utama</li>
        @can('daftar-kompetensi')
        <li class="{{ request()->routeIs('kompetensi.index') ? 'active' : '' }}"><a class="menu nav-link"
                href="{{ route('kompetensi.index') }}"><i class='fa-regular fa-rectangle-list'></i>
                <span>Daftar Kompetensi</span></a>
        </li>
        @endcan
        @can('kompetensi-asn-list')
        <li class="{{ request()->routeIs('kompetensiasn.index') ? 'active' : '' }}"><a class="menu nav-link"
                href="{{ route('kompetensiasn.index') }}"><i class="fa-solid fa-fingerprint"></i>
                <span>Kompetensi ASN</span></a>
        </li>
        @endcan
        @can('jp-bangkom')
        <li class="{{ request()->routeIs('jpbangkom.index') ? 'active' : '' }}"><a class="menu nav-link"
                href="{{ route('jpbangkom.index') }}"><i class='fa-regular fa-hourglass-half'></i>
                <span>20 JP Bang Kom</span></a>
        </li>
        @endcan
        @can('kesenjangan-list')
        <li class=""><a class="menu nav-link" href="{{ route('blank-page') }}"><i class="fa-solid fa-code-compare"></i>
                <span>Kesenjangan Kompetensi</span></a>
        </li>
        @endcan
        @can('usulan-list')
        <li
            class="{{ request()->routeIs('usulan_bangkom') || request()->routeIs('form_usulan') || request()->routeIs('update_status') || request()->routeIs('usulan_bangkom.create') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('usulan_bangkom') }}"><i class="fa fa-file-pen"></i>
                <span>Usulan Bang Kom</span></a>
        </li>
        @endcan
        {{-- <li
            class="dropdown {{ request()->routeIs('usulan_bangkom') || request()->routeIs('form_usulan') || request()->routeIs('update_status') || request()->routeIs('usulan_bangkom.create') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa fa-file-pen"></i>
                <span>Usulan Bang Kom</span></a>
            <ul class="dropdown-menu">
                <li class="{{ request()->routeIs('usulan_bangkom') ? 'active' : '' }}"><a class="nav-link"
                        href="{{ route('usulan_bangkom') }}">Daftar Usulan</a>
                </li>
                <li
                    class="{{ request()->routeIs('form_usulan') || request()->routeIs('update_status') || request()->routeIs('usulan_bangkom.create') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('form_usulan') }}">Form Usulan</a>
                </li>
            </ul>
        </li> --}}
        {{-- <li
            class="dropdown {{ request()->routeIs('pengiriman') || request()->routeIs('form_pengiriman') || request()->routeIs('pengiriman.create') || request()->routeIs('pengiriman.edit') ? 'active' : '' }}">
            <a href="{{ route('pengiriman') }}" class="nav-link has-dropdown" data-toggle="dropdown"><i
                    class="fa-regular fa-paper-plane"></i> <span>Pengiriman Bang Kom</span></a>
            <ul class="dropdown-menu" style="display: none;">
                <li
                    class="{{ request()->routeIs('pengiriman') || request()->routeIs('pengiriman.edit') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('pengiriman') }}">Daftar Pengiriman</a>
                </li>
                <li
                    class="{{ request()->routeIs('form_pengiriman') || request()->routeIs('pengiriman.create')  ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('form_pengiriman') }}">Form Kendali</a>
                </li>
            </ul>
        </li> --}}
        @can('pengiriman-list')
        <li
            class="{{ request()->routeIs('pengiriman.index') || request()->routeIs('pengiriman.edit') || request()->routeIs('pengiriman.create') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('pengiriman.index') }} "><i class="fa-regular fa-paper-plane"></i>
                <span>Pengiriman Bang Kom</span></a>
        </li>
        @endcan
        @can('laporan-list')
        <li
            class="{{ request()->routeIs('laporan.index') || request()->routeIs('laporan.edit') || request()->routeIs('laporan.form_laporan') || request()->routeIs('laporan.update') || request()->routeIs('laporan.create') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('laporan.index') }} "><i class="fa-solid fa-graduation-cap"></i>
                <span>Laporan Bang Kom</span></a>
        </li>
        @endcan

        <li class="menu-header">Rekapitulasi</li>
        @can('usulan-list')
        <li
            class="dropdown {{ request()->routeIs('rekapitulasi.usulan') || request()->routeIs('rekapitulasi.usulan_jenis_diklat') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa-solid fa-calculator"></i>
                <span>Rekapitulasi Usulan</span></a>
            <ul class="dropdown-menu">
                <li class="{{ request()->routeIs('rekapitulasi.usulan') ? 'active' : '' }}"><a class="menu nav-link"
                        href="{{ route('rekapitulasi.usulan') }}">
                        <span>Berdasarkan Sumber</span></a>
                </li>
                <li class="{{ request()->routeIs('rekapitulasi.usulan_jenis_diklat') ? 'active' : '' }}"><a
                        class="menu nav-link" href="{{ route('rekapitulasi.usulan_jenis_diklat') }}">
                        <span>Berdasarkan Jenis Diklat</span></a>
                </li>
            </ul>
        </li>
        @endcan
        @can('laporan-list')
        <li
            class="dropdown {{ request()->routeIs('rekapitulasi.index') || request()->routeIs('rekapitulasi.pd') || request()->routeIs('rekapitulasi.asn') || request()->routeIs('rekapitulasi.jenis_diklat') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa-solid fa-calculator"></i>
                <span>Rekapitulasi Laporan</span></a>
            <ul class="dropdown-menu">
                <li class="{{ request()->routeIs('rekapitulasi.pd') ? 'active' : '' }}"><a class="nav-link"
                        href="{{ route('rekapitulasi.pd') }}">Berdasarkan Laporan</a>
                </li>
                <li class="{{ request()->routeIs('rekapitulasi.asn') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('rekapitulasi.asn') }}">Berdasarkan ASN</a>
                </li>
                <li class="{{ request()->routeIs('rekapitulasi.jenis_diklat') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('rekapitulasi.jenis_diklat') }}">Berdasarkan Jenis Diklat</a>
                </li>
            </ul>
        </li>
        @endcan

        @can('role-list')
        <li
            class="dropdown {{ request()->routeIs('rekapitulasi.login_terbanyak') || request()->routeIs('rekapitulasi.login_terbaru') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa-solid fa-calculator"></i>
                <span>Aktifitas User</span></a>
            <ul class="dropdown-menu">
                <li class="{{ request()->routeIs('rekapitulasi.login_terbaru') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('rekapitulasi.login_terbaru') }}">Aktifitas Terbaru</a>
                </li>
                <li class="{{ request()->routeIs('rekapitulasi.login_terbanyak') ? 'active' : '' }}"><a class="nav-link"
                        href="{{ route('rekapitulasi.login_terbanyak') }}">Aktifitas Terbanyak</a>
                </li>
            </ul>
        </li>
        @endcan

        @can('chat')
        <li class="menu-header">Chat</li>
        <li class="{{ request()->routeIs('chat.index') ? 'active' : '' }}"><a class="menu nav-link"
                href="{{ route('chat.index') }}"><i class="fa-regular fa-message"></i>
                <span>Pesan</span></a>
            @endcan
        <li class="menu-header">Pendukung</li>
        @can('petunjuk-list')
        <li
            class="{{ request()->routeIs('pendukung.petunjuk') || request()->routeIs('pendukung.petunjuk.edit') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('pendukung.petunjuk') }}"><i class="fa-solid fa-file-pdf"></i>
                <span>Petunjuk</span></a>
        </li>
        @endcan
        <li
            class="{{ request()->routeIs('pendukung.about') || request()->routeIs('pendukung.about.edit') ? 'active' : '' }}">
            <a class="menu nav-link" href="{{ route('pendukung.about') }}"><i class="fa-solid fa-code"></i>
                <span>About</span></a>
        </li>
        @can('master-data')
        <li class="menu-header">Referensi</li>
        <li
            class="nav-item dropdown {{ request()->routeIs('md_perangkatdaerah') || request()->routeIs('md_diklat.index') || request()->routeIs('md_diklatstruktural') || request()->routeIs('md_diktekfungs') || request()->routeIs('md_pegawai.index') ? 'active' : '' }}">
            <a href="#" class="menu nav-link has-dropdown" data-toggle="dropdown"><i class="fa fa-cogs"
                    aria-hidden="true"></i>
                <span>Master Data</span></a>
            <ul class="dropdown-menu">
                <li class="{{ request()->routeIs('md_perangkatdaerah') ? 'active' : '' }}">
                    <a class="menu nav-link" href="{{ route('md_perangkatdaerah') }}">Perangkat
                        Daerah</a>
                </li>
                <li class="{{ request()->routeIs('md_diklat.index') ? 'active' : '' }}"><a class="
                                        nav-link" href="{{ route('md_diklat.index') }}">Diklat</a></li>
                <li class="{{ request()->routeIs('md_diklatstruktural') ? 'active' : '' }}">
                    <a class="menu nav-link" href="{{ route('md_diklatstruktural') }}">Diklat
                        Struktural</a>
                </li>
                <li class="{{ request()->routeIs('md_diktekfungs') ? 'active' : '' }}">
                    <a class="menu nav-link" href="{{ route('md_diktekfungs') }}">Diklat Teknis
                        Fungsional</a>
                </li>
                <li class="{{ request()->routeIs('md_pegawai.index') ? 'active' : '' }}"><a class="
                                        nav-link" href="{{ route('md_pegawai.index') }} ">Pegawai</a>
                </li>
            </ul>
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