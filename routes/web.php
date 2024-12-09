<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, CartController, HomeController, OrderController, PointController, ReviewController, ProductController, ProfileController, RajaOngkirController, TransactionController, UserController, AdminController, ServiceController};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// pre authenticate
Route::middleware(['alreadyLogin'])->group(function () {
    // landing
    Route::get('/', function () {
        return view('landing.index', [
            "title" => "Landing",
        ]);
    });

    // Login
    Route::get('/{url}', [AuthController::class, "loginGet"])->where(["url" => "auth|auth/login"])->name("auth");
    Route::post('/auth/login', [AuthController::class, "loginPost"]);

    // Register
    Route::get('/auth/register', [AuthController::class, "registrationGet"]);
    Route::post('/auth/register', [AuthController::class, "registrationPost"]);
});

// main
Route::middleware(['auth'])->group(function () {
    // Home
    Route::controller(HomeController::class)->group(function () {
        Route::get("/home", "index");
        Route::get("/home/customers", "customers");
    });

    // profile
    Route::controller(ProfileController::class)->group(function () {
        Route::get("/profile/my_profile", "myProfile");
        Route::get("/profile/edit_profile", "editProfileGet");
        Route::post("/profile/edit_profile/{user:id}", "editProfilePost");
        Route::get("/profile/change_password", "changePasswordGet");
        Route::post("/profile/change_password", "changePasswordPost");
    });

    // Product
    Route::controller(ProductController::class)->group(function () {
        Route::get('/product', [ProductController::class, 'index'])->name('products.index');
        Route::get("/product/data/{id}", "getProductData");

        // admin only
        Route::get("/product/add_product", "addProductGet")->can("add_product", App\Models\Product::class);
        Route::post("/product/add_product", "addProductPost")->can("add_product", App\Models\Product::class);
        Route::get("/product/edit_product/{product:id}", "editProductGet")->can("edit_product", App\Models\Product::class);
        Route::post("/product/edit_product/{product:id}", "editProductPost")->can("edit_product", App\Models\Product::class);
    });

    // Order
    Route::controller(OrderController::class)->group(function () {
        Route::get("/order/order_data", "orderData");
        Route::get("/order/order_history", "orderHistory");
        Route::get("/order/order_data/{status_id}", "orderDataFilter");
        Route::get("/order/data/{order}", "getOrderData")->can("my_real_order", "order");
        Route::get("/order/getProof/{order}", "getProofOrder")->can("my_real_order", "order");

        // customer only
        Route::get('/order/make_order', [OrderController::class, 'makeOrderGet'])->name('order.make_order')->middleware('auth');
        Route::post("/order/make_order", [OrderController::class, 'makeOrderPost'])->can("create_order", App\Models\Order::class)->name('order.make_order');
        Route::get("/order/edit_order/{order}", "editOrderGet")->can("edit_order", "order");
        Route::post("/order/edit_order/{order}", "editOrderPost")->can("edit_order", "order");
        Route::get("/order/delete_proof/{order}", "deleteProof")->can("delete_proof", "order");
        Route::post("/order/cancel_order/{order}", "cancelOrder")->can("cancel_order", "order");
        Route::post("/order/upload_proof/{order}", "uploadProof")->can("upload_proof", "order");
        Route::get('/order/invoice/{orderId}', [OrderController::class, 'downloadInvoice']);

        // admin only
        Route::post("/order/reject_order/{order}", "rejectOrder")->can("reject_order", App\Models\Order::class);
        Route::post("/order/end_order/{order}", "endOrder")->can("end_order", App\Models\Order::class);
        Route::post("/order/approve_order/{order}", "approveOrder")->can("approve_order", App\Models\Order::class);        
    });

    // Ongkir
    Route::controller(RajaOngkirController::class)->group(function () {
        Route::get("/shipping/province", "province");
        Route::get("/shipping/city/{province_id}", "city");
        Route::get("/shipping/cost/{origin}/{destination}/{quantity}/{courier}", "cost");
    });

    // review
    Route::controller(ReviewController::class)->group(function () {
        Route::get("/review/product/{product}", "productReview");
        Route::get("/review/data/{review}", "getDataReview");
        Route::post("/review/add_review/", "addReview");
        Route::post("/review/edit_review/{review}", "editReview")->can("edit_review", "review");
        Route::post("/review/delete_review/{review}", "deleteReview")->can("delete_review", "review");
    });

    // transaction
    Route::controller(TransactionController::class)->group(function () {
        Route::get("/transaction", "index");
        Route::get("/transaction/add_outcome", "addOutcomeGet")->can("is_admin");
        Route::post("/transaction/add_outcome", "addOutcomePost")->can("is_admin");
        Route::get("/transaction/edit_outcome/{transaction}", "editOutcomeGet")->can("is_admin");
        Route::post("/transaction/edit_outcome/{transaction}", "editOutcomePost")->can("is_admin");
    });

    // point
    Route::controller(PointController::class)->group(function () {
        Route::get("/point/user_point", "user_point")->can("user_point", App\Models\User::class);
        Route::post("/point/convert_point", "convert_point")->can("convert_point", App\Models\User::class);
    });

    Route::get('/contact', function () {
        $title = "Contact Us"; // Atur nilai variabel title
        return view('contact.index', compact('title')); // Kirim variabel ke view
    });

    // chart
    Route::middleware(['auth'])->group(function () {
        // sales chart
        Route::get("/chart/sales_chart", function () {
            $oneWeekAgo = DB::select(DB::raw('SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 6 DAY), "%Y-%m-%d") AS date'))[0]->date;

            $now = date('Y-m-d', time());

            $array_result = [
                "one_week_ago" => $oneWeekAgo,
                "now" => $now,
            ];

            //disable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
            $array_result["data"] = DB::table("orders")
                ->selectSub("count(*)", "sales_total")
                ->selectSub("DATE_FORMAT(orders.updated_at, '%d')", "day")
                ->selectSub("DATE_FORMAT(orders.updated_at, '%Y-%m-%d')", "date")
                ->where("is_done", 1)
                ->whereBetween(DB::raw("DATE_FORMAT(orders.updated_at, '%Y-%m-%d')"), ["$oneWeekAgo", $now])
                ->groupByRaw("DATE_FORMAT(orders.updated_at, '%Y-%m-%d')")
                ->get();
            //re-enable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY'));");

            echo json_encode($array_result);
        });
        // profits chart
        Route::get("/chart/profits_chart", function () {
            $six_month_ago = DB::select(DB::raw('SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH), "%Y-%m") AS month'))[0]->month;
            $now = date('Y-m', time());
            $array_result = [
                "six_month_ago" => $six_month_ago,
                "now" => $now,
            ];

            //disable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
            $array_result["data"] = DB::table("transactions")
                ->selectSub("SUM(income) - SUM(outcome)", "profits")
                ->selectSub("DATE_FORMAT(transactions.created_at, '%Y-%m')", "date")
                ->whereBetween(DB::raw("DATE_FORMAT(transactions.created_at, '%Y-%m')"), ["$six_month_ago", $now])
                ->groupByRaw("DATE_FORMAT(transactions.created_at, '%Y-%m')")
                ->get();
            //re-enable ONLY_FULL_GROUP_BY
            DB::statement("SET sql_mode=(SELECT CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY'));");

            echo json_encode($array_result);
        });
    });

// Cart
Route::controller(CartController::class)->middleware(['auth'])->group(function () {
    Route::get('/cart', 'index')->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove'); // Tambahkan nama di sini
    Route::post('/cart/delete', [CartController::class, 'deleteFromCart'])->name('cart.delete');    // Menghapus item dari keranjang
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

});

    // Logout
    Route::post('/auth/logout', [AuthController::class, "logoutPost"]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

    Route::get('/admin/tambah_admin', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/tambah_admin', [AdminController::class, 'store'])->name('admin.store');
    
    Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index'); // Menampilkan daftar admin
    Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit'); // Form edit admin
    Route::post('/{id}', [AdminController::class, 'update'])->name('update'); // Proses update admin
});

    Route::get('/product', [ProductController::class, 'index'])->name('products.index');
    
    Route::middleware(['auth'])->group(function () {
        // Rute untuk customer
        Route::get('service', [ServiceController::class, 'index'])->name('services.index');
        Route::post('service', [ServiceController::class, 'store'])->name('services.store');
        Route::get('/service/servis_data', [ServiceController::class, 'showData'])->name('service.servis_data');
        Route::get('service/servis_history', [ServiceController::class, 'history'])->name('services.servis_history'); // Rute baru untuk history
        Route::post('service/{service}/done', [ServiceController::class, 'setDone'])->name('services.setDone');
    
        // Rute untuk admin
        Route::middleware('can:is_admin')->prefix('admin')->group(function () {
            Route::get('/services', [ServiceController::class, 'adminIndex'])->name('admin.services.index');
            Route::get('/service/servis_data', [ServiceController::class, 'showData'])->name('admin.servis_data');
            Route::get('/service/servis_history', [ServiceController::class, 'history'])->name('admin.servis_history');
            Route::post('/services/{service}/update-status', [ServiceController::class, 'updateStatus'])->name('services.updateStatus');
            Route::post('/service/{service}/approve', [ServiceController::class, 'approve'])->name('admin.services.approve');
        });
    });
    
    
    