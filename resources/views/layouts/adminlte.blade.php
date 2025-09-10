<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Manajemen Gudang')</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    @yield('css')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">
                            {{ Auth::user()->email }}
                        </span>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link">
                <span class="brand-text font-weight-light">Gudang Shabat Printing</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="#" class="d-block">
                            {{ Auth::user()->role == 'admin' ? 'Admin' : 'Operator' }}
                        </a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <!-- DASHBOARD -->
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @if (Auth::user()->role == 'admin')
                            <!-- MASTER (top level, tanpa sub) -->
                            <li class="nav-header">MASTER</li>

                            <li class="nav-item">
                            <a href="{{ route('barangs.index') }}"
                            class="nav-link {{ request()->routeIs('barangs.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i><p>Barang</p>
                            </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-truck"></i>
                                    <p>Supplier</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('kategoris.index') }}" class="nav-link {{ request()->routeIs('kategoris.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-tags"></i>
                                    <p>Kategori</p>
                                </a>
                            </li>

                            <!-- TRANSAKSI -->
                            <li class="nav-header">TRANSAKSI</li>
                            <li class="nav-item">
                                <a href="{{ route('transaksi.masuk') }}" class="nav-link {{ request()->routeIs('transaksi.masuk') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-arrow-down"></i>
                                    <p>Barang Masuk</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('transaksi.keluar') }}" class="nav-link {{ request()->routeIs('transaksi.keluar') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-arrow-up"></i>
                                    <p>Barang Keluar</p>
                                </a>
                            </li>

                            <!-- LAPORAN -->
                            <li class="nav-header">LAPORAN</li>
                            <li class="nav-item">
                                <a href="{{ route('laporan.stok') }}" class="nav-link {{ request()->routeIs('laporan.stok') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>Persediaan Barang</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('laporan.transaksi') }}" class="nav-link {{ request()->routeIs('laporan.transaksi') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-file-alt"></i>
                                    <p>Transaksi Barang</p>
                                </a>
                            </li>

                            <!-- SETTINGS (paling bawah, ganti dari Manajemen User) -->
                            <li class="nav-header">PENGATURAN</li>
                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>
                                        Settings
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>User Management</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('karyawans.index') }}" class="nav-link {{ request()->routeIs('karyawans.*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Data Karyawan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('divisis.index') }}" class="nav-link {{ request()->routeIs('divisis.*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Divisi</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if (Auth::user()->role == 'operator')
                            <!-- TRANSAKSI OPERATOR -->
                            <li class="nav-header">TRANSAKSI</li>
                            <li class="nav-item">
                                <a href="{{ route('operator.transaksi.keluar') }}" class="nav-link">
                                    <i class="nav-icon fas fa-arrow-up"></i>
                                    <p>Keluar Barang</p>
                                </a>
                            </li>

                            <!-- LAPORAN OPERATOR -->
                            <li class="nav-header">LAPORAN</li>
                            <li class="nav-item">
                                <a href="{{ route('operator.history') }}" class="nav-link">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>Riwayat Transaksi</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('title')</h1>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2023 Manajemen Gudang.</strong>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @yield('js')

    <script>
        // Aktifkan state aktif & buka treeview sesuai URL
        $(document).ready(function() {
            var url = window.location;

            $('ul.nav-sidebar a').filter(function() {
                return this.href == url;
            }).parent().addClass('active');

            $('ul.nav-sidebar a').filter(function() {
                return this.href == url;
            }).parentsUntil('.nav-sidebar', '.has-treeview').addClass('menu-open').children('a').addClass('active');
        });
    </script>
</body>
</html>
