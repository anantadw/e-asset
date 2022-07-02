<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
    <ul class="navbar-nav navbar-right ml-auto">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">{{ session()->get('user_name', 'Nama') }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="features-profile.html" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profil
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route(((session()->get('user_role') === 1) ? 'admin' : 'user') . '-index') }}">E-Asset</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route(((session()->get('user_role') === 1) ? 'admin' : 'user') . '-index') }}">EA</a>
        </div>
        <ul class="sidebar-menu">
            @if (session()->get('user_role') === 0)
                <li class="nav-item @if ($link === 'Home') active @endif">
                    <a href="{{ route('user-index') }}" class="nav-link"><i class="fas fa-home"></i><span>Beranda</span></a>
                </li>
                <li class="nav-item @if ($link === 'Request') active @endif">
                    <a href="{{ route('user-request') }}" class="nav-link"><i class="fas fa-clipboard-list"></i></i><span>Pinjam Barang</span></a>
                </li>
                <li class="nav-item @if ($link === 'History') active @endif">
                    <a href="{{ route('user-history') }}" class="nav-link"><i class="fas fa-exchange-alt"></i></i><span>Riwayat Transaksi</span></a>
                </li>
            @else
                <li class="nav-item @if ($link === 'Dashboard') active @endif">
                    <a href="{{ route('admin-index') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dasbor</span></a>
                </li>
                <li class="nav-item @if ($link === 'ItemAdd' || $link === 'ItemCategory') active @endif">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-cubes"></i><span>Barang</span></a>
                    <ul class="dropdown-menu">
                        <li class="@if ($link === 'ItemAdd') active @endif"><a class="nav-link" href="{{ route('admin-item-add') }}">Tambah Barang</a></li>
                        <li class="@if ($link === 'ItemCategory') active @endif"><a class="nav-link" href="{{ route('admin-category') }}">Kategori</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown @if ($link === 'AccountsAdmin' || $link === 'AccountsUser') active @endif">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Data Akun</span></a>
                    <ul class="dropdown-menu">
                        <li class="@if ($link === 'AccountsAdmin') active @endif"><a class="nav-link" href="{{ route('admin-accounts-admin') }}">Admin</a></li>
                        <li class="@if ($link === 'AccountsUser') active @endif"><a class="nav-link" href="{{ route('admin-accounts-user') }}">Pengguna</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown @if ($link === 'TransactionIn' || $link === 'TransactionOut') active @endif">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-exchange-alt"></i><span>Riwayat Transaksi</span></a>
                    <ul class="dropdown-menu">
                        <li class="@if ($link === 'TransactionIn') active @endif"><a class="nav-link" href="{{ route('admin-incoming-transaction') }}">Transaksi Masuk</a></li>
                        <li class="@if ($link === 'TransactionOut') active @endif"><a class="nav-link" href="{{ route('admin-outgoing-transaction-pending') }}">Transaksi Keluar</a></li>
                    </ul>
                </li>
                <li class="nav-item @if ($link === 'Direct') active @endif">
                    <a href="#" class="nav-link"><i class="fas fa-clipboard-list"></i><span>Pinjam Langsung</span></a>
                </li>
            @endif
        </ul>
    </aside>
</div>