<nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            @can("is_admin")
            <!-- Sidebar untuk Admin -->
            <a class="nav-link" href="/home">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            <a class="nav-link" href="/home/customers">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-solid fa-users"></i></div>
                Users
            </a>
            <a class="nav-link" href="/transaction">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-dollar-sign"></i></div>
                Transaksi
            </a>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
              aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-wrench"></i></div>
                Service
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-fw fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="/service/servis_data">Service Data</a>
                    <a class="nav-link" href="/service/servis_history">Service History</a>
                </nav>
            </div>
            @endcan

            @can("is_owner")
            <!-- Sidebar untuk Owner (Owner tidak dapat mengakses Order dan Service) -->
            <a class="nav-link" href="/home">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            <a class="nav-link" href="/home/customers">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-solid fa-users"></i></div>
                Users
            </a>
            <a class="nav-link" href="/transaction">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-dollar-sign"></i></div>
                Transaksi
            </a>
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
            <a class="nav-link" href="/product-logs">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-list-alt"></i></div>
                Logs
            </a>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReports" aria-expanded="false" aria-controls="collapseReports">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-chart-line"></i></div>
                Reports
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-fw fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseReports" aria-labelledby="headingOne">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('report.index') }}">Sales Report</a>
                    <a class="nav-link" href="{{ route('report.service') }}">Service Report</a>
                </nav>
            </div>            
            @endcan

            @cannot("is_admin")
            @cannot("is_owner")
            <!-- Sidebar untuk Customer -->
            <a class="nav-link" href="/home">
                <div class="sb-nav-link-icon"><i class="fas fa-fw fa-home-alt"></i></i></div>
                Home
            </a>
            <a class="nav-link" href="/point/user_point">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-paw"></i></div>
                My Point
            </a>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
              aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-wrench"></i></div>
                Service
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-fw fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="/service">Make Service</a>
                    <a class="nav-link" href="/service/servis_data">Service Data</a>
                    <a class="nav-link" href="/service/servis_history">Service History</a>
                </nav>
            </div>
            @endcannot
            @endcannot

            <!-- Bagian Interface untuk semua role -->
            <a class="nav-link" href="/product">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fw fa-dumpster"></i></div>
                Product
            </a>

            @if (auth()->user()->role_id == 1)
            <a class="nav-link" href="/cart">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-shopping-cart"></i></div>
                Cart
            </a>
            @endif

            <!-- Menu Cart hanya untuk role_id 2 -->
            @if (auth()->user()->role_id == 2)
            <a class="nav-link" href="/cart">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-shopping-cart"></i></div>
                Cart
            </a>
            <a class="nav-link" href="/contact">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-phone"></i></div>
                Contact Us
            </a>
            @endif

            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayoutsOrder"
            aria-expanded="false" aria-controls="collapseLayoutsOrder">
              <div class="sb-nav-link-icon"><i class="fas fa-fw fa-columns"></i></div>
              Order
              <div class="sb-sidenav-collapse-arrow"><i class="fas fa-fw fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="collapseLayoutsOrder" aria-labelledby="headingOne">
              <nav class="sb-sidenav-menu-nested nav">
                  @can("is_owner")
                      <!-- Hanya tampilkan Order History untuk Owner -->
                      <a class="nav-link" href="/order/order_history">Order History</a>
                  @else
                      <!-- Tampilkan Order Data dan Order History untuk Admin atau role lain -->
                      <a class="nav-link" href="/order/order_data">Order Data</a>
                      <a class="nav-link" href="/order/order_history">Order History</a>
                  @endcan
              </nav>
          </div>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Currently logged in as:</div>
        {{ auth()->user()->role->role_name }}
    </div>
</nav>
