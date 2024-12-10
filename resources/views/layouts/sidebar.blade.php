        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
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
                    <a href="home" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="kbm" class="nav-link {{ request()->is('kbm') ? 'active' : '' }}">
                    <i class="nav-icon fa-solid fa-people-roof"></i>
                    <p>
                        KBM
                    </p>
                    </a>
                </li>
                @can('admin')
                <li class="nav-item menu-open">
                    <a href="" class="nav-link {{ in_array(request()->path(), ['data_guru', 'data_user', 'data_mapel', 'data_role', 'data_jurusan', 'data_kelas', 'data_siswa', 'data_ketua_kelas', 'data_walas']) ? 'active' : '' }}">
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
                    // Cek apakah user adalah walas
                    $isWalas = DB::table('walas')
                                ->join('guru', 'walas.id_guru', '=', 'guru.id')
                                ->where('guru.id_user', Auth::user()->id)
                                ->exists();
                @endphp
                @if($isWalas)
                <li class="nav-item menu-open">
                    <a href="" class="nav-link {{ in_array(request()->path(), ['data_guru', 'data_user', 'data_mapel', 'data_role', 'data_jurusan', 'data_kelas', 'data_siswa', 'data_ketua_kelas', 'data_walas', 'data_ketua_kelas', 'data_walas']) ? 'active' : '' }}">
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