<nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            @can("is_admin")
            <!-- Sidebar untuk Admin -->
            <div class="sb-sidenav-menu-heading">Administrator</div>
            <a class="nav-link" href="/home">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            <a class="nav-link" href="/home/customers">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-solid fa-users"></i></div>
                Customers
            </a>
            <a class="nav-link" href="/transaction">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-dollar-sign"></i></div>
                Transaksi
            </a>
            @endcan

            @can("is_owner")
            <!-- Sidebar untuk Owner -->
            <div class="sb-sidenav-menu-heading">Owner</div>
            <a class="nav-link" href="/home">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            <a class="nav-link" href="/home/customers">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-solid fa-users"></i></div>
                Customers
            </a>
            <a class="nav-link" href="/transaction">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-dollar-sign"></i></div>
                Transaksi
            </a>
            {{-- <a class="nav-link" href="{{ route('admin.create') }}" >
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-person"></i></div>
                Kontrol Admin
            </a> --}}
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmin"
              aria-expanded="false" aria-controls="collapseAdmin">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-columns"></i></div>
                Kontrol Admin
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-fw fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseAdmin" aria-labelledby="headingOne">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('admin.create') }}" >Tambah Admin</a>
                    <a class="nav-link" href="{{ route('admin.index') }}">List Admin</a>
                </nav>
            </div>
            @endcan

            @cannot("is_admin")
            @cannot("is_owner")
            <!-- Sidebar untuk Customer -->
            <div class="sb-sidenav-menu-heading">Customer</div>
            <a class="nav-link" href="/home">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-home-alt"></i></i></div>
                Home
            </a>
            <a class="nav-link" href="/point/user_point">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-paw"></i></div>
                My Point
            </a>
            @endcannot
            @endcannot

            <!-- Bagian Interface untuk semua role -->
            <div class="sb-sidenav-menu-heading">Interface</div>
            <a class="nav-link" href="/product">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-dumpster"></i></div>
                Product
            </a>
            <!-- Menu Cart hanya untuk role_id 2 -->
            @if (auth()->user()->role_id == 2)
            <a class="nav-link" href="/cart">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-shopping-cart"></i></div>
                Cart
            </a>
            @endif
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
              aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-columns"></i></div>
                Order
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-fw fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="/order/order_data">Order Data</a>
                    <a class="nav-link" href="/order/order_history">Order History</a>
                </nav>
            </div>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Currently logged in as:</div>
        {{ auth()->user()->role->role_name }}
    </div>
    
</nav>
