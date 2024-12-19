<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; 
use App\Models\Service; 
use Illuminate\Support\Facades\View; 
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function __construct()
    {
        // Menambahkan title untuk setiap view yang dipanggil
        View::share('title', 'Sales Report'); // Sesuaikan dengan title yang diinginkan
    }

    public function index(Request $request)
    {
        // Mendapatkan filter dari request, default adalah 'weekly'
        $filter = $request->input('filter', 'weekly');
        
        // Mengatur tanggal awal dan akhir berdasarkan filter
        if ($filter == 'monthly') {
            $startDate = now('Asia/Jakarta')->startOfMonth(); // Awal bulan ini dengan zona waktu Jakarta
            $endDate = now('Asia/Jakarta')->endOfMonth(); // Akhir bulan ini dengan zona waktu Jakarta
        } else {
            $startDate = now('Asia/Jakarta')->subWeek(); // 1 minggu terakhir dengan zona waktu Jakarta
            $endDate = now('Asia/Jakarta'); // Sekarang dengan zona waktu Jakarta
        }
    
        // Menambahkan log untuk memeriksa rentang tanggal
        Log::info("Start Date: " . $startDate);
        Log::info("End Date: " . $endDate);
    
        // Mengambil data order berdasarkan rentang waktu dengan pagination
        $orders = Order::with(['user', 'orderDetails.product'])
            ->where('is_done', 1)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->paginate(10); // Pagination tetap LengthAwarePaginator
    
        // Transformasi data dilakukan di tampilan atau dengan fungsi tambahan
        $reports = $orders->getCollection()->map(function ($order) {
            return [
                'id' => $order->id,
                'customer_name' => $order->user->fullname,
                'products' => $order->orderDetails->map(function ($detail) {
                    return [
                        'name' => $detail->product->product_name,
                        'quantity' => $detail->quantity,
                    ];
                }),
                'total_price' => $order->orderDetails->sum(function ($detail) {
                    return $detail->price * $detail->quantity;
                }),
                'date' => $order->updated_at->format('d M Y'),
            ];
        });
    
        // Ganti koleksi pada paginator dengan hasil transformasi
        $orders->setCollection($reports);

        $totalSales = $orders->sum('total_price'); // Menghitung total sales

        return view('report.index', [
            'orders' => $orders,
            'filter' => $filter,
            'totalSales' => $totalSales, // Kirim total sales ke view
        ]);

    
        // Menambahkan log untuk melihat jumlah laporan yang diambil
        Log::info("Number of reports found: " . $reports->count());
    
        return view('report.index', compact('orders', 'filter')); // Ganti 'reports' dengan 'orders'
    }
    

    public function service(Request $request)
{
    // Mendapatkan filter dari request, default adalah 'weekly'
    $filter = $request->input('filter', 'weekly');
    
    // Mengatur tanggal awal dan akhir berdasarkan filter
    if ($filter == 'monthly') {
        $startDate = now('Asia/Jakarta')->startOfMonth();
        $endDate = now('Asia/Jakarta')->endOfMonth();
    } else {
        $startDate = now('Asia/Jakarta')->subWeek();
        $endDate = now('Asia/Jakarta');
    }

    // Log untuk debugging
    Log::info("Start Date: " . $startDate);
    Log::info("End Date: " . $endDate);

    // Ambil data service dengan pagination
    $services = Service::whereBetween('order_date', [$startDate, $endDate])
        ->whereIn('status', ['done', 'rejected'])
        ->orderBy('updated_at', 'desc')
        ->paginate(10); // Pagination setiap 10 data

    // Mengubah data menjadi array yang sesuai dengan tampilan
    $services->getCollection()->transform(function ($service) {
        return [
            'id' => $service->id,
            'customer_name' => $service->user->fullname,
            'laptop_model' => $service->laptop_model,
            'problem_description' => $service->problem_description,
            'price' => $service->price,
            'order_date' => $service->order_date->format('d M Y'),
            'end_date' => $service->updated_at->format('d M Y'),
            'status' => $service->status,
        ];
    });

    $totalPrice = $services->sum('price'); // Menghitung total harga service
    return view('report.service', compact('services', 'filter', 'totalPrice'));
    // Return view dengan data paginated
    return view('report.service', compact('services', 'filter'));
}

}
