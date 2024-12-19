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

        // Mengambil data order berdasarkan rentang waktu
        $reports = Order::with(['user', 'orderDetails.product'])
            ->where('is_done', 1)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->get()
            ->map(function ($order) {
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

        // Menambahkan log untuk melihat jumlah laporan yang diambil
        Log::info("Number of reports found: " . $reports->count());

        return view('report.index', compact('reports', 'filter'));
    }

    public function service(Request $request)
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

        // Ambil data service berdasarkan rentang waktu dan status 'done' atau 'rejected'
        $services = Service::whereBetween('order_date', [$startDate, $endDate])
            ->whereIn('status', ['done', 'rejected']) // Mengambil servis dengan status done atau rejected
            ->orderBy('updated_at', 'desc') // Mengurutkan berdasarkan tanggal update
            ->get();

        // Menambahkan log untuk memeriksa jumlah data yang diambil
        Log::info("Number of services found: " . $services->count());

        // Menambahkan log untuk memeriksa data yang diambil
        foreach ($services as $service) {
            Log::info("Service ID: " . $service->id . " | Customer Name: " . $service->user->fullname . " | Status: " . $service->status);
        }

        // Mengubah data service menjadi array yang sesuai dengan tampilan
        $services = $services->map(function ($service) {
            return [
                'id' => $service->id,
                'customer_name' => $service->user->fullname, // Pastikan relasi user dimuat
                'laptop_model' => $service->laptop_model,
                'problem_description' => $service->problem_description,
                'price' => $service->price,
                'order_date' => $service->order_date->format('d M Y'),
                'end_date' => $service->updated_at->format('d M Y'),
                'status' => $service->status, // Menambahkan status untuk laporan
            ];
        });

        // Menambahkan log untuk melihat jumlah data yang sudah diproses
        Log::info("Number of services after mapping: " . $services->count());

        // Mengembalikan view dengan data yang sudah diproses
        return view('report.service', compact('services', 'filter'));
    }
}
