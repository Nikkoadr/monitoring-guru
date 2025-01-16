        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
            <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">SMKMUHKDH</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                <img src="{{ asset('assets/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>
            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
                </div>
            </div>
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="/home" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                    </a>
                </li>
                <li class="nav-item">
                    @php
                        $isKaryawan = DB::table('karyawan')->where('id_user', auth()->id())->exists();
                    @endphp

                    @if (!$isKaryawan)
                        <a href="/kbm" class="nav-link {{ request()->is('kbm') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-people-roof"></i>
                            <p>
                                KBM
                            </p>
                        </a>
                    @endif
                </li>
                @foreach (['admin' => '/data_izin_siswa', 'siswa' => 'req_izin_siswa'] as $role => $href)
                    @if (
                        ($role === 'admin' &&
                        (auth()->user()->can('admin') || 
                        \DB::table('kesiswaan')
                            ->join('guru', 'kesiswaan.id_guru', '=', 'guru.id')
                            ->where('guru.id_user', auth()->id())
                            ->exists())) 
                        || auth()->user()->can($role)
                    )
                    <li class="nav-item">
                        <a href="{{ $href }}" class="nav-link {{ request()->is(ltrim($href, '/')) ? 'active' : '' }}">
                            <i class="nav-icon {{ $role === 'admin' ? 'fa-regular fa-envelope' : 'fa-solid fa-circle-info' }}"></i>
                            <p>{{ $role === 'admin' ? 'Data Izin Siswa' : 'Izin Siswa' }}</p>
                        </a>
                    </li>
                    @endif
                @endforeach
                @can('admin')
                <li class="nav-item">
                    <a href="/data_izin_pendidik" class="nav-link {{ request()->is('data_izin_pendidik') ? 'active' : '' }}">
                    <i class="nav-icon fa-solid fa-clipboard-user"></i>
                    <p>
                        Izin Pendidik
                    </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/data_prensensi_pendidik" class="nav-link {{ request()->is('data_prensensi_pendidik') ? 'active' : '' }}">
                    <i class="nav-icon fa-solid fa-fingerprint"></i>
                    <p>
                        Presensi Pendidik
                    </p>
                    </a>
                </li>
                <li class="nav-item menu-open">
                    <a href="" class="nav-link {{ in_array(request()->path(), ['data_guru', 'data_user', 'data_mapel', 'data_role', 'data_jurusan', 'data_kelas', 'data_siswa', 'data_ketua_kelas', 'data_walas', 'data_ketua_kelas', 'data_walas','data_waka','data_kepsek','data_kesiswaan','data_karyawan']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-database"></i>
                    <p>
                        Database
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="/data_role" class="nav-link {{ request()->is('data_role') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Role</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_user" class="nav-link {{ request()->is('data_user') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_guru" class="nav-link {{ request()->is('data_guru') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Guru</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_karyawan" class="nav-link {{ request()->is('data_karyawan') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Karyawan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_kepsek" class="nav-link {{ request()->is('data_kepsek') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data kepala Sekolah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_waka" class="nav-link {{ request()->is('data_waka') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data WaKa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_kesiswaan" class="nav-link {{ request()->is('data_kesiswaan') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Kesiswaan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_mapel" class="nav-link {{ request()->is('data_mapel') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Mata Pelajaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_jurusan" class="nav-link {{ request()->is('data_jurusan') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Jurusan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_kelas" class="nav-link {{ request()->is('data_kelas') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Kelas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_walas" class="nav-link {{ request()->is('data_walas') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Wali Kelas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_ketua_kelas" class="nav-link {{ request()->is('data_ketua_kelas') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Ketua kelas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/data_siswa" class="nav-link {{ request()->is('data_siswa') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Data Siswa</p>
                        </a>
                    </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="/laporan" class="nav-link {{ request()->is('laporan') ? 'active' : '' }}">
                    <i class="nav-icon fa-regular fa-folder-open"></i>
                    <p>
                        Laporan
                    </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/setting" class="nav-link {{ request()->is('setting') ? 'active' : '' }}">
                    <i class="nav-icon fa-solid fa-sliders"></i>
                    <p>
                        Setting
                    </p>
                    </a>
                </li>
                @endcan
                @php
                    $isGuru = DB::table('guru')
                                ->where('id_user', Auth::user()->id)
                                ->exists();
                @endphp
                @if($isGuru || $isKaryawan)
                <li class="nav-item">
                    <a href="/presensi_pendidik" class="nav-link {{ request()->is('presensi_pendidik') ? 'active' : '' }}">
                    <i class="nav-icon fa-solid fa-clipboard-user"></i>
                    <p>
                        Presensi
                    </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/request_izin_pendidik" class="nav-link {{ request()->is('req_izin_pendidik') ? 'active' : '' }}">
                    <i class="nav-icon fa-solid fa-code-pull-request"></i>
                    <p>
                        Request Izin
                    </p>
                    </a>
                </li>
                @else
                    
                @endif
                @php
                    $isWalas = DB::table('walas')
                                ->join('guru', 'walas.id_guru', '=', 'guru.id')
                                ->where('guru.id_user', Auth::user()->id)
                                ->exists();
                @endphp
                @if($isWalas)
                <li class="nav-item menu-open">
                    <a href="" class="nav-link {{ in_array(request()->path(), ['data_guru', 'data_user', 'data_mapel', 'data_role', 'data_jurusan', 'data_kelas', 'data_siswa', 'data_ketua_kelas', 'data_walas', 'data_ketua_kelas', 'data_walas','data_waka','data_kepsek','data_kesiswaan','data_karyawan']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-database"></i>
                    <p>
                        Database
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/data_siswa" class="nav-link {{ request()->is('data_siswa') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Siswa</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/data_ketua_kelas" class="nav-link {{ request()->is('data_ketua_kelas') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Ketua kelas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                    <li class="nav-item">
                        <a href="/laporan" class="nav-link {{ request()->is('laporan') ? 'active' : '' }}">
                        <i class="nav-icon fa-regular fa-folder-open"></i>
                        <p>
                            Laporan
                        </p>
                        </a>
                    </li>
                @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>