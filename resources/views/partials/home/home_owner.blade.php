<div class="container-fluid px-4 pt-2">
    <h1 class="mt-2">Dashboard Owner</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard Owner</li>
    </ol>

    <div class="row">
        <!-- Card: Produk yang dijual -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Produk yang Dijual</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>{{ \App\Models\Product::count() }}</span>
                </div>
            </div>
        </div>

        <!-- Card: Order -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Order</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span> {{ \App\Models\Order::count() }}</span>
                </div>
            </div>
        </div>

        <!-- Card: Customer -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Customer</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>{{ \App\Models\User::where('role_id', 2)->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Card: Omset -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Omset</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>{{ number_format(\App\Models\Order::where('is_done', 1)->sum('total_price'), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sales Chart -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i> Sales Chart
                </div>
                <div class="card-body">
                    <canvas id="sales_chart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- Profits Chart -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i> Profits Chart
                </div>
                <div class="card-body">
                    <canvas id="profits_chart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>